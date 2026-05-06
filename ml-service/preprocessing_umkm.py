"""
src/preprocessing_umkm.py — Konekin ML Service
Full preprocessing pipeline for UMKM dataset.
"""

import os
import sys
import logging
import warnings

import numpy as np
import pandas as pd
import joblib
from sklearn.preprocessing import LabelEncoder, StandardScaler

warnings.filterwarnings('ignore')

# ── allow running standalone ──────────────────────────────────────────────────
sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config import (
    UMKM_DATA_PATH, MODEL_DIR, LABEL_ENCODERS_PATH,
    SCALER_UMKM_PATH, UMKM_NUMERIC_COLS, UMKM_OUTLIER_COLS,
    UMKM_KMEANS_FEATURES, LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


class UMKMPreprocessor:
    """
    End-to-end preprocessing pipeline for UMKM dataset.

    Steps:
        1. load()
        2. convert_types()
        3. engineer_features()
        4. handle_missing()
        5. handle_outlier()
        6. encode()
        7. scale()

    Use run(path) to execute all steps at once.
    """

    def __init__(self):
        self.label_encoders: dict = {}
        self.scaler: StandardScaler | None = None
        self._fitted = False

    # ──────────────────────────────────────────────────────────────────────────
    # 1. LOAD
    # ──────────────────────────────────────────────────────────────────────────
    def load(self, path: str) -> pd.DataFrame:
        logger.info(f"[UMKM] Loading dataset from: {path}")
        df = pd.read_csv(path, low_memory=False)
        logger.info(f"[UMKM] Loaded {len(df):,} rows × {df.shape[1]} cols")
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 2. CONVERT TYPES
    # ──────────────────────────────────────────────────────────────────────────
    def convert_types(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info("[UMKM] Converting numeric columns from string → float")
        df = df.copy()
        for col in UMKM_NUMERIC_COLS:
            if col in df.columns:
                before_null = df[col].isna().sum()
                df[col] = pd.to_numeric(df[col], errors='coerce')
                after_null = df[col].isna().sum()
                new_nulls = after_null - before_null
                if new_nulls > 0:
                    logger.warning(
                        f"[UMKM] '{col}': {new_nulls} new NaN after coerce"
                    )
        logger.info("[UMKM] Type conversion complete")
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 3. FEATURE ENGINEERING
    # ──────────────────────────────────────────────────────────────────────────
    def engineer_features(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info("[UMKM] Engineering new features")
        df = df.copy()

        # total_tenaga_kerja
        df['total_tenaga_kerja'] = (
            df['tenaga_kerja_perempuan'].fillna(0)
            + df['tenaga_kerja_laki_laki'].fillna(0)
        )

        # rasio_laba_omset — guard against division by zero
        df['rasio_laba_omset'] = df['laba'] / df['omset'].replace(0, np.nan)
        df['rasio_laba_omset'] = (
            df['rasio_laba_omset']
            .replace([np.inf, -np.inf], 0)
            .fillna(0)
        )

        # umur_usaha
        df['umur_usaha'] = 2025 - df['tahun_berdiri']
        df['umur_usaha'] = df['umur_usaha'].clip(lower=0)

        # is_profitable
        df['is_profitable'] = (df['laba'] > 0).astype(int)

        logger.info(
            "[UMKM] New features: total_tenaga_kerja, rasio_laba_omset, "
            "umur_usaha, is_profitable"
        )
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 4. HANDLE MISSING
    # ──────────────────────────────────────────────────────────────────────────
    def handle_missing(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info("[UMKM] Handling missing values")
        df = df.copy()

        numeric_cols = df.select_dtypes(include=[np.number]).columns.tolist()
        cat_cols = df.select_dtypes(include=['object']).columns.tolist()

        # Numeric → median
        for col in numeric_cols:
            n_null = df[col].isna().sum()
            if n_null > 0:
                median_val = df[col].median()
                df[col].fillna(median_val, inplace=True)
                logger.info(
                    f"[UMKM] '{col}': filled {n_null} NaN with median={median_val:.2f}"
                )

        # Categorical → mode, replace 'unknown'
        for col in cat_cols:
            # replace 'unknown' string first
            df[col] = df[col].replace('unknown', np.nan)
            n_null = df[col].isna().sum()
            if n_null > 0:
                mode_val = df[col].mode()
                if len(mode_val) > 0:
                    mode_val = mode_val[0]
                    df[col].fillna(mode_val, inplace=True)
                    logger.info(
                        f"[UMKM] '{col}': filled {n_null} NaN/unknown "
                        f"with mode='{mode_val}'"
                    )

        logger.info("[UMKM] Missing value imputation complete")
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 5. HANDLE OUTLIER
    # ──────────────────────────────────────────────────────────────────────────
    def handle_outlier(
        self, df: pd.DataFrame, cols: list | None = None
    ) -> pd.DataFrame:
        if cols is None:
            cols = UMKM_OUTLIER_COLS
        logger.info(f"[UMKM] IQR clipping on: {cols}")
        df = df.copy()
        for col in cols:
            if col not in df.columns:
                continue
            Q1 = df[col].quantile(0.25)
            Q3 = df[col].quantile(0.75)
            IQR = Q3 - Q1
            lower = Q1 - 1.5 * IQR
            upper = Q3 + 1.5 * IQR
            n_clipped = ((df[col] < lower) | (df[col] > upper)).sum()
            df[col] = df[col].clip(lower=lower, upper=upper)
            logger.info(
                f"[UMKM] '{col}': clipped {n_clipped} outliers "
                f"[{lower:.0f}, {upper:.0f}]"
            )
        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 6. ENCODE
    # ──────────────────────────────────────────────────────────────────────────
    def encode(self, df: pd.DataFrame) -> pd.DataFrame:
        logger.info("[UMKM] Encoding categorical features")
        df = df.copy()
        os.makedirs(MODEL_DIR, exist_ok=True)

        # status_legalitas → binary
        df['status_legalitas_enc'] = (
            df['status_legalitas'].str.strip().str.lower() == 'terdaftar'
        ).astype(int)
        logger.info("[UMKM] 'status_legalitas' → binary encoded")

        # jenis_usaha → LabelEncoder
        le_jenis = LabelEncoder()
        df['jenis_usaha_enc'] = le_jenis.fit_transform(
            df['jenis_usaha'].astype(str)
        )
        self.label_encoders['jenis_usaha'] = le_jenis
        logger.info(
            f"[UMKM] 'jenis_usaha' LabelEncoded, "
            f"classes: {list(le_jenis.classes_)}"
        )

        # marketplace → one-hot (get_dummies)
        mkt_dummies = pd.get_dummies(
            df['marketplace'].astype(str), prefix='mkt'
        )
        df = pd.concat([df, mkt_dummies], axis=1)
        logger.info(
            f"[UMKM] 'marketplace' one-hot encoded: "
            f"{list(mkt_dummies.columns)}"
        )

        # persist label encoders
        joblib.dump(self.label_encoders, LABEL_ENCODERS_PATH)
        logger.info(f"[UMKM] Label encoders saved → {LABEL_ENCODERS_PATH}")

        return df

    # ──────────────────────────────────────────────────────────────────────────
    # 7. SCALE
    # ──────────────────────────────────────────────────────────────────────────
    def scale(self, df: pd.DataFrame, fit: bool = True) -> np.ndarray:
        logger.info(f"[UMKM] Scaling features (fit={fit})")
        os.makedirs(MODEL_DIR, exist_ok=True)

        # Build feature list: defined cols + any mkt_ dummies present
        mkt_cols = [c for c in df.columns if c.startswith('mkt_')]
        feature_cols = [
            c for c in UMKM_KMEANS_FEATURES + mkt_cols
            if c in df.columns
        ]
        # deduplicate while preserving order
        seen = set()
        feature_cols = [
            c for c in feature_cols
            if not (c in seen or seen.add(c))
        ]

        X = df[feature_cols].fillna(0).values

        if fit:
            self.scaler = StandardScaler()
            X_scaled = self.scaler.fit_transform(X)
            joblib.dump(self.scaler, SCALER_UMKM_PATH)
            logger.info(f"[UMKM] Scaler fitted & saved → {SCALER_UMKM_PATH}")
        else:
            if self.scaler is None:
                self.scaler = joblib.load(SCALER_UMKM_PATH)
            X_scaled = self.scaler.transform(X)

        self._fitted = True
        logger.info(
            f"[UMKM] Scaled matrix shape: {X_scaled.shape}, "
            f"features: {feature_cols}"
        )
        return X_scaled

    # ──────────────────────────────────────────────────────────────────────────
    # MASTER RUN
    # ──────────────────────────────────────────────────────────────────────────
    def run(self, path: str) -> tuple[pd.DataFrame, np.ndarray]:
        """
        Execute full preprocessing pipeline.

        Returns:
            df_processed : fully processed DataFrame (with engineered & encoded cols)
            X_scaled     : numpy array ready for KMeans
        """
        logger.info("=" * 60)
        logger.info("[UMKM] Starting full preprocessing pipeline")
        logger.info("=" * 60)

        df = self.load(path)
        df = self.convert_types(df)
        df = self.engineer_features(df)
        df = self.handle_missing(df)
        df = self.handle_outlier(df)
        df = self.encode(df)
        X_scaled = self.scale(df, fit=True)

        logger.info("[UMKM] Pipeline complete ✓")
        logger.info(f"[UMKM] Final shape: {df.shape}")
        return df, X_scaled


# ──────────────────────────────────────────────────────────────────────────────
# STANDALONE TEST
# ──────────────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    preprocessor = UMKMPreprocessor()
    df_processed, X_scaled = preprocessor.run(UMKM_DATA_PATH)

    print("\n=== UMKM Preprocessor — Standalone Test ===")
    print(f"Processed shape  : {df_processed.shape}")
    print(f"X_scaled shape   : {X_scaled.shape}")
    print(f"\nSample columns:\n{list(df_processed.columns[:15])}")
    print(f"\nFirst 3 rows (numeric):\n{df_processed.select_dtypes(include=[np.number]).head(3)}")
