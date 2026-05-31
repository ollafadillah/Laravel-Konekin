"""Backward-compatible entrypoint for KMeans training."""

from src.train_kmeans import *  # noqa: F401,F403


if __name__ == "__main__":
    from config import UMKM_DATA_PATH
    from src.preprocessing_umkm import UMKMPreprocessor

    print("=== KMeans Standalone Test ===")
    prep = UMKMPreprocessor()
    df_proc, X_scaled_proc = prep.run(UMKM_DATA_PATH)
    model, df_clustered = run(X_scaled_proc, df_proc)  # noqa: F405

    print(f"\nModel type    : {type(model)}")
    print(f"Labels unique : {sorted(set(model.labels_))}")
    print(f"DF shape      : {df_clustered.shape}")
