"""
src/train_kmeans.py — Konekin ML Service
KMeans clustering for UMKM dataset with optimal-K selection.
"""

import os
import sys
import logging
import warnings

import numpy as np
import pandas as pd
import joblib
try:
    import matplotlib
    matplotlib.use('Agg')
    import matplotlib.pyplot as plt
except ModuleNotFoundError:
    matplotlib = None
    plt = None

from sklearn.cluster import KMeans
from sklearn.metrics import silhouette_score, davies_bouldin_score

warnings.filterwarnings('ignore')

sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
from config import (
    MODEL_DIR, KMEANS_MODEL_PATH, CLUSTER_SUMMARY_PATH,
    ELBOW_PLOT_PATH, SILHOUETTE_PLOT_PATH,
    K_RANGE, RANDOM_STATE, KMEANS_N_INIT, LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


# ──────────────────────────────────────────────────────────────────────────────
def find_optimal_k(X_scaled: np.ndarray, k_range=None) -> int:
    """
    Evaluate KMeans for a range of K values.
    Saves elbow and silhouette plots.
    Returns the K with the highest silhouette score.
    """
    if k_range is None:
        k_range = K_RANGE

    os.makedirs(MODEL_DIR, exist_ok=True)

    logger.info(
        f"[KMeans] Searching optimal K in range {list(k_range)}"
    )

    inertias     = []
    sil_scores   = []
    k_values     = list(k_range)

    print(f"\n{'K':>4} | {'Inertia':>15} | {'Silhouette':>12}")
    print("-" * 38)

    for k in k_values:
        km = KMeans(
            n_clusters=k,
            random_state=RANDOM_STATE,
            n_init=KMEANS_N_INIT,
        )
        labels = km.fit_predict(X_scaled)
        inertia = km.inertia_
        sil = silhouette_score(X_scaled, labels, sample_size=min(3000, len(X_scaled)))

        inertias.append(inertia)
        sil_scores.append(sil)

        print(f"{k:>4} | {inertia:>15.2f} | {sil:>12.4f}")
        logger.info(
            f"[KMeans] K={k}: inertia={inertia:.2f}, silhouette={sil:.4f}"
        )

    if plt is not None:
        # ── Elbow Plot ────────────────────────────────────────────────────────────
        fig, ax = plt.subplots(figsize=(8, 4))
        ax.plot(k_values, inertias, 'bo-', linewidth=2, markersize=7)
        ax.set_xlabel('Number of Clusters (K)', fontsize=12)
        ax.set_ylabel('Inertia', fontsize=12)
        ax.set_title('KMeans Elbow Plot — UMKM Clusters', fontsize=14)
        ax.grid(True, alpha=0.3)
        plt.tight_layout()
        plt.savefig(ELBOW_PLOT_PATH, dpi=150)
        plt.close()
        logger.info(f"[KMeans] Elbow plot saved → {ELBOW_PLOT_PATH}")

        # ── Silhouette Plot ───────────────────────────────────────────────────────
        fig, ax = plt.subplots(figsize=(8, 4))
        ax.plot(k_values, sil_scores, 'rs-', linewidth=2, markersize=7)
        ax.set_xlabel('Number of Clusters (K)', fontsize=12)
        ax.set_ylabel('Silhouette Score', fontsize=12)
        ax.set_title('KMeans Silhouette Score — UMKM Clusters', fontsize=14)
        ax.grid(True, alpha=0.3)
        plt.tight_layout()
        plt.savefig(SILHOUETTE_PLOT_PATH, dpi=150)
        plt.close()
        logger.info(f"[KMeans] Silhouette plot saved → {SILHOUETTE_PLOT_PATH}")
    else:
        logger.warning("[KMeans] matplotlib not installed, skipping elbow/silhouette plots")

    # ── Select best K ─────────────────────────────────────────────────────────
    best_k = k_values[int(np.argmax(sil_scores))]
    best_sil = max(sil_scores)
    logger.info(
        f"[KMeans] Best K = {best_k} (silhouette={best_sil:.4f})"
    )
    print(f"\n>>> Best K = {best_k}  (silhouette = {best_sil:.4f})")
    return best_k


# ──────────────────────────────────────────────────────────────────────────────
def train(X_scaled: np.ndarray, n_clusters: int) -> KMeans:
    """
    Fit final KMeans model with chosen n_clusters.
    Prints silhouette score and Davies-Bouldin index.
    Saves model to disk.
    """
    os.makedirs(MODEL_DIR, exist_ok=True)

    logger.info(
        f"[KMeans] Training final model: n_clusters={n_clusters}"
    )

    km = KMeans(
        n_clusters=n_clusters,
        random_state=RANDOM_STATE,
        n_init=KMEANS_N_INIT,
        max_iter=500,
    )
    labels = km.fit_predict(X_scaled)

    sil   = silhouette_score(X_scaled, labels, sample_size=min(3000, len(X_scaled)))
    db    = davies_bouldin_score(X_scaled, labels)

    logger.info(
        f"[KMeans] Final model — "
        f"Silhouette={sil:.4f}, Davies-Bouldin={db:.4f}"
    )
    print(f"\n=== Final KMeans (K={n_clusters}) ===")
    print(f"  Silhouette Score  : {sil:.4f}")
    print(f"  Davies-Bouldin    : {db:.4f}  (lower = better)")

    joblib.dump(km, KMEANS_MODEL_PATH)
    logger.info(f"[KMeans] Model saved → {KMEANS_MODEL_PATH}")

    return km


# ──────────────────────────────────────────────────────────────────────────────
def analyze_clusters(
    df_umkm: pd.DataFrame, labels: np.ndarray
) -> pd.DataFrame:
    """
    Attach cluster labels to df, print cluster summaries,
    save CSV summary.
    """
    os.makedirs(MODEL_DIR, exist_ok=True)

    df = df_umkm.copy()
    df['cluster'] = labels

    print("\n=== Cluster Distribution ===")
    dist = df['cluster'].value_counts().sort_index()
    for c, n in dist.items():
        print(f"  Cluster {c}: {n:,} UMKM ({n/len(df)*100:.1f}%)")

    # ── Numeric mean per cluster ──────────────────────────────────────────────
    numeric_summary_cols = [
        'omset', 'laba', 'aset', 'total_tenaga_kerja',
        'umur_usaha', 'rasio_laba_omset',
    ]
    avail_cols = [c for c in numeric_summary_cols if c in df.columns]

    if avail_cols:
        print("\n=== Numeric Means per Cluster ===")
        cluster_means = df.groupby('cluster')[avail_cols].mean()
        # Format large numbers
        pd.set_option('display.float_format', '{:,.0f}'.format)
        print(cluster_means.to_string())
        pd.reset_option('display.float_format')
        logger.info(f"[KMeans] Cluster numeric means computed")

    # ── Jenis usaha distribution per cluster ─────────────────────────────────
    if 'jenis_usaha' in df.columns:
        print("\n=== Jenis Usaha Distribution per Cluster ===")
        jenis_dist = (
            df.groupby(['cluster', 'jenis_usaha'])
            .size()
            .unstack(fill_value=0)
        )
        print(jenis_dist.to_string())

    # ── Save summary ──────────────────────────────────────────────────────────
    summary_parts = []
    if avail_cols:
        summary_parts.append(df.groupby('cluster')[avail_cols].mean())

    if 'jenis_usaha' in df.columns:
        top_jenis = (
            df.groupby('cluster')['jenis_usaha']
            .agg(lambda x: x.value_counts().index[0])
            .rename('top_jenis_usaha')
        )
        if summary_parts:
            summary = summary_parts[0].join(top_jenis)
        else:
            summary = pd.DataFrame(top_jenis)
    else:
        summary = summary_parts[0] if summary_parts else pd.DataFrame()

    summary['cluster_size'] = dist
    summary.to_csv(CLUSTER_SUMMARY_PATH)
    logger.info(f"[KMeans] Cluster summary saved → {CLUSTER_SUMMARY_PATH}")

    return df


# ──────────────────────────────────────────────────────────────────────────────
def run(
    X_scaled: np.ndarray, df_umkm: pd.DataFrame
) -> tuple[KMeans, pd.DataFrame]:
    """
    Full training pipeline:
        1. find_optimal_k()
        2. train()
        3. analyze_clusters()

    Returns:
        model          : fitted KMeans
        df_with_cluster: DataFrame with 'cluster' column
    """
    logger.info("=" * 60)
    logger.info("[KMeans] Starting full KMeans training pipeline")
    logger.info("=" * 60)

    best_k = find_optimal_k(X_scaled)
    model  = train(X_scaled, n_clusters=best_k)
    labels = model.labels_
    df_clustered = analyze_clusters(df_umkm, labels)

    logger.info("[KMeans] Training pipeline complete ✓")
    return model, df_clustered


# ──────────────────────────────────────────────────────────────────────────────
# STANDALONE TEST
# ──────────────────────────────────────────────────────────────────────────────
if __name__ == '__main__':
    import sys
    sys.path.insert(0, os.path.dirname(os.path.dirname(os.path.abspath(__file__))))

    from src.preprocessing_umkm import UMKMPreprocessor
    from config import UMKM_DATA_PATH

    print("=== KMeans Standalone Test ===")
    prep = UMKMPreprocessor()
    df_proc, X_scaled = prep.run(UMKM_DATA_PATH)

    model, df_clustered = run(X_scaled, df_proc)
    print(f"\nModel type    : {type(model)}")
    print(f"Labels unique : {sorted(np.unique(model.labels_))}")
    print(f"DF with cluster shape: {df_clustered.shape}")
