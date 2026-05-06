"""
run.py - Konekin ML Service
Full training pipeline: preprocess -> cluster -> tfidf -> save artifacts.

Usage:
    python run.py
"""

import importlib.util
import os
import sys
import time


ROOT = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, ROOT)

from config import (  # noqa: E402
    UMKM_DATA_PATH, CW_DATA_PATH,
    KMEANS_MODEL_PATH, TFIDF_VECTORIZER_PATH,
    SCALER_UMKM_PATH, SCALER_CW_PATH,
    LABEL_ENCODERS_PATH, CLUSTER_SUMMARY_PATH,
    ELBOW_PLOT_PATH, SILHOUETTE_PLOT_PATH,
    ensure_dirs,
)

DIVIDER = "=" * 65
OPTIONAL_DEPENDENCIES = {
    "matplotlib": "Elbow dan silhouette plot akan dilewati kalau matplotlib belum terpasang.",
}
REQUIRED_DEPENDENCIES = ["pandas", "numpy", "joblib", "sklearn"]


def section(title: str) -> None:
    print(f"\n{DIVIDER}")
    print(f"  {title}")
    print(DIVIDER)


def check_dependencies() -> None:
    section("0. Dependency Check")

    missing_required = [
        module_name
        for module_name in REQUIRED_DEPENDENCIES
        if importlib.util.find_spec(module_name) is None
    ]

    for module_name, note in OPTIONAL_DEPENDENCIES.items():
        if importlib.util.find_spec(module_name) is None:
            print(f"  !  Optional dependency not found: {module_name}")
            print(f"     {note}")

    if missing_required:
        print("  [ERROR] Missing required Python packages:")
        for module_name in missing_required:
            print(f"     - {module_name}")
        print("\n  Install dependencies from ml-service/requirements.txt in the active environment, then rerun run.py.")
        sys.exit(1)


def check_data() -> None:
    """Verify that required dataset files exist."""
    section("1. Pre-flight Checks")
    ok = True

    for path, label in [
        (UMKM_DATA_PATH, "UMKM dataset"),
        (CW_DATA_PATH, "CW dataset"),
    ]:
        if os.path.exists(path):
            size_mb = os.path.getsize(path) / 1_048_576
            print(f"  ✓  {label:<20}: {path}  ({size_mb:.1f} MB)")
        else:
            print(f"  ✗  {label:<20}: NOT FOUND - {path}")
            ok = False

    if not ok:
        print("\n  [ERROR] Please place dataset files in the 'data/' directory.")
        sys.exit(1)


def print_artifact_summary() -> None:
    section("5. Artifact Summary")
    artifacts = {
        "KMeans model": KMEANS_MODEL_PATH,
        "UMKM Scaler": SCALER_UMKM_PATH,
        "CW Scaler": SCALER_CW_PATH,
        "TF-IDF Vectorizer": TFIDF_VECTORIZER_PATH,
        "Label Encoders": LABEL_ENCODERS_PATH,
        "Cluster Summary CSV": CLUSTER_SUMMARY_PATH,
        "Elbow Plot": ELBOW_PLOT_PATH,
        "Silhouette Plot": SILHOUETTE_PLOT_PATH,
    }

    all_saved = True
    for label, path in artifacts.items():
        exists = os.path.exists(path)
        icon = "✓" if exists else "✗"
        size = f"({os.path.getsize(path)/1024:.1f} KB)" if exists else "(missing)"
        print(f"  {icon}  {label:<25}: {path}  {size}")
        if not exists:
            all_saved = False

    total_time = time.time() - START_TIME
    print(f"\n{DIVIDER}")
    if all_saved:
        print(f"  ✓  Training complete in {total_time:.1f}s")
    else:
        print(f"  ⚠  Training done ({total_time:.1f}s) but some artifacts missing!")
    print(DIVIDER)


def main() -> None:
    ensure_dirs()
    check_dependencies()
    check_data()

    try:
        section("2. UMKM Preprocessing")
        t0 = time.time()
        from src.preprocessing_umkm import UMKMPreprocessor

        umkm_prep = UMKMPreprocessor()
        df_umkm, X_scaled_umkm = umkm_prep.run(UMKM_DATA_PATH)
        print(f"\n  UMKM processed shape : {df_umkm.shape}")
        print(f"  X_scaled shape       : {X_scaled_umkm.shape}")
        print(f"  Duration             : {time.time() - t0:.1f}s")

        section("3. CW Preprocessing")
        t0 = time.time()
        from src.preprocessing_cw import CWPreprocessor

        cw_prep = CWPreprocessor()
        df_cw, tfidf_matrix_init, vectorizer_init = cw_prep.run(CW_DATA_PATH)
        print(f"\n  CW processed shape   : {df_cw.shape}")
        print(f"  TF-IDF shape         : {tfidf_matrix_init.shape}")
        print(f"  Duration             : {time.time() - t0:.1f}s")

        section("4. KMeans Clustering")
        t0 = time.time()
        from src.train_kmeans import run as run_kmeans

        kmeans_model, df_umkm_clustered = run_kmeans(X_scaled_umkm, df_umkm)
        print(f"\n  Clusters found       : {kmeans_model.n_clusters}")
        print(
            "  Cluster sizes        : "
            + str(df_umkm_clustered["cluster"].value_counts().sort_index().to_dict())
        )
        print(f"  Duration             : {time.time() - t0:.1f}s")

        section("5. TF-IDF Training & Evaluation")
        t0 = time.time()
        from src.train_tfidf import run as run_tfidf

        tfidf_matrix, vectorizer = run_tfidf(df_cw)
        print(f"\n  TF-IDF shape         : {tfidf_matrix.shape}")
        print(f"  Vocab size           : {len(vectorizer.vocabulary_)}")
        print(f"  Duration             : {time.time() - t0:.1f}s")

        print_artifact_summary()

    except ModuleNotFoundError as exc:
        missing_module = exc.name or "unknown"
        print(f"\n  [ERROR] Python module not found: {missing_module}")
        note = OPTIONAL_DEPENDENCIES.get(missing_module)
        if note:
            print(f"  Note: {note}")
        print("  Install dependencies from ml-service/requirements.txt in the active environment, then rerun run.py.")
        sys.exit(1)


START_TIME = time.time()


if __name__ == "__main__":
    main()
