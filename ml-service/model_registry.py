"""
Model registry for Konekin ML Service.

This keeps the trained artifacts on disk and exposes a small cache for
the Flask app so Laravel only needs to call the API endpoints.
"""

from __future__ import annotations

from pathlib import Path
from typing import Any

from config import (
    KMEANS_MODEL_PATH,
    TFIDF_VECTORIZER_PATH,
    SCALER_UMKM_PATH,
    SCALER_CW_PATH,
    LABEL_ENCODERS_PATH,
    CLUSTER_SUMMARY_PATH,
    ELBOW_PLOT_PATH,
    SILHOUETTE_PLOT_PATH,
)


ARTIFACTS = {
    "kmeans_model": KMEANS_MODEL_PATH,
    "tfidf_vectorizer": TFIDF_VECTORIZER_PATH,
    "scaler_umkm": SCALER_UMKM_PATH,
    "scaler_cw": SCALER_CW_PATH,
    "label_encoders": LABEL_ENCODERS_PATH,
    "cluster_summary": CLUSTER_SUMMARY_PATH,
    "elbow_plot": ELBOW_PLOT_PATH,
    "silhouette_plot": SILHOUETTE_PLOT_PATH,
}

_recommender = None


def artifact_status() -> dict[str, dict[str, Any]]:
    """Return a detailed status map for all known artifacts."""
    status: dict[str, dict[str, Any]] = {}

    for name, raw_path in ARTIFACTS.items():
        path = Path(raw_path)
        exists = path.exists()
        status[name] = {
            "path": str(path),
            "exists": exists,
            "size_bytes": path.stat().st_size if exists else None,
        }

    return status


def required_artifacts_ready() -> bool:
    """Check only the core artifacts required to serve recommendations."""
    required_names = [
        "kmeans_model",
        "tfidf_vectorizer",
        "scaler_umkm",
        "scaler_cw",
        "label_encoders",
    ]
    status = artifact_status()
    return all(status[name]["exists"] for name in required_names)


def get_recommender(force_reload: bool = False):
    """Load and cache the recommender singleton."""
    global _recommender

    if force_reload or _recommender is None:
        from src.recommend import Recommender

        _recommender = Recommender()

    return _recommender


def reload_recommender():
    """Drop cache and reload recommender from the latest artifacts."""
    global _recommender
    _recommender = None
    return get_recommender(force_reload=True)


def recommender_loaded() -> bool:
    """Return whether the recommender cache already exists in memory."""
    return _recommender is not None
