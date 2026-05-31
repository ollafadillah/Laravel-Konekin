"""
Cluster skill-map artifact helpers.

The current production path intentionally uses cluster_summary.csv as the
source of truth. Later, when live cluster data is stable, this module can be
upgraded to generate queries directly from KMeans centroids.
"""

from __future__ import annotations

import json
import logging
import os
import re
from datetime import datetime, timezone
from pathlib import Path
from typing import Any

import pandas as pd

from config import (
    CLUSTER_SKILL_MAP_PATH,
    CLUSTER_SUMMARY_PATH,
    KMEANS_N_CLUSTERS,
    LOG_FORMAT,
)

logging.basicConfig(level=logging.INFO, format=LOG_FORMAT)
logger = logging.getLogger(__name__)

DEFAULT_QUERY = "digital marketing social media content branding marketplace"

JENIS_USAHA_SKILLS = {
    "jasa": [
        "service marketing",
        "customer support",
        "landing page",
        "copywriting",
        "social media",
    ],
    "perdagangan": [
        "ecommerce",
        "marketplace optimization",
        "product photography",
        "digital ads",
        "sales copywriting",
    ],
    "kuliner": [
        "food photography",
        "menu design",
        "instagram content",
        "short video",
        "local seo",
    ],
    "fashion": [
        "fashion photography",
        "catalog design",
        "branding",
        "social commerce",
        "influencer content",
    ],
    "pendidikan": [
        "learning content",
        "presentation design",
        "video editing",
        "copywriting",
        "community marketing",
    ],
    "kerajinan": [
        "product photography",
        "brand identity",
        "catalog design",
        "marketplace listing",
        "social media content",
    ],
    "pertanian": [
        "supply chain content",
        "product catalog",
        "marketplace listing",
        "brand storytelling",
        "local marketing",
    ],
    "kesehatan": [
        "educational content",
        "trust branding",
        "copywriting",
        "social media",
        "local seo",
    ],
    "teknologi": [
        "web development",
        "ui ux",
        "technical content",
        "seo",
        "digital marketing",
    ],
    "kreatif": [
        "graphic design",
        "branding",
        "illustration",
        "video editing",
        "content strategy",
    ],
}


def _normalize_text(value: Any) -> str:
    text = str(value or "").strip().lower()
    text = re.sub(r"\s+", " ", text)
    return text


def _dedupe_terms(terms: list[str]) -> list[str]:
    seen = set()
    result = []
    for term in terms:
        normalized = _normalize_text(term)
        if normalized and normalized not in seen:
            seen.add(normalized)
            result.append(normalized)
    return result


def _category_terms(top_jenis_usaha: str) -> list[str]:
    normalized = _normalize_text(top_jenis_usaha)
    for key, terms in JENIS_USAHA_SKILLS.items():
        if key in normalized:
            return terms
    return DEFAULT_QUERY.split()


def _numeric_terms(row: pd.Series, medians: pd.Series) -> list[str]:
    terms: list[str] = []

    omset = float(row.get("omset", 0) or 0)
    laba = float(row.get("laba", 0) or 0)
    aset = float(row.get("aset", 0) or 0)
    tenaga_kerja = float(row.get("total_tenaga_kerja", 0) or 0)
    umur_usaha = float(row.get("umur_usaha", 0) or 0)
    rasio_laba = float(row.get("rasio_laba_omset", 0) or 0)

    if omset >= float(medians.get("omset", 0) or 0):
        terms.extend(["digital ads", "sales growth", "conversion optimization"])
    else:
        terms.extend(["brand awareness", "marketplace setup", "social media content"])

    if laba <= 0 or rasio_laba <= 0:
        terms.extend(["financial planning", "pricing strategy", "cost optimization"])
    elif laba >= float(medians.get("laba", 0) or 0):
        terms.extend(["customer retention", "analytics dashboard", "crm"])

    if aset >= float(medians.get("aset", 0) or 0):
        terms.extend(["inventory management", "product catalog", "operations"])

    if tenaga_kerja >= float(medians.get("total_tenaga_kerja", 0) or 0):
        terms.extend(["workflow automation", "hr operations", "project management"])

    if umur_usaha >= float(medians.get("umur_usaha", 0) or 0):
        terms.extend(["brand refresh", "seo", "ecommerce strategy"])
    else:
        terms.extend(["go to market", "launch campaign", "content creator"])

    return terms


def _read_summary(summary_path: str = CLUSTER_SUMMARY_PATH) -> pd.DataFrame:
    if not os.path.exists(summary_path):
        raise FileNotFoundError(
            f"Cluster summary not found at {summary_path}. Run KMeans training first."
        )

    summary = pd.read_csv(summary_path)
    if "cluster" not in summary.columns:
        first_col = summary.columns[0]
        summary = summary.rename(columns={first_col: "cluster"})

    summary["cluster"] = summary["cluster"].astype(int)
    return summary.sort_values("cluster").reset_index(drop=True)


def build_cluster_skill_map(summary: pd.DataFrame) -> tuple[dict[int, str], dict[str, Any]]:
    numeric_cols = [
        col
        for col in [
            "omset",
            "laba",
            "aset",
            "total_tenaga_kerja",
            "umur_usaha",
            "rasio_laba_omset",
        ]
        if col in summary.columns
    ]
    medians = summary[numeric_cols].median(numeric_only=True) if numeric_cols else pd.Series(dtype=float)

    skill_map: dict[int, str] = {}
    profiles: dict[str, Any] = {}

    for _, row in summary.iterrows():
        cluster_id = int(row["cluster"])
        top_jenis = str(row.get("top_jenis_usaha", "") or "")
        terms = _dedupe_terms(_category_terms(top_jenis) + _numeric_terms(row, medians))
        query = " ".join(terms) if terms else DEFAULT_QUERY

        skill_map[cluster_id] = query
        profiles[str(cluster_id)] = {
            "top_jenis_usaha": top_jenis,
            "cluster_size": int(row.get("cluster_size", 0) or 0),
            "numeric_means": {
                col: round(float(row.get(col, 0) or 0), 6)
                for col in numeric_cols
            },
            "query_terms": terms,
        }

    return skill_map, profiles


def save_cluster_skill_map(
    skill_map: dict[int, str],
    profiles: dict[str, Any],
    output_path: str = CLUSTER_SKILL_MAP_PATH,
    source_path: str = CLUSTER_SUMMARY_PATH,
) -> dict[str, Any]:
    artifact = {
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "source": str(Path(source_path)),
        "method": "top_jenis_usaha + numeric mean rules from cluster_summary.csv",
        "cluster_skill_map": {str(k): v for k, v in sorted(skill_map.items())},
        "cluster_profiles": profiles,
    }

    os.makedirs(os.path.dirname(output_path), exist_ok=True)
    with open(output_path, "w", encoding="utf-8") as fh:
        json.dump(artifact, fh, ensure_ascii=False, indent=2)

    logger.info(f"[ClusterMap] Skill map saved -> {output_path}")
    return artifact


def generate_cluster_skill_map(
    summary_path: str = CLUSTER_SUMMARY_PATH,
    output_path: str = CLUSTER_SKILL_MAP_PATH,
    expected_clusters: int | None = KMEANS_N_CLUSTERS,
) -> dict[int, str]:
    summary = _read_summary(summary_path)
    if expected_clusters is not None and len(summary) != expected_clusters:
        raise ValueError(
            f"Expected {expected_clusters} clusters in {summary_path}, found {len(summary)}."
        )

    skill_map, profiles = build_cluster_skill_map(summary)
    save_cluster_skill_map(skill_map, profiles, output_path, summary_path)
    return skill_map


def load_cluster_skill_map(
    path: str = CLUSTER_SKILL_MAP_PATH,
    fallback_to_summary: bool = True,
    expected_clusters: int | None = None,
) -> dict[int, str]:
    if not os.path.exists(path):
        if fallback_to_summary and os.path.exists(CLUSTER_SUMMARY_PATH):
            logger.warning(
                f"[ClusterMap] {path} not found, regenerating from {CLUSTER_SUMMARY_PATH}"
            )
            return generate_cluster_skill_map(
                CLUSTER_SUMMARY_PATH,
                path,
                expected_clusters=expected_clusters,
            )
        logger.warning(f"[ClusterMap] {path} not found, using default query")
        total = expected_clusters or KMEANS_N_CLUSTERS
        return {i: DEFAULT_QUERY for i in range(total)}

    with open(path, "r", encoding="utf-8") as fh:
        payload = json.load(fh)

    raw_map = payload.get("cluster_skill_map", payload)
    skill_map = {int(k): str(v) for k, v in raw_map.items()}

    if expected_clusters is not None:
        expected_ids = set(range(expected_clusters))
        missing_ids = sorted(expected_ids - set(skill_map))
        if missing_ids:
            logger.warning(
                f"[ClusterMap] Missing cluster IDs in {path}: {missing_ids}. "
                "Using default query for missing clusters."
            )
            for cluster_id in missing_ids:
                skill_map[cluster_id] = DEFAULT_QUERY

    return skill_map
