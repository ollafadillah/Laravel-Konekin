# Konekin ML Service

> Hybrid **KMeans + TF-IDF** recommendation engine yang menghubungkan UMKM dengan Creative Workers terbaik.

---

## Arsitektur

```
                          ┌───────────────────┐
      UMKM Profile ──────►│  KMeans Clustering │──► Cluster ID
                          └───────────────────┘
                                    │
                          ┌─────────▼─────────┐
                          │ cluster_skill_map  │──► Skill Query String
                          └───────────────────┘
                                    │
                          ┌─────────▼─────────┐
  CW TF-IDF Matrix ──────►│  Cosine Similarity │──► Top-N CW Recommendations
                          └───────────────────┘
```

## Struktur File

```
konekin-ml-service/
│
├── config.py                   # Konstanta dan path artifact
├── app.py                      # Flask REST API
├── run.py                      # Full training pipeline
├── requirements.txt
│
├── src/
│   ├── __init__.py
│   ├── preprocessing_umkm.py   # UMKMPreprocessor class
│   ├── preprocessing_cw.py     # CWPreprocessor class
│   ├── train_kmeans.py         # KMeans training & evaluation
│   ├── cluster_skill_mapping.py # Auto-generate cluster skill map
│   ├── train_tfidf.py          # TF-IDF training & coverage eval
│   └── recommend.py            # Recommender class (singleton)
│
├── data/
│   ├── cw_dataset.csv          # Creative Workers (5000 rows)
│   └── dataset_umkm.csv        # UMKM (13.564 rows)
│
└── models/                     # Auto-created after training
    ├── kmeans_umkm.pkl
    ├── scaler_umkm.pkl
    ├── scaler_cw.pkl
    ├── tfidf_vectorizer.pkl
    ├── label_encoders.pkl
    ├── cluster_summary.csv
    ├── elbow_plot.png
    └── silhouette_plot.png
```

## Instalasi

```bash
# 1. Clone / extract project
cd konekin-ml-service

# 2. Buat virtual environment
python -m venv venv
source venv/bin/activate        # Linux/Mac
venv\Scripts\activate           # Windows CMD
.\venv\Scripts\Activate.ps1     # Windows PowerShell

# 3. Install dependencies
pip install -r requirements.txt

# 4. Pastikan dataset ada di folder data/
ls data/
# cw_dataset.csv  dataset_umkm.csv
```

## Training

```bash
python run.py
```

Output yang diharapkan:
```
=== Pre-flight checks ===
  ✓  UMKM dataset: data/dataset_umkm.csv (2.1 MB)
  ✓  CW dataset:   data/cw_dataset.csv (0.8 MB)

=== 1. UMKM Preprocessing ===
...
=== 2. CW Preprocessing ===
...
=== 3. KMeans Clustering ===
   K | Inertia | Silhouette
   2 | 45123.2 |     0.3124
   ...
>>> Best K = 5  (silhouette = 0.4231)

=== 5. Artifact Summary ===
  ✓  KMeans model        : models/kmeans_umkm.pkl       (12.3 KB)
  ✓  TF-IDF Vectorizer   : models/tfidf_vectorizer.pkl  (98.1 KB)
  ...

  ✓  Training complete in 24.3s
```

## Menjalankan API

```bash
venv\Scripts\activate           # Windows CMD
# atau: .\venv\Scripts\Activate.ps1 untuk PowerShell
python app.py
# Server: http://0.0.0.0:5000
```

---

## API Endpoints

### `GET /health`
```json
{
  "status": "ok",
  "service": "konekin-ml-service"
}
```

---

### `GET /api/models/status`
Menampilkan status artifact model yang tersimpan di `models/` dan status cache Flask.

**Response ringkas:**
```json
{
  "success": true,
  "models_loaded": true,
  "artifacts_ready": true,
  "artifacts": {
    "kmeans_model": {
      "path": "ml-service/models/kmeans_umkm.pkl",
      "exists": true,
      "size_bytes": 55296
    }
  }
}
```

---

### `POST /api/models/reload`
Memaksa Flask memuat ulang artifact `.pkl` terbaru dari folder `models/`.

**Response ringkas:**
```json
{
  "success": true,
  "message": "Model artifacts reloaded successfully",
  "models_loaded": true
}
```

---

### `POST /api/recommend`
Rekomendasikan Creative Worker berdasarkan profil UMKM.

**Request:**
```json
{
  "omset": 50000000,
  "laba": 10000000,
  "aset": 20000000,
  "jenis_usaha": "Perdagangan",
  "marketplace": "Tokopedia",
  "status_legalitas": "Terdaftar",
  "tenaga_kerja_perempuan": 3,
  "tenaga_kerja_laki_laki": 2,
  "tahun_berdiri": 2018,
  "top_n": 10,
  "min_budget": null,
  "experience_level": null
}
```

**Response:**
```json
{
  "success": true,
  "cluster": 1,
  "cluster_label": "UMKM Digital & Teknologi",
  "total_found": 10,
  "recommendations": [
    {
      "id": 42,
      "full_name": "Budi Santoso",
      "email": "budi@example.com",
      "specific_role": "Full Stack Developer",
      "job_category": "Full Stack Developer",
      "skills": "React, Node.js, Laravel, PostgreSQL",
      "experience_level": "Expert",
      "experience_years": 5.0,
      "success_rate_job": 0.92,
      "client_rating": 4.8,
      "rehire_rate": 0.75,
      "jobs_completed": 48,
      "min_budget_idr": 2000000.0,
      "hourly_rate_usd": 15.0,
      "profile_verified": 1,
      "similarity_score": 0.8241
    }
  ]
}
```

---

### `POST /api/recommend/skills`
Cari CW langsung berdasarkan skill query.

**Request:**
```json
{
  "skills": "social media instagram content planning",
  "top_n": 5
}
```

---

### `GET /api/categories`
```json
{
  "success": true,
  "total": 5,
  "categories": [
    "Content Creator",
    "Full Stack Developer",
    "Graphic Designer",
    "Social Media Specialist",
    "Video Editor"
  ]
}
```

---

### `GET /api/stats`
```json
{
  "success": true,
  "total_cw": 5000,
  "total_available": 3241,
  "total_verified": 2890,
  "availability_rate": 0.6482,
  "category_distribution": {
    "Full Stack Developer": 1012,
    "Graphic Designer": 987,
    ...
  },
  "avg_client_rating": 4.23,
  "avg_success_rate": 0.78,
  "avg_rehire_rate": 0.61
}
```

---

## Integrasi dari Laravel

```php
// Contoh di Laravel Controller
$response = Http::post('http://localhost:5000/api/recommend', [
    'omset'                  => 50000000,
    'laba'                   => 10000000,
    'aset'                   => 20000000,
    'jenis_usaha'            => 'Perdagangan',
    'marketplace'            => 'Tokopedia',
    'status_legalitas'       => 'Terdaftar',
    'tenaga_kerja_perempuan' => 3,
    'tenaga_kerja_laki_laki' => 2,
    'tahun_berdiri'          => 2018,
    'top_n'                  => 10,
]);

$data = $response->json();
```

---

## Filter Opsional

| Parameter          | Tipe      | Contoh              | Keterangan                              |
|--------------------|-----------|---------------------|-----------------------------------------|
| `min_budget`       | `float`   | `5000000`           | Hanya CW dengan min_budget_idr ≤ nilai  |
| `experience_level` | `string`  | `"Intermediate"`    | `Beginner` / `Intermediate` / `Expert`  |
| `top_n`            | `int`     | `10`                | Jumlah hasil rekomendasi (default: 10)  |

---

## Cluster Skill Map

`CLUSTER_SKILL_MAP` tidak lagi di-hardcode di `config.py`.
Training KMeans akan:

1. menjalankan `find_optimal_k()` untuk analisis dan plot,
2. melatih model final dengan `KMEANS_N_CLUSTERS = 5`,
3. menyimpan `models/cluster_summary.csv`,
4. generate `models/cluster_skill_map.json` otomatis dari `top_jenis_usaha` dan rata-rata fitur numerik per cluster.

Saat service rekomendasi berjalan, skill query dibaca dari `models/cluster_skill_map.json`.
