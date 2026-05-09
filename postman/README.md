# Konekin Postman Pack

Koleksi ini dipakai untuk ngetes endpoint Laravel API, creative worker, UMKM, dan Flask ML service.

## Isi

- `Konekin-Laravel.postman_collection.json`
- `Konekin-Laravel.postman_environment.json`

## Cara Pakai

1. Buka Postman.
2. Import kedua file di folder `postman/`.
3. Pilih environment `Konekin Local`.
4. Jalankan `Auth > Login UMKM` atau `Auth > Login Creative Worker`.
5. Setelah login berhasil, token akan disimpan ke `current_token`, `umkm_token`, atau `creative_token`.
6. Untuk endpoint yang butuh file upload, ubah field `type: file` di Postman dan pilih file lokal kamu.

## Catatan

- Endpoint Laravel yang diuji di sini adalah endpoint JSON API.
- Route web berbasis session seperti `/rekomendasi-kreator` lebih nyaman diuji lewat browser.
- Kalau kamu mau tes ulang dari role berbeda, login lagi dengan request role yang sesuai supaya `current_token` terganti.
