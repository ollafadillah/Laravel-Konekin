"""
src/train_tfidf.py — Konekin ML Service
TF-IDF vectorizer training + recommendation coverage/diversity evaluation.
"""

import os
import sys
import logging
import warnings

import numpy as np
import pandas as pd
import joblib
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity

warnings.filterwarnings('ignore')

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config import (
    CW_DATA_PATH, MODEL_DIR, TFIDF_VECTORIZER_PATH,
    CLUSTER_SKILL_MAP, TOP_N_DEFAULT, LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


# ──────────────────────────────────────────────────────────────────────────────
def build_vectorizer(
    df_cw: pd.DataFrame,
) -> tuple:
    """
    Fit TfidfVectorizer on the 'skills' column of the CW dataset.
    Prints top 20 terms by average TF-IDF score.
    Saves vectorizer to disk.

    Returns:
        tfidf_matrix : sparse matrix (n_cw × n_terms)
        vectorizer   : fitted TfidfVectorizer
    """
    os.makedirs(MODEL_DIR, exist_ok=True)

    logger.info("[TF-IDF] Fitting vectorizer on skills corpus")

    corpus = (
        df_cw['skills']
        .fillna('')
        .str.lower()
        .str.replace(r'\s*,\s*', ' ', regex=True)
        .str.strip()
    )

    vectorizer = TfidfVectorizer(
        analyzer='word',
        ngram_range=(1, 2),
        min_df=2,
        max_df=0.95,
        sublinear_tf=True,
        token_pattern=r'[a-z][a-z0-9+#.\-]*',
    )

    tfidf_matrix = vectorizer.fit_transform(corpus)
    logger.info(
        f"[TF-IDF] Matrix shape: {tfidf_matrix.shape[0]:,} docs × "
        f"{tfidf_matrix.shape[1]:,} terms"
    )

    # ── Top 20 terms ──────────────────────────────────────────────────────────
    feature_names = vectorizer.get_feature_names_out()
    mean_tfidf    = np.asarray(tfidf_matrix.mean(axis=0)).flatten()
    top_indices   = mean_tfidf.argsort()[::-1][:20]

    print("\n=== Top 20 TF-IDF Terms (by mean score) ===")
    print(f"{'Rank':>4} | {'Term':<35} | {'Mean TF-IDF':>12}")
    print("-" * 58)
    for rank, idx in enumerate(top_indices, 1):
        print(
            f"{rank:>4} | {feature_names[idx]:<35} | "
            f"{mean_tfidf[idx]:>12.5f}"
        )

    joblib.dump(vectorizer, TFIDF_VECTORIZER_PATH)
    logger.info(f"[TF-IDF] Vectorizer saved → {TFIDF_VECTORIZER_PATH}")

    return tfidf_matrix, vectorizer


# ──────────────────────────────────────────────────────────────────────────────
def evaluate_coverage(
    df_cw: pd.DataFrame,
    tfidf_matrix,
    cluster_skill_map: dict | None = None,
    top_n: int = TOP_N_DEFAULT,
    vectorizer: TfidfVectorizer | None = None,
) -> dict:
    """
    Evaluate recommendation quality for each cluster query:
      - Coverage   : % of unique CW recommended across all clusters
      - Diversity  : avg number of unique job_categories per recommendation set

    Returns:
        dict with 'coverage' and 'diversity' keys
    """
    if cluster_skill_map is None:
        cluster_skill_map = CLUSTER_SKILL_MAP

    if vectorizer is None:
        if not os.path.exists(TFIDF_VECTORIZER_PATH):
            raise FileNotFoundError(
                f"Vectorizer not found at {TFIDF_VECTORIZER_PATH}. "
                "Run build_vectorizer() first."
            )
        vectorizer = joblib.load(TFIDF_VECTORIZER_PATH)

    logger.info(
        f"[TF-IDF] Evaluating coverage & diversity "
        f"(top_n={top_n}, clusters={len(cluster_skill_map)})"
    )

    # Filter to available CW only for recommendation
    avail_mask = df_cw['is_available'] == 1
    df_avail   = df_cw[avail_mask].reset_index(drop=True)
    mat_avail  = tfidf_matrix[avail_mask.values]

    all_recommended_ids = set()
    diversity_scores    = []

    print("\n=== Recommendation Coverage & Diversity per Cluster ===")
    print(
        f"{'Cluster':>8} | {'Query':<45} | "
        f"{'Top-N Found':>11} | {'Unique Cats':>11}"
    )
    print("-" * 85)

    for cluster_id, query in cluster_skill_map.items():
        # Vectorize query
        query_norm = query.lower().strip()
        query_vec  = vectorizer.transform([query_norm])

        # Cosine similarity
        sims   = cosine_similarity(query_vec, mat_avail).flatten()
        top_idx = sims.argsort()[::-1][:top_n]

        recs = df_avail.iloc[top_idx]
        recommended_ids = set(recs.index.tolist())
        all_recommended_ids.update(recommended_ids)

        # Diversity: unique job_categories
        if 'job_category' in recs.columns:
            unique_cats = recs['job_category'].nunique()
        else:
            unique_cats = 0

        diversity_scores.append(unique_cats)

        short_query = query[:42] + "..." if len(query) > 45 else query
        print(
            f"{cluster_id:>8} | {short_query:<45} | "
            f"{len(top_idx):>11} | {unique_cats:>11}"
        )

        logger.info(
            f"[TF-IDF] Cluster {cluster_id}: "
            f"top-{top_n} found, unique_cats={unique_cats}"
        )

    # ── Aggregate metrics ─────────────────────────────────────────────────────
    total_cw       = len(df_avail)
    coverage       = len(all_recommended_ids) / total_cw if total_cw > 0 else 0
    avg_diversity  = float(np.mean(diversity_scores)) if diversity_scores else 0.0

    print(f"\n{'='*50}")
    print(f"  Total available CW         : {total_cw:,}")
    print(f"  Unique CW recommended      : {len(all_recommended_ids):,}")
    print(f"  Coverage                   : {coverage:.2%}")
    print(f"  Avg Diversity (unique cats): {avg_diversity:.2f}")
    print(f"{'='*50}")

    logger.info(
        f"[TF-IDF] Coverage={coverage:.2%}, "
        f"AvgDiversity={avg_diversity:.2f}"
    )

    return {
        'coverage': coverage,
        'avg_diversity': avg_diversity,
        'total_available_cw': total_cw,
        'unique_cw_recommended': len(all_recommended_ids),
    }


# ──────────────────────────────────────────────────────────────────────────────
def run(df_cw: pd.DataFrame) -> tuple:
    """
    Full TF-IDF training pipeline:
        1. build_vectorizer()
        2. evaluate_coverage()

    Returns:
        tfidf_matrix, vectorizer
    """
    logger.info("=" * 60)
    logger.info("[TF-IDF] Starting TF-IDF training pipeline")
    logger.info("=" * 60)

    tfidf_matrix, vectorizer = build_vectorizer(df_cw)
    evaluate_coverage(df_cw, tfidf_matrix, vectorizer=vectorizer)

    logger.info("[TF-IDF] Pipeline complete ✓")
    return tfidf_matrix, vectorizer


# ──────────────────────────────────────────────────────────────────────────────
# STANDALONE TEST
# ──────────────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    from src.preprocessing_cw import CWPreprocessor

    print("=== TF-IDF Standalone Test ===")
    proc = CWPreprocessor()
    df_cw, _, _ = proc.run(CW_DATA_PATH)

    tfidf_matrix, vectorizer = run(df_cw)
    print(f"\nMatrix shape : {tfidf_matrix.shape}")
    print(f"Vocab size   : {len(vectorizer.vocabulary_)}")
