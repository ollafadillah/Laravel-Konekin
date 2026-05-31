"""
config.py — Konekin ML Service
Centralized configuration: paths and constants.
"""

import os

# ──────────────────────────────────────────────
# BASE PATHS
# ──────────────────────────────────────────────
BASE_DIR  = os.path.dirname(os.path.abspath(__file__))
DATA_DIR  = os.path.join(BASE_DIR, 'data')
MODEL_DIR = os.path.join(BASE_DIR, 'models')
SRC_DIR   = os.path.join(BASE_DIR, 'src')
LOG_DIR   = os.path.join(BASE_DIR, 'logs')

# ──────────────────────────────────────────────
# DATA PATHS
# ──────────────────────────────────────────────
CW_DATA_PATH   = os.path.join(DATA_DIR, 'cw_dataset.csv')
UMKM_DATA_PATH = os.path.join(DATA_DIR, 'dataset_umkm.csv')

# ──────────────────────────────────────────────
# MODEL ARTIFACT PATHS
# ──────────────────────────────────────────────
KMEANS_MODEL_PATH     = os.path.join(MODEL_DIR, 'kmeans_umkm.pkl')
SCALER_UMKM_PATH      = os.path.join(MODEL_DIR, 'scaler_umkm.pkl')
SCALER_CW_PATH        = os.path.join(MODEL_DIR, 'scaler_cw.pkl')
TFIDF_VECTORIZER_PATH = os.path.join(MODEL_DIR, 'tfidf_vectorizer.pkl')
LABEL_ENCODERS_PATH   = os.path.join(MODEL_DIR, 'label_encoders.pkl')
CLUSTER_SUMMARY_PATH   = os.path.join(MODEL_DIR, 'cluster_summary.csv')
CLUSTER_SKILL_MAP_PATH = os.path.join(MODEL_DIR, 'cluster_skill_map.json')
ELBOW_PLOT_PATH        = os.path.join(MODEL_DIR, 'elbow_plot.png')
SILHOUETTE_PLOT_PATH   = os.path.join(MODEL_DIR, 'silhouette_plot.png')

# ──────────────────────────────────────────────
# TRAINING CONSTANTS
# ──────────────────────────────────────────────
RANDOM_STATE      = 42
TOP_N_DEFAULT     = 10
K_RANGE           = range(2, 11)
KMEANS_N_INIT     = 10
KMEANS_N_CLUSTERS = 5

# ──────────────────────────────────────────────
# UMKM PREPROCESSING COLUMNS
# ──────────────────────────────────────────────
UMKM_NUMERIC_COLS = [
    'omset', 'laba', 'aset', 'biaya_karyawan',
    'jumlah_pelanggan', 'kapasitas_produksi',
    'tenaga_kerja_perempuan', 'tenaga_kerja_laki_laki',
    'tahun_berdiri',
]

UMKM_OUTLIER_COLS = ['omset', 'laba', 'aset']

UMKM_CATEGORICAL_COLS = ['jenis_usaha', 'marketplace', 'status_legalitas']

UMKM_KMEANS_FEATURES = [
    'omset', 'laba', 'aset', 'biaya_karyawan',
    'jumlah_pelanggan', 'kapasitas_produksi',
    'total_tenaga_kerja', 'rasio_laba_omset',
    'umur_usaha', 'is_profitable',
    'status_legalitas_enc', 'jenis_usaha_enc',
]

# ──────────────────────────────────────────────
# CW PREPROCESSING COLUMNS
# ──────────────────────────────────────────────
CW_ORDINAL_MAP = {'Beginner': 0, 'Intermediate': 1, 'Expert': 2}

CW_LABEL_ENCODE_COLS = [
    'job_category', 'specific_role',
    'location', 'platform_origin', 'project_type',
]

CW_MINMAX_COLS = [
    'success_rate_job', 'client_rating', 'rehire_rate',
    'experience_years', 'jobs_completed',
    'min_budget_idr', 'hourly_rate_usd', 'earnings_usd',
]

# ──────────────────────────────────────────────
# CLUSTER SKILL QUERY MAP
# Generated during KMeans training and saved to CLUSTER_SKILL_MAP_PATH.
# ──────────────────────────────────────────────
# Human-readable cluster labels for API responses
CLUSTER_LABEL_MAP = {
    0: "UMKM Sosial & Kreatif",
    1: "UMKM Digital & Teknologi",
    2: "UMKM Konten & Media",
    3: "UMKM Branding & Visual",
    4: "UMKM Marketing & Pemasaran",
}

# ──────────────────────────────────────────────
# FLASK CONFIG
# ──────────────────────────────────────────────
FLASK_HOST  = '0.0.0.0'
FLASK_PORT  = 5000
FLASK_DEBUG = False

# ──────────────────────────────────────────────
# LOGGING FORMAT
# ──────────────────────────────────────────────
LOG_FORMAT = '%(asctime)s - %(name)s - %(levelname)s - %(message)s'
LOG_LEVEL  = 'INFO'


def ensure_dirs():
    """Create required directories if they don't exist."""
    for d in [DATA_DIR, MODEL_DIR, LOG_DIR]:
        os.makedirs(d, exist_ok=True)


if __name__ == '__main__':
    ensure_dirs()
    print("=== Konekin ML Service — Config ===")
    print(f"BASE_DIR  : {BASE_DIR}")
    print(f"DATA_DIR  : {DATA_DIR}")
    print(f"MODEL_DIR : {MODEL_DIR}")
    print(f"CW_DATA   : {CW_DATA_PATH}")
    print(f"UMKM_DATA : {UMKM_DATA_PATH}")
    print(f"CLUSTER_SKILL_MAP_PATH: {CLUSTER_SKILL_MAP_PATH}")
