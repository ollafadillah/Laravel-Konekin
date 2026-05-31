"""
KMeans clustering for the UMKM dataset.

find_optimal_k() is kept for analysis plots only. The production model is
trained with config.KMEANS_N_CLUSTERS so cluster IDs stay aligned with the
downstream recommendation map.
"""

from __future__ import annotations

import logging
import os
import sys
import warnings
from pathlib import Path

import joblib
import numpy as np
import pandas as pd
from sklearn.cluster import KMeans
from sklearn.metrics import davies_bouldin_score, silhouette_score

try:
    import matplotlib

    matplotlib.use("Agg")
    import matplotlib.pyplot as plt
except ModuleNotFoundError:
    plt = None

ROOT = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(ROOT))

from config import (  # noqa: E402
    CLUSTER_SUMMARY_PATH,
    ELBOW_PLOT_PATH,
    K_RANGE,
    KMEANS_MODEL_PATH,
    KMEANS_N_CLUSTERS,
    KMEANS_N_INIT,
    LOG_FORMAT,
    MODEL_DIR,
    RANDOM_STATE,
    SILHOUETTE_PLOT_PATH,
)
from src.cluster_skill_mapping import generate_cluster_skill_map  # noqa: E402

warnings.filterwarnings("ignore")

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)


def _sample_size(X_scaled: np.ndarray) -> int:
    return min(3000, len(X_scaled))


def find_optimal_k(X_scaled: np.ndarray, k_range=None) -> int:
    """
    Evaluate KMeans for a range of K values and save analysis plots.

    Returns the best K by silhouette score for reporting only. run() still
    trains the final model with KMEANS_N_CLUSTERS.
    """
    if k_range is None:
        k_range = K_RANGE

    os.makedirs(MODEL_DIR, exist_ok=True)
    k_values = list(k_range)
    inertias = []
    sil_scores = []

    logger.info(f"[KMeans] Analyzing K range: {k_values}")
    print(f"\n{'K':>4} | {'Inertia':>15} | {'Silhouette':>12}")
    print("-" * 38)

    for k in k_values:
        km = KMeans(
            n_clusters=k,
            random_state=RANDOM_STATE,
            n_init=KMEANS_N_INIT,
        )
        labels = km.fit_predict(X_scaled)
        inertia = float(km.inertia_)
        silhouette = float(
            silhouette_score(
                X_scaled,
                labels,
                sample_size=_sample_size(X_scaled),
                random_state=RANDOM_STATE,
            )
        )

        inertias.append(inertia)
        sil_scores.append(silhouette)

        print(f"{k:>4} | {inertia:>15.2f} | {silhouette:>12.4f}")
        logger.info(
            f"[KMeans] K={k}: inertia={inertia:.2f}, silhouette={silhouette:.4f}"
        )

    if plt is not None:
        fig, ax = plt.subplots(figsize=(8, 4))
        ax.plot(k_values, inertias, "bo-", linewidth=2, markersize=7)
        ax.set_xlabel("Number of Clusters (K)", fontsize=12)
        ax.set_ylabel("Inertia", fontsize=12)
        ax.set_title("KMeans Elbow Plot - UMKM Clusters", fontsize=14)
        ax.grid(True, alpha=0.3)
        plt.tight_layout()
        plt.savefig(ELBOW_PLOT_PATH, dpi=150)
        plt.close(fig)
        logger.info(f"[KMeans] Elbow plot saved -> {ELBOW_PLOT_PATH}")

        fig, ax = plt.subplots(figsize=(8, 4))
        ax.plot(k_values, sil_scores, "rs-", linewidth=2, markersize=7)
        ax.set_xlabel("Number of Clusters (K)", fontsize=12)
        ax.set_ylabel("Silhouette Score", fontsize=12)
        ax.set_title("KMeans Silhouette Score - UMKM Clusters", fontsize=14)
        ax.grid(True, alpha=0.3)
        plt.tight_layout()
        plt.savefig(SILHOUETTE_PLOT_PATH, dpi=150)
        plt.close(fig)
        logger.info(f"[KMeans] Silhouette plot saved -> {SILHOUETTE_PLOT_PATH}")
    else:
        logger.warning("[KMeans] matplotlib not installed, skipping plots")

    best_k = k_values[int(np.argmax(sil_scores))]
    best_silhouette = max(sil_scores)
    logger.info(
        f"[KMeans] Analysis best K={best_k} (silhouette={best_silhouette:.4f})"
    )
    print(f"\n>>> Analysis best K = {best_k} (silhouette = {best_silhouette:.4f})")
    print(f">>> Final training K is forced to {KMEANS_N_CLUSTERS}")
    return best_k


def train(X_scaled: np.ndarray, n_clusters: int = KMEANS_N_CLUSTERS) -> KMeans:
    """
    Fit the final KMeans model and save it to disk.
    """
    os.makedirs(MODEL_DIR, exist_ok=True)
    logger.info(f"[KMeans] Training final model with n_clusters={n_clusters}")

    km = KMeans(
        n_clusters=n_clusters,
        random_state=RANDOM_STATE,
        n_init=KMEANS_N_INIT,
        max_iter=500,
    )
    labels = km.fit_predict(X_scaled)

    silhouette = float(
        silhouette_score(
            X_scaled,
            labels,
            sample_size=_sample_size(X_scaled),
            random_state=RANDOM_STATE,
        )
    )
    davies_bouldin = float(davies_bouldin_score(X_scaled, labels))

    print(f"\n=== Final KMeans (K={n_clusters}) ===")
    print(f"  Silhouette Score  : {silhouette:.4f}")
    print(f"  Davies-Bouldin    : {davies_bouldin:.4f}  (lower = better)")

    logger.info(
        "[KMeans] Final metrics: "
        f"silhouette={silhouette:.4f}, davies_bouldin={davies_bouldin:.4f}"
    )

    joblib.dump(km, KMEANS_MODEL_PATH)
    logger.info(f"[KMeans] Model saved -> {KMEANS_MODEL_PATH}")
    return km


def analyze_clusters(df_umkm: pd.DataFrame, labels: np.ndarray) -> pd.DataFrame:
    """
    Attach cluster labels, print summary tables, and save cluster_summary.csv.
    """
    os.makedirs(MODEL_DIR, exist_ok=True)

    df = df_umkm.copy()
    df["cluster"] = labels

    print("\n=== Cluster Distribution ===")
    dist = df["cluster"].value_counts().sort_index()
    for cluster_id, total in dist.items():
        print(f"  Cluster {cluster_id}: {total:,} UMKM ({total / len(df) * 100:.1f}%)")

    numeric_summary_cols = [
        "omset",
        "laba",
        "aset",
        "total_tenaga_kerja",
        "umur_usaha",
        "rasio_laba_omset",
    ]
    available_numeric_cols = [
        col for col in numeric_summary_cols if col in df.columns
    ]

    summary_parts: list[pd.DataFrame] = []
    if available_numeric_cols:
        print("\n=== Numeric Means per Cluster ===")
        cluster_means = df.groupby("cluster")[available_numeric_cols].mean()
        pd.set_option("display.float_format", "{:,.0f}".format)
        print(cluster_means.to_string())
        pd.reset_option("display.float_format")
        summary_parts.append(cluster_means)
        logger.info("[KMeans] Cluster numeric means computed")

    if "jenis_usaha" in df.columns:
        print("\n=== Jenis Usaha Distribution per Cluster ===")
        jenis_dist = (
            df.groupby(["cluster", "jenis_usaha"])
            .size()
            .unstack(fill_value=0)
        )
        print(jenis_dist.to_string())

        top_jenis = (
            df.groupby("cluster")["jenis_usaha"]
            .agg(lambda values: values.value_counts().index[0])
            .rename("top_jenis_usaha")
        )
        top_jenis_count = (
            df.groupby("cluster")["jenis_usaha"]
            .agg(lambda values: int(values.value_counts().iloc[0]))
            .rename("top_jenis_count")
        )

        if summary_parts:
            summary = summary_parts[0].join([top_jenis, top_jenis_count])
        else:
            summary = pd.DataFrame(top_jenis).join(top_jenis_count)
    else:
        summary = summary_parts[0] if summary_parts else pd.DataFrame(index=dist.index)

    summary["cluster_size"] = dist
    if "top_jenis_count" in summary.columns:
        summary["top_jenis_share"] = summary["top_jenis_count"] / summary["cluster_size"]

    summary.to_csv(CLUSTER_SUMMARY_PATH)
    logger.info(f"[KMeans] Cluster summary saved -> {CLUSTER_SUMMARY_PATH}")
    return df


def run(X_scaled: np.ndarray, df_umkm: pd.DataFrame) -> tuple[KMeans, pd.DataFrame]:
    """
    Full training pipeline:
    1. Analyze candidate K values and save plots.
    2. Train final KMeans with KMEANS_N_CLUSTERS.
    3. Save cluster_summary.csv.
    4. Generate cluster_skill_map.json from the summary.
    """
    logger.info("=" * 60)
    logger.info("[KMeans] Starting full KMeans training pipeline")
    logger.info("=" * 60)

    analysis_best_k = find_optimal_k(X_scaled)
    if analysis_best_k != KMEANS_N_CLUSTERS:
        logger.info(
            "[KMeans] Analysis suggested K=%s, final training uses forced K=%s",
            analysis_best_k,
            KMEANS_N_CLUSTERS,
        )

    model = train(X_scaled, n_clusters=KMEANS_N_CLUSTERS)
    df_clustered = analyze_clusters(df_umkm, model.labels_)
    skill_map = generate_cluster_skill_map(expected_clusters=KMEANS_N_CLUSTERS)

    print("\n=== Generated Cluster Skill Map ===")
    for cluster_id, query in sorted(skill_map.items()):
        print(f"  Cluster {cluster_id}: {query}")

    logger.info("[KMeans] Training pipeline complete")
    return model, df_clustered


if __name__ == "__main__":
    from config import UMKM_DATA_PATH
    from src.preprocessing_umkm import UMKMPreprocessor

    print("=== KMeans Standalone Test ===")
    prep = UMKMPreprocessor()
    df_proc, X_scaled_proc = prep.run(UMKM_DATA_PATH)

    trained_model, clustered_df = run(X_scaled_proc, df_proc)
    print(f"\nModel type    : {type(trained_model)}")
    print(f"Labels unique : {sorted(np.unique(trained_model.labels_))}")
    print(f"DF shape      : {clustered_df.shape}")
