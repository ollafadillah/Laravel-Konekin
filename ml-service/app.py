"""
app.py - Konekin ML Service
Flask REST API exposing recommendation endpoints and trained model status.
"""

import logging
import os
import sys
import traceback

from flask import Flask, jsonify, request
from flask_cors import CORS

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))
from config import (  # noqa: E402
    CLUSTER_LABEL_MAP,
    CW_DATA_PATH,
    FLASK_DEBUG,
    FLASK_HOST,
    FLASK_PORT,
    LOG_FORMAT,
)
from model_registry import (  # noqa: E402
    artifact_status,
    get_recommender,
    reload_recommender,
    recommender_loaded,
    required_artifacts_ready,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})


def _success(data: dict, status: int = 200):
    return jsonify({"success": True, **data}), status


def _error(message: str, status: int = 400):
    return jsonify({"success": False, "error": message}), status


@app.route("/health", methods=["GET"])
def health():
    """
    GET /health
    Quick service and model health check.
    """
    ready = required_artifacts_ready()
    return jsonify(
        {
            "status": "ok" if ready else "degraded",
            "service": "konekin-ml-service",
            "models_loaded": recommender_loaded(),
            "artifacts_ready": ready,
            "artifacts": artifact_status(),
        }
    ), (200 if ready else 503)


@app.route("/api/models/status", methods=["GET"])
def model_status():
    """
    GET /api/models/status
    Return artifact availability and in-memory cache state.
    """
    return _success(
        {
            "models_loaded": recommender_loaded(),
            "artifacts_ready": required_artifacts_ready(),
            "artifacts": artifact_status(),
        }
    )


@app.route("/api/models/reload", methods=["POST"])
def reload_models():
    """
    POST /api/models/reload
    Reload the recommender singleton from the latest .pkl artifacts.
    """
    try:
        recommender = reload_recommender()
        return _success(
            {
                "message": "Model artifacts reloaded successfully",
                "models_loaded": True,
                "artifacts_ready": required_artifacts_ready(),
                "clusters": int(getattr(recommender, "n_clusters", 0) or 0),
            }
        )
    except FileNotFoundError as exc:
        logger.error(f"[App] Reload models failed: {exc}")
        return _error(f"Model artifact missing: {exc}", 503)
    except Exception as exc:
        logger.error(f"[App] Reload models error: {traceback.format_exc()}")
        return _error(f"Internal server error: {str(exc)}", 500)


@app.route("/api/recommend", methods=["POST"])
def recommend():
    """
    POST /api/recommend
    Recommend Creative Workers for a given UMKM profile.
    """
    try:
        body = request.get_json(force=True, silent=True)
        if not body:
            return _error("Request body must be valid JSON", 400)

        top_n = int(body.pop("top_n", 10))
        min_budget = body.pop("min_budget", None)
        experience_level = body.pop("experience_level", None)

        required = ["omset", "laba", "aset"]
        missing = [k for k in required if k not in body]
        if missing:
            return _error(f"Missing required fields: {missing}", 400)

        rec = get_recommender()
        cluster_id, recommendations = rec.recommend(
            umkm_features=body,
            top_n=top_n,
            min_budget=float(min_budget) if min_budget is not None else None,
            experience_level=experience_level,
        )

        cluster_label = CLUSTER_LABEL_MAP.get(cluster_id, f"Cluster {cluster_id}")
        return _success(
            {
                "cluster": cluster_id,
                "cluster_label": cluster_label,
                "total_found": len(recommendations),
                "recommendations": recommendations,
            }
        )
    except ValueError as exc:
        logger.warning(f"[App] /api/recommend ValueError: {exc}")
        return _error(str(exc), 400)
    except Exception as exc:
        logger.error(f"[App] /api/recommend Error: {traceback.format_exc()}")
        return _error(f"Internal server error: {str(exc)}", 500)


@app.route("/api/recommend/skills", methods=["POST"])
def recommend_by_skills():
    """
    POST /api/recommend/skills
    Direct skill-based search without UMKM profile.
    """
    try:
        body = request.get_json(force=True, silent=True)
        if not body:
            return _error("Request body must be valid JSON", 400)

        query_skills = body.get("skills", "").strip()
        top_n = int(body.get("top_n", 10))
        min_budget = body.get("min_budget", None)
        experience_level = body.get("experience_level", None)

        if not query_skills:
            return _error("'skills' field is required and cannot be empty", 400)

        rec = get_recommender()
        results = rec.recommend_by_skills(
            query_skills=query_skills,
            top_n=top_n,
            min_budget=float(min_budget) if min_budget is not None else None,
            experience_level=experience_level,
        )

        return _success(
            {
                "query": query_skills,
                "total_found": len(results),
                "recommendations": results,
            }
        )
    except ValueError as exc:
        logger.warning(f"[App] /api/recommend/skills ValueError: {exc}")
        return _error(str(exc), 400)
    except Exception as exc:
        logger.error(f"[App] /api/recommend/skills Error: {traceback.format_exc()}")
        return _error(f"Internal server error: {str(exc)}", 500)


@app.route("/api/categories", methods=["GET"])
def get_categories():
    """
    GET /api/categories
    Return all unique job_category values from the CW dataset.
    """
    try:
        import pandas as pd

        df = pd.read_csv(CW_DATA_PATH, usecols=["job_category"], low_memory=False)
        categories = sorted(df["job_category"].dropna().unique().tolist())
        return _success({"total": len(categories), "categories": categories})
    except Exception as exc:
        logger.error(f"[App] /api/categories Error: {traceback.format_exc()}")
        return _error(f"Internal server error: {str(exc)}", 500)


@app.route("/api/stats", methods=["GET"])
def get_stats():
    """
    GET /api/stats
    Return dataset statistics.
    """
    try:
        import pandas as pd

        df = pd.read_csv(CW_DATA_PATH, low_memory=False)

        total_cw = len(df)
        total_available = int((df["is_available"] == 1).sum())
        total_verified = int((df["profile_verified"] == 1).sum()) if "profile_verified" in df.columns else None

        cat_dist = df["job_category"].value_counts().to_dict() if "job_category" in df.columns else {}
        exp_dist = df["experience_level"].value_counts().to_dict() if "experience_level" in df.columns else {}

        avg_rating = round(float(df["client_rating"].mean()), 4) if "client_rating" in df.columns else None
        avg_success_rate = round(float(df["success_rate_job"].mean()), 4) if "success_rate_job" in df.columns else None
        avg_rehire_rate = round(float(df["rehire_rate"].mean()), 4) if "rehire_rate" in df.columns else None

        return _success(
            {
                "total_cw": total_cw,
                "total_available": total_available,
                "total_verified": total_verified,
                "availability_rate": round(total_available / total_cw, 4),
                "category_distribution": cat_dist,
                "experience_distribution": exp_dist,
                "avg_client_rating": avg_rating,
                "avg_success_rate": avg_success_rate,
                "avg_rehire_rate": avg_rehire_rate,
            }
        )
    except Exception as exc:
        logger.error(f"[App] /api/stats Error: {traceback.format_exc()}")
        return _error(f"Internal server error: {str(exc)}", 500)


@app.errorhandler(400)
def bad_request(e):
    return _error(str(e), 400)


@app.errorhandler(404)
def not_found(e):
    return jsonify(
        {
            "success": False,
            "error": "Endpoint not found",
            "available_endpoints": [
                "GET  /health",
                "GET  /api/models/status",
                "POST /api/models/reload",
                "POST /api/recommend",
                "POST /api/recommend/skills",
                "GET  /api/categories",
                "GET  /api/stats",
            ],
        }
    ), 404


@app.errorhandler(405)
def method_not_allowed(e):
    return _error("Method not allowed", 405)


@app.errorhandler(500)
def internal_error(e):
    logger.error(f"[App] Unhandled 500: {traceback.format_exc()}")
    return _error("Internal server error", 500)


if __name__ == "__main__":
    logger.info(
        f"[App] Starting Konekin ML Service on "
        f"http://{FLASK_HOST}:{FLASK_PORT}"
    )
    try:
        get_recommender()
    except FileNotFoundError as exc:
        logger.warning(f"[App] Recommender not loaded at startup: {exc}")

    app.run(
        host=FLASK_HOST,
        port=FLASK_PORT,
        debug=FLASK_DEBUG,
    )
