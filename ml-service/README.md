# Konekin ML Service

Flask REST API untuk sistem rekomendasi berbasis Machine Learning pada platform Konekin.
Menggabungkan data **Freelancer (Creative Workers)** dan **UMKM** menggunakan:
- **KMeans Clustering** (Elbow Method + Silhouette Score)
- **Content-Based Filtering** (TF-IDF + Cosine Similarity)
- **MongoDB** sebagai penyimpanan hasil clustering dan recommendation logs

---

## Struktur Folder

```
ml-service/
├── app.py              # Flask app utama + Blueprint routing (7 endpoints)
├── config.py           # Konfigurasi terpusat: path, MongoDB, model settings
├── database.py         # MongoDB singleton manager (PyMongo)
├── preprocessing.py    # Pipeline preprocessing kedua dataset
├── clustering.py       # KMeans: Elbow + Silhouette → fit → label semantik
├── recommender.py      # TF-IDF + Cosine Similarity (CBF)
├── train.py            # Script training offline (jalankan sekali)
├── models/             # File .pkl hasil training (auto-created)
├── data/
│   ├── freelancer_earnings_bd.csv
│   └── dataset_umkm.csv
├── .env.example
├── requirements.txt
└── README.md
```

---

## Instalasi

### 1. Buat Virtual Environment

```bash
cd ml-service
python -m venv venv

# Windows
venv\Scripts\activate

# Mac/Linux
source venv/bin/activate
```

### 2. Install Dependencies

```bash
pip install -r requirements.txt
```

### 3. Setup Environment

```bash
copy .env.example .env   # Windows
# cp .env.example .env   # Mac/Linux
```

Edit `.env` sesuai konfigurasi kamu:
```
MONGO_URI=mongodb://localhost:27017/konekin_ml
FLASK_PORT=5000
```

### 4. Pastikan Dataset Ada

```
ml-service/data/freelancer_earnings_bd.csv   (1950 rows)
ml-service/data/dataset_umkm.csv             (13564 rows)
```

---

## Training

Jalankan **sekali** sebelum menjalankan server:

```bash
python train.py
```

Proses ini akan:
1. Load & preprocessing kedua dataset
2. KMeans clustering dengan Elbow + Silhouette (k=2..10)
3. TF-IDF vectorizer + cosine similarity matrix
4. Simpan semua file `.pkl` ke folder `models/`
5. Populate MongoDB: `freelancer_clusters`, `umkm_clusters`, `cluster_profiles`

Output yang diharapkan:
```
============================================================
  FASE 1: PREPROCESSING
============================================================
[INFO] Freelancer dataset loaded: 1950 rows × 15 cols
[INFO] UMKM dataset loaded: 13564 rows × 14 cols
...
============================================================
  TRAINING SELESAI — 45.2 detik
============================================================
```

---

## Menjalankan Server

```bash
python app.py
```

Server berjalan di: `http://localhost:5000`

Untuk production dengan Gunicorn:
```bash
gunicorn -w 2 -b 0.0.0.0:5000 "app:create_app()"
```

---

## API Endpoints

### GET /health

Cek status API, MongoDB, dan model yang ter-load.

```bash
curl http://localhost:5000/health
```

```json
{
  "status": "success",
  "data": {
    "api": "Konekin ML Service",
    "version": "1.0.0",
    "uptime_seconds": 12.5,
    "mongodb_connected": true,
    "models_loaded": true,
    "freelancer_count": 1950,
    "umkm_count": 13564,
    "models": ["kmeans_freelancer", "tfidf_vectorizer", "..."]
  }
}
```

---

### GET /clusters/info

Ambil profil semua cluster dari MongoDB.

```bash
curl "http://localhost:5000/clusters/info?dataset=freelancer"
curl "http://localhost:5000/clusters/info?dataset=umkm"
```

```json
{
  "status": "success",
  "data": [
    {
      "cluster_id": 0,
      "cluster_label": "Beginner",
      "metrics": {
        "avg_earnings_usd": 12345.6,
        "avg_client_rating": 3.8,
        "avg_job_success_rate": 0.72
      },
      "total_members": 430
    }
  ]
}
```

---

### POST /cluster/freelancer

Predict cluster untuk freelancer baru (tidak disimpan ke DB).

```bash
curl -X POST http://localhost:5000/cluster/freelancer \
  -H "Content-Type: application/json" \
  -d '{
    "Job_Category": "Web Development",
    "Platform": "Upwork",
    "Experience_Level": "Expert",
    "Client_Region": "North America",
    "Payment_Method": "Bank Transfer",
    "Job_Completed": 45,
    "Earnings_USD": 75000,
    "Hourly_Rate": 85,
    "Job_Success_Rate": 0.95,
    "Client_Rating": 4.8,
    "Job_Duration_Days": 30,
    "Project_Type": "Fixed",
    "Rehire_Rate": 0.7,
    "Marketing_Spend": 2000
  }'
```

```json
{
  "status": "success",
  "data": {
    "cluster_id": 2,
    "cluster_label": "High Performer",
    "cluster_profile": {
      "metrics": { "avg_earnings_usd": 68000, "avg_client_rating": 4.5 }
    },
    "input_vs_cluster": {
      "earnings_usd_input": 75000,
      "earnings_usd_cluster_avg": 68000,
      "earnings_usd_diff_pct": 10.29
    }
  }
}
```

---

### POST /cluster/umkm

Predict cluster untuk UMKM baru (tidak disimpan ke DB).

```bash
curl -X POST http://localhost:5000/cluster/umkm \
  -H "Content-Type: application/json" \
  -d '{
    "jenis_usaha": "Perdagangan",
    "tenaga_kerja_perempuan": 5,
    "tenaga_kerja_laki_laki": 3,
    "aset": 5000000,
    "omset": 20000000,
    "marketplace": "Shopee",
    "kapasitas_produksi": 200,
    "status_legalitas": "Terdaftar",
    "tahun_berdiri": 2018,
    "laba": 8000000,
    "biaya_karyawan": 3000000,
    "jumlah_pelanggan": 150
  }'
```

```json
{
  "status": "success",
  "data": {
    "cluster_id": 1,
    "cluster_label": "UMKM Berkembang",
    "cluster_profile": { "metrics": { "avg_omset": 18500000 } },
    "input_vs_cluster": {
      "omset_input": 20000000,
      "omset_cluster_avg": 18500000,
      "omset_diff_pct": 8.11
    }
  }
}
```

---

### GET /recommend

Sistem rekomendasi. Tiga tipe:

| `type` | Deskripsi |
|--------|-----------|
| `freelancer_similar` | Cari freelancer mirip dengan freelancer tertentu |
| `umkm_to_freelancer` | Cari freelancer cocok untuk UMKM tertentu |
| `freelancer_to_umkm` | Cari UMKM cocok untuk freelancer tertentu |

```bash
# Freelancer mirip dengan Freelancer #101
curl "http://localhost:5000/recommend?type=freelancer_similar&id=101&top_n=5"

# Freelancer yang cocok untuk UMKM ID 28828567
curl "http://localhost:5000/recommend?type=umkm_to_freelancer&id=28828567&top_n=5"

# UMKM yang cocok untuk Freelancer #42
curl "http://localhost:5000/recommend?type=freelancer_to_umkm&id=42&top_n=5"
```

```json
{
  "status": "success",
  "data": {
    "type": "umkm_to_freelancer",
    "input_id": "28828567",
    "top_n": 5,
    "results": [
      {
        "id": "101",
        "name": "Freelancer #101",
        "job_category": "Design",
        "platform": "Fiverr",
        "similarity_score": 0.8742,
        "cluster_id": 2,
        "cluster_label": "High Performer"
      }
    ],
    "response_time_ms": 12.4
  }
}
```

---

### GET /clusters/members

Ambil anggota cluster dari MongoDB.

```bash
curl "http://localhost:5000/clusters/members?dataset=umkm&cluster_id=1&limit=10"
curl "http://localhost:5000/clusters/members?dataset=freelancer&cluster_id=0&limit=20"
```

---

### GET /data/sample

Sample data untuk debugging.

```bash
curl "http://localhost:5000/data/sample?dataset=freelancer&n=3"
curl "http://localhost:5000/data/sample?dataset=umkm&n=5"
```

---

## Integrasi dengan Laravel

### Via Flask API (Http facade)

```php
use Illuminate\Support\Facades\Http;

// Predict cluster UMKM baru
$response = Http::post('http://localhost:5000/cluster/umkm', [
    'jenis_usaha'            => 'Perdagangan',
    'omset'                  => 20000000,
    'laba'                   => 8000000,
    'aset'                   => 5000000,
    'marketplace'            => 'Shopee',
    'status_legalitas'       => 'Terdaftar',
    'tahun_berdiri'          => 2018,
    'kapasitas_produksi'     => 200,
    'jumlah_pelanggan'       => 150,
    'biaya_karyawan'         => 3000000,
    'tenaga_kerja_perempuan' => 5,
    'tenaga_kerja_laki_laki' => 3,
]);

$data = $response->json('data');
$cluster = $data['cluster_label']; // "UMKM Berkembang"

// Rekomendasi freelancer untuk UMKM
$rec = Http::get('http://localhost:5000/recommend', [
    'type'  => 'umkm_to_freelancer',
    'id'    => $umkm->id_umkm,
    'top_n' => 5,
]);

$freelancers = $rec->json('data.results');
```

### Akses MongoDB Langsung (jenssegers/mongodb)

Data yang sudah di-training bisa diakses langsung tanpa hit Flask:

```php
use Illuminate\Support\Facades\DB;

// Ambil profil cluster UMKM
$profiles = DB::collection('cluster_profiles')
    ->where('dataset', 'umkm')
    ->orderBy('cluster_id')
    ->get();

// Cari UMKM dalam cluster tertentu
$umkmInCluster = DB::collection('umkm_clusters')
    ->where('cluster_id', 1)
    ->limit(20)
    ->get();

// Log rekomendasi terbaru
$logs = DB::collection('recommendation_logs')
    ->where('request_type', 'umkm_to_freelancer')
    ->orderBy('requested_at', 'desc')
    ->limit(10)
    ->get();
```

---

## MongoDB Collections

| Collection | Isi | Index |
|------------|-----|-------|
| `freelancer_clusters` | Setiap freelancer + cluster info | `freelancer_id` (unique), `cluster_id` |
| `umkm_clusters` | Setiap UMKM + cluster info | `id_umkm` (unique), `cluster_id` |
| `cluster_profiles` | Profil per cluster | `(dataset, cluster_id)` unique |
| `recommendation_logs` | Log setiap request recommend | `request_type`, `requested_at` |

---

## Error Codes

| Code | Kondisi |
|------|---------|
| 400 | Body tidak valid, field wajib kosong, enum salah |
| 404 | ID tidak ditemukan |
| 503 | MongoDB tidak terhubung / model belum di-load |
| 500 | Internal error (lihat log server) |

Semua error response format JSON:
```json
{
  "status": "error",
  "data": null,
  "message": "Field wajib tidak ada: ['omset']",
  "timestamp": "2025-05-03T05:30:00+00:00"
}
```
