"""
src/preprocessing_cw.py — Konekin ML Service
Full preprocessing pipeline for Creative Workers dataset.
"""

import os
import sys
import logging
import warnings

import numpy as np
import pandas as pd
import joblib
from sklearn.preprocessing import LabelEncoder, MinMaxScaler, OrdinalEncoder
from sklearn.feature_extraction.text import TfidfVectorizer

warnings.filterwarnings('ignore')

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config import (
    CW_DATA_PATH, MODEL_DIR,
    SCALER_CW_PATH, TFIDF_VECTORIZER_PATH, LABEL_ENCODERS_PATH,
    CW_ORDINAL_MAP, CW_LABEL_ENCODE_COLS, CW_MINMAX_COLS,
    RANDOM_STATE, LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


class CWPreprocessor:
    """
    End-to-end preprocessing pipeline for Creative Workers dataset.

    Steps:
        1. load()
        2. encode_experience()
        3. encode_categoricals()
        4. scale_numerics()
        5. build_tfidf()
        6. filter_available()  ← optional, used in recommend flow

    Use run(path) to execute all steps at once.
    """

    def __init__(self):
        self.label_encoders: dict = {}
        self.ordinal_encoder: OrdinalEncoder | None = None
        self.scaler: MinMaxScaler | None = None
        self.vectorizer: TfidfVectorizer | None = None

    # ──────────────────────────────────────────────────────────────────────────
    # 1. LOAD
    # ──────────────────────────────────────────────────────────────────────────
    def load(self, path: str) -> pd.DataFrame:
        logger.info(f"[CW] Loading dataset from: {path}")
        df = pd.read_csv(path, low_memory=False)
        logger.info(f"[CW] Loaded {len(df):,} rows × {df.shape[1]} cols")
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 2. ENCODE EXPERIENCE (Ordinal)
    # ──────────────────────────────────────────────────────────────────────────
    def encode_experience(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info("[CW] Ordinal encoding 'experience_level'")
        df = df.copy()

        order = list(CW_ORDINAL_MAP.keys())   # ['Beginner', 'Intermediate', 'Expert']
        mapping = CW_ORDINAL_MAP              # {'Beginner':0, 'Intermediate':1, 'Expert':2}

        # Map directly for interpretability; keep as float to allow NaN handling
        df['experience_level_enc'] = (
            df['experience_level']
            .map(mapping)
            .fillna(0)
            .astype(int)
        )
        logger.info(
            f"[CW] 'experience_level' encoded: {mapping}, "
            f"distribution:\n{df['experience_level'].value_counts().to_dict()}"
        )
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 3. ENCODE CATEGORICALS (LabelEncoder)
    # ──────────────────────────────────────────────────────────────────────────
    def encode_categoricals(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info(f"[CW] LabelEncoding columns: {CW_LABEL_ENCODE_COLS}")
        df = df.copy()
        os.makedirs(MODEL_DIR, exist_ok=True)

        for col in CW_LABEL_ENCODE_COLS:
            if col not in df.columns:
                logger.warning(f"[CW] Column '{col}' not found, skipping")
                continue
            le = LabelEncoder()
            df[f'{col}_enc'] = le.fit_transform(df[col].astype(str))
            self.label_encoders[f'cw_{col}'] = le
            logger.info(
                f"[CW] '{col}' encoded → {len(le.classes_)} unique classes"
            )

        # Persist CW label encoders (merge with any existing ones)
        existing = {}
        if os.path.exists(LABEL_ENCODERS_PATH):
            try:
                existing = joblib.load(LABEL_ENCODERS_PATH)
            except Exception:
                pass
        existing.update(self.label_encoders)
        joblib.dump(existing, LABEL_ENCODERS_PATH)
        logger.info(f"[CW] Label encoders saved → {LABEL_ENCODERS_PATH}")

        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 4. SCALE NUMERICS (MinMaxScaler)
    # ──────────────────────────────────────────────────────────────────────────
    def scale_numerics(self, df: pd.DataFrame, fit: bool = True) -> pd.DataFrame:
        logger.info(f"[CW] MinMaxScaling numeric cols (fit={fit})")
        df = df.copy()
        os.makedirs(MODEL_DIR, exist_ok=True)

        cols_present = [c for c in CW_MINMAX_COLS if c in df.columns]
        X = df[cols_present].fillna(0).values

        if fit:
            self.scaler = MinMaxScaler()
            X_scaled = self.scaler.fit_transform(X)
            joblib.dump(self.scaler, SCALER_CW_PATH)
            logger.info(f"[CW] MinMaxScaler fitted & saved → {SCALER_CW_PATH}")
        else:
            if self.scaler is None:
                self.scaler = joblib.load(SCALER_CW_PATH)
            X_scaled = self.scaler.transform(X)

        # Write back scaled columns with '_scaled' suffix
        for i, col in enumerate(cols_present):
            df[f'{col}_scaled'] = X_scaled[:, i]

        logger.info(f"[CW] Scaled {len(cols_present)} numeric columns")
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 5. BUILD TF-IDF
    # ──────────────────────────────────────────────────────────────────────────
    def build_tfidf(
        self, df: pd.DataFrame, fit: bool = True
    ) -> tuple:
        logger.info(f"[CW] Building TF-IDF on 'skills' column (fit={fit})")
        os.makedirs(MODEL_DIR, exist_ok=True)

        # Normalize skills text: lowercase, strip extra spaces
        skills_corpus = (
            df['skills']
            .fillna('')
            .str.lower()
            .str.replace(r'\s*,\s*', ' ', regex=True)
            .str.strip()
        )

        if fit:
            self.vectorizer = TfidfVectorizer(
                analyzer='word',
                ngram_range=(1, 2),
                min_df=2,
                max_df=0.95,
                sublinear_tf=True,
                token_pattern=r'[a-z][a-z0-9+#.\-]*',
            )
            tfidf_matrix = self.vectorizer.fit_transform(skills_corpus)
            joblib.dump(self.vectorizer, TFIDF_VECTORIZER_PATH)
            logger.info(
                f"[CW] TF-IDF fitted: {tfidf_matrix.shape[0]} docs × "
                f"{tfidf_matrix.shape[1]} terms"
            )
            logger.info(
                f"[CW] TF-IDF vectorizer saved → {TFIDF_VECTORIZER_PATH}"
            )
        else:
            if self.vectorizer is None:
                self.vectorizer = joblib.load(TFIDF_VECTORIZER_PATH)
            tfidf_matrix = self.vectorizer.transform(skills_corpus)

        return tfidf_matrix, self.vectorizer

    # ──────────────────────────────────────────────────────────────────────────
    # 6. FILTER AVAILABLE
    # ──────────────────────────────────────────────────────────────────────────
    def filter_available(self, df: pd.DataFrame) -> pd.DataFrame:
        n_before = len(df)
        df_avail = df[df['is_available'] == 1].copy()
        n_after = len(df_avail)
        logger.info(
            f"[CW] Available filter: {n_before:,} → {n_after:,} "
            f"({n_before - n_after:,} removed)"
        )
        return df_avail

    # ──────────────────────────────────────────────────────────────────────────
    # MASTER RUN
    # ──────────────────────────────────────────────────────────────────────────
    def run(self, path: str) -> tuple[pd.DataFrame, object, object]:
        """
        Execute full preprocessing pipeline.

        Returns:
            df_processed  : processed DataFrame (all CW, including unavailable)
            tfidf_matrix  : sparse TF-IDF matrix aligned with df_processed
            vectorizer    : fitted TfidfVectorizer
        """
        logger.info("=" * 60)
        logger.info("[CW] Starting full preprocessing pipeline")
        logger.info("=" * 60)

        df = self.load(path)
        df = self.encode_experience(df)
        df = self.encode_categoricals(df)
        df = self.scale_numerics(df, fit=True)
        tfidf_matrix, vectorizer = self.build_tfidf(df, fit=True)

        logger.info("[CW] Pipeline complete ✓")
        logger.info(f"[CW] Final shape: {df.shape}")
        return df, tfidf_matrix, vectorizer


# ──────────────────────────────────────────────────────────────────────────────
# STANDALONE TEST
# ──────────────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    proc = CWPreprocessor()
    df_cw, tfidf_mat, vec = proc.run(CW_DATA_PATH)

    print("\n=== CW Preprocessor — Standalone Test ===")
    print(f"Processed shape  : {df_cw.shape}")
    print(f"TF-IDF shape     : {tfidf_mat.shape}")
    print(f"\nExperience dist  :\n{df_cw['experience_level'].value_counts()}")
    print(f"\nAvailable CW     : {(df_cw['is_available']==1).sum():,}")

    # Show top 20 TF-IDF terms
    feature_names = vec.get_feature_names_out()
    mean_tfidf = np.asarray(tfidf_mat.mean(axis=0)).flatten()
    top_idx = mean_tfidf.argsort()[::-1][:20]
    print("\nTop 20 TF-IDF terms:")
    for i, idx in enumerate(top_idx, 1):
        print(f"  {i:2d}. {feature_names[idx]:<30s}  score={mean_tfidf[idx]:.5f}")
