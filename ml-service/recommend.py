"""
src/recommend.py — Konekin ML Service
Recommender class: loads all models once at startup,
exposes predict_cluster(), get_skill_query(), recommend(), recommend_by_skills().
"""

import os
import sys
import logging
import warnings

import numpy as np
import pandas as pd
import joblib
from sklearn.metrics.pairwise import cosine_similarity

warnings.filterwarnings('ignore')

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config import (
    CW_DATA_PATH, MODEL_DIR,
    KMEANS_MODEL_PATH, TFIDF_VECTORIZER_PATH,
    SCALER_UMKM_PATH, LABEL_ENCODERS_PATH,
    CLUSTER_SKILL_MAP, CLUSTER_LABEL_MAP,
    UMKM_KMEANS_FEATURES, TOP_N_DEFAULT,
    LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


class Recommender:
    """
    Hybrid KMeans + TF-IDF recommender for Konekin.

    Loads all model artifacts once at instantiation.
    All public methods are stateless after __init__.
    """

    def __init__(self):
        logger.info("[Recommender] Loading model artifacts …")
        self._load_models()
        logger.info("[Recommender] All artifacts loaded ✓")

    # ──────────────────────────────────────────────────────────────────────────
    # PRIVATE: LOAD MODELS
    # ──────────────────────────────────────────────────────────────────────────
    def _load_models(self):
        self.kmeans   = joblib.load(KMEANS_MODEL_PATH)
        self.tfidf    = joblib.load(TFIDF_VECTORIZER_PATH)
        self.scaler   = joblib.load(SCALER_UMKM_PATH)
        self.encoders = joblib.load(LABEL_ENCODERS_PATH)

        # Load full CW dataset (all rows, pre-recommendations will filter)
        self.df_cw = pd.read_csv(CW_DATA_PATH, low_memory=False)

        # Pre-compute TF-IDF matrix for all CW once
        skills_corpus = (
            self.df_cw['skills']
            .fillna('')
            .str.lower()
            .str.replace(r'\s*,\s*', ' ', regex=True)
            .str.strip()
        )
        self.tfidf_matrix = self.tfidf.transform(skills_corpus)
        self.n_clusters   = self.kmeans.n_clusters

        logger.info(
            f"[Recommender] CW dataset: {len(self.df_cw):,} rows | "
            f"TF-IDF: {self.tfidf_matrix.shape} | "
            f"Clusters: {self.n_clusters}"
        )

    # ──────────────────────────────────────────────────────────────────────────
    # PRIVATE: PREPROCESS UMKM INPUT
    # ──────────────────────────────────────────────────────────────────────────
    def _preprocess_umkm_input(self, umkm_features: dict) -> np.ndarray:
        """
        Transform raw UMKM dict → scaled feature vector for KMeans.predict().

        Handles:
        - Numeric feature computation (engineer features)
        - Categorical encoding (jenis_usaha → LabelEncoder)
        - status_legalitas → binary
        - marketplace → one-hot dummies
        - StandardScaler transform
        """
        f = umkm_features

        # ── Numeric & engineered features ─────────────────────────────────────
        omset     = float(f.get('omset', 0) or 0)
        laba      = float(f.get('laba', 0) or 0)
        aset      = float(f.get('aset', 0) or 0)
        biaya_kry = float(f.get('biaya_karyawan', 0) or 0)
        jml_plgn  = float(f.get('jumlah_pelanggan', 0) or 0)
        kap_prod  = float(f.get('kapasitas_produksi', 0) or 0)
        tk_perp   = float(f.get('tenaga_kerja_perempuan', 0) or 0)
        tk_laki   = float(f.get('tenaga_kerja_laki_laki', 0) or 0)
        thn_brdri = float(f.get('tahun_berdiri', 2020) or 2020)

        total_tk       = tk_perp + tk_laki
        rasio_laba     = (laba / omset) if omset != 0 else 0.0
        rasio_laba     = 0.0 if not np.isfinite(rasio_laba) else rasio_laba
        umur_usaha     = max(0, 2025 - thn_brdri)
        is_profitable  = 1 if laba > 0 else 0

        # ── Categorical: status_legalitas ─────────────────────────────────────
        status = str(f.get('status_legalitas', '')).strip().lower()
        status_enc = 1 if status == 'terdaftar' else 0

        # ── Categorical: jenis_usaha → LabelEncoder ──────────────────────────
        le_jenis = self.encoders.get('jenis_usaha')
        jenis_raw = str(f.get('jenis_usaha', 'Jasa'))
        if le_jenis is not None:
            try:
                jenis_enc = int(le_jenis.transform([jenis_raw])[0])
            except ValueError:
                # unseen label → use most frequent class (index 0)
                jenis_enc = 0
        else:
            jenis_enc = 0

        # ── Base numeric features (order must match UMKM_KMEANS_FEATURES) ─────
        base_features = {
            'omset': omset,
            'laba': laba,
            'aset': aset,
            'biaya_karyawan': biaya_kry,
            'jumlah_pelanggan': jml_plgn,
            'kapasitas_produksi': kap_prod,
            'total_tenaga_kerja': total_tk,
            'rasio_laba_omset': rasio_laba,
            'umur_usaha': umur_usaha,
            'is_profitable': is_profitable,
            'status_legalitas_enc': status_enc,
            'jenis_usaha_enc': jenis_enc,
        }

        # ── Marketplace one-hot ────────────────────────────────────────────────
        # Determine which marketplace dummies exist in the scaler's feature set
        mkt_options = [
            'Tokopedia', 'Shopee', 'Bukalapak',
            'Lazada', 'Website Sendiri', 'Tidak Ada',
        ]
        mkt_input = str(f.get('marketplace', 'Tidak Ada'))
        for opt in mkt_options:
            col_name = f"mkt_{opt.replace(' ', '_')}"
            base_features[col_name] = 1.0 if opt == mkt_input else 0.0

        # ── Build vector aligned to scaler features ───────────────────────────
        # Scaler was fitted on specific columns; we must match that order.
        # We'll build a DataFrame row and reindex to match scaler input.
        row_df = pd.DataFrame([base_features])

        # Try to get scaler feature names; fallback to UMKM_KMEANS_FEATURES
        try:
            scaler_features = self.scaler.feature_names_in_.tolist()
        except AttributeError:
            # StandardScaler < 1.0 doesn't store feature_names_in_
            scaler_features = list(base_features.keys())

        for col in scaler_features:
            if col not in row_df.columns:
                row_df[col] = 0.0

        X = row_df[scaler_features].fillna(0).values
        X_scaled = self.scaler.transform(X)
        return X_scaled

    # ──────────────────────────────────────────────────────────────────────────
    # PUBLIC: PREDICT CLUSTER
    # ──────────────────────────────────────────────────────────────────────────
    def predict_cluster(self, umkm_features: dict) -> int:
        """
        Preprocess UMKM features dict and predict its KMeans cluster.

        Returns:
            cluster_id (int)
        """
        X_scaled   = self._preprocess_umkm_input(umkm_features)
        cluster_id = int(self.kmeans.predict(X_scaled)[0])
        logger.info(f"[Recommender] Predicted cluster: {cluster_id}")
        return cluster_id

    # ──────────────────────────────────────────────────────────────────────────
    # PUBLIC: GET SKILL QUERY
    # ──────────────────────────────────────────────────────────────────────────
    def get_skill_query(self, cluster_id: int) -> str:
        """
        Return the skill query string for a given cluster_id.
        Falls back to a generic query if cluster_id not in map.
        """
        query = CLUSTER_SKILL_MAP.get(
            cluster_id,
            "digital marketing content social media",
        )
        logger.info(
            f"[Recommender] Cluster {cluster_id} query: '{query}'"
        )
        return query

    # ──────────────────────────────────────────────────────────────────────────
    # PUBLIC: RECOMMEND
    # ──────────────────────────────────────────────────────────────────────────
    def recommend(
        self,
        umkm_features: dict,
        top_n: int = TOP_N_DEFAULT,
        min_budget: float | None = None,
        experience_level: str | None = None,
    ) -> list[dict]:
        """
        Full recommendation pipeline for a UMKM.

        Args:
            umkm_features   : raw UMKM data dict
            top_n           : number of recommendations
            min_budget      : optional max acceptable min_budget_idr filter
            experience_level: optional filter ('Beginner'/'Intermediate'/'Expert')

        Returns:
            list of dicts, each representing a recommended CW
        """
        # 1. Cluster prediction
        cluster_id = self.predict_cluster(umkm_features)

        # 2. Skill query
        query = self.get_skill_query(cluster_id)

        # 3. Cosine similarity
        recs, cluster_id = self._score_and_filter(
            query=query,
            top_n=top_n,
            min_budget=min_budget,
            experience_level=experience_level,
            cluster_id=cluster_id,
        )

        logger.info(
            f"[Recommender] Returning {len(recs)} recommendations "
            f"(cluster={cluster_id})"
        )
        return cluster_id, recs

    # ──────────────────────────────────────────────────────────────────────────
    # PUBLIC: RECOMMEND BY SKILLS
    # ──────────────────────────────────────────────────────────────────────────
    def recommend_by_skills(
        self,
        query_skills: str,
        top_n: int = TOP_N_DEFAULT,
        min_budget: float | None = None,
        experience_level: str | None = None,
    ) -> list[dict]:
        """
        Direct skill-based recommendation (no UMKM data required).
        Useful for free-text search from frontend.
        """
        _, recs = self._score_and_filter(
            query=query_skills,
            top_n=top_n,
            min_budget=min_budget,
            experience_level=experience_level,
        )
        logger.info(
            f"[Recommender] recommend_by_skills: '{query_skills}' → "
            f"{len(recs)} results"
        )
        return recs

    # ──────────────────────────────────────────────────────────────────────────
    # PRIVATE: SCORE AND FILTER
    # ──────────────────────────────────────────────────────────────────────────
    def _score_and_filter(
        self,
        query: str,
        top_n: int,
        min_budget: float | None,
        experience_level: str | None,
        cluster_id: int | None = None,
    ) -> tuple[int | None, list[dict]]:
        """
        Core scoring logic shared by recommend() and recommend_by_skills().

        Steps:
            1. Vectorize query
            2. Compute cosine similarity vs full TF-IDF matrix
            3. Filter: is_available == 1
            4. Optional: min_budget filter
            5. Optional: experience_level filter
            6. Sort by similarity → return top_n

        Returns:
            (cluster_id, list_of_dicts)
        """
        df = self.df_cw.copy()

        # ── 1. Filter: available only ──────────────────────────────────────────
        avail_mask = df['is_available'] == 1
        df_avail   = df[avail_mask].reset_index()  # preserve original index
        mat_avail  = self.tfidf_matrix[avail_mask.values]

        # ── 2. Optional filters ────────────────────────────────────────────────
        filter_mask = pd.Series([True] * len(df_avail))

        if min_budget is not None:
            if 'min_budget_idr' in df_avail.columns:
                filter_mask &= df_avail['min_budget_idr'] <= float(min_budget)
                logger.info(
                    f"[Recommender] min_budget filter (≤{min_budget:,.0f}): "
                    f"{filter_mask.sum()} pass"
                )

        if experience_level is not None:
            exp_norm = experience_level.strip().capitalize()
            if 'experience_level' in df_avail.columns:
                filter_mask &= (
                    df_avail['experience_level'].str.capitalize() == exp_norm
                )
                logger.info(
                    f"[Recommender] experience_level filter ('{exp_norm}'): "
                    f"{filter_mask.sum()} pass"
                )

        df_filtered  = df_avail[filter_mask].reset_index(drop=True)
        mat_filtered = mat_avail[filter_mask.values]

        if len(df_filtered) == 0:
            logger.warning(
                "[Recommender] No CW passed filters — relaxing filters"
            )
            df_filtered  = df_avail
            mat_filtered = mat_avail

        # ── 3. Vectorize query & cosine similarity ─────────────────────────────
        query_norm = query.lower().strip()
        query_vec  = self.tfidf.transform([query_norm])
        sims       = cosine_similarity(query_vec, mat_filtered).flatten()

        # ── 4. Top-N ────────────────────────────────────────────────────────────
        actual_top_n = min(top_n, len(df_filtered))
        top_indices  = sims.argsort()[::-1][:actual_top_n]
        top_df       = df_filtered.iloc[top_indices].copy()
        top_sims     = sims[top_indices]

        # ── 5. Build result list ────────────────────────────────────────────────
        results = []
        for i, (_, row) in enumerate(top_df.iterrows()):
            results.append({
                'id':               int(row.get('id', row.name)),
                'full_name':        str(row.get('full_name', '')),
                'email':            str(row.get('email', '')),
                'specific_role':    str(row.get('specific_role', '')),
                'job_category':     str(row.get('job_category', '')),
                'skills':           str(row.get('skills', '')),
                'experience_level': str(row.get('experience_level', '')),
                'experience_years': float(row.get('experience_years', 0) or 0),
                'success_rate_job': float(row.get('success_rate_job', 0) or 0),
                'client_rating':    float(row.get('client_rating', 0) or 0),
                'rehire_rate':      float(row.get('rehire_rate', 0) or 0),
                'jobs_completed':   int(row.get('jobs_completed', 0) or 0),
                'min_budget_idr':   float(row.get('min_budget_idr', 0) or 0),
                'hourly_rate_usd':  float(row.get('hourly_rate_usd', 0) or 0),
                'profile_verified': int(row.get('profile_verified', 0) or 0),
                'similarity_score': round(float(top_sims[i]), 6),
            })

        return cluster_id, results


# ──────────────────────────────────────────────────────────────────────────────
# STANDALONE TEST
# ──────────────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    print("=== Recommender Standalone Test ===")

    rec = Recommender()

    # Test recommend()
    sample_umkm = {
        'omset':                  50_000_000,
        'laba':                   10_000_000,
        'aset':                   20_000_000,
        'biaya_karyawan':          5_000_000,
        'jumlah_pelanggan':             200,
        'kapasitas_produksi':          1000,
        'jenis_usaha':           'Perdagangan',
        'marketplace':            'Tokopedia',
        'status_legalitas':       'Terdaftar',
        'tenaga_kerja_perempuan':         3,
        'tenaga_kerja_laki_laki':         2,
        'tahun_berdiri':               2018,
    }

    cluster_id, recs = rec.recommend(sample_umkm, top_n=5)
    print(f"\nCluster predicted: {cluster_id}")
    print(f"Recommendations  : {len(recs)}")
    for r in recs:
        print(
            f"  [{r['similarity_score']:.4f}] "
            f"{r['full_name']:<25} | {r['job_category']:<25} | "
            f"{r['experience_level']}"
        )

    # Test recommend_by_skills()
    print("\n--- recommend_by_skills ---")
    skill_recs = rec.recommend_by_skills("instagram reels video editing", top_n=3)
    for r in skill_recs:
        print(
            f"  [{r['similarity_score']:.4f}] "
            f"{r['full_name']:<25} | {r['specific_role']}"
        )
