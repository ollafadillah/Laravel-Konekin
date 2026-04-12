# 🚀 Konekin - Platform Kolaborasi Creative Worker & UMKM

## ✨ Fitur yang Sudah Tersedia

### 🔐 Authentication
- ✅ Register dengan 2 pilihan tipe user: Creative Worker atau UMKM
- ✅ Login dengan email & password
- ✅ Logout functionality
- ✅ Form validation yang komprehensif
- ✅ Data tersimpan di MySQL Database (Laragon)

### 📊 Dashboard
- ✅ **Dashboard Creative Worker** - `/dashboard/creative`
  - Profil & statistik
  - Edit profil (nama, telepon, alamat, kota, bio)
  - Quick access ke fitur lainnya
  
- ✅ **Dashboard UMKM** - `/dashboard/umkm`
  - Profil usaha & statistik
  - Edit profil usaha
  - Budget management (coming soon)

### 🎨 UI/UX
- ✅ Responsive design dengan Tailwind CSS
- ✅ Modern styling yang menarik
- ✅ Navbar dinamis (berbeda saat login/logout)
- ✅ Form dengan validasi real-time

---

## 🛠️ Setup & Running

### Prerequisites
- Laragon installed dengan MySQL & MongoDB
- PHP 8.3+ (dari Laragon)

### Installation
1. Copy project ke: `D:\laragon\www\laravel-konekin`
2. Database akan auto-create saat startup Laragon

### Running Artisan Commands
**Penting**: Gunakan **Laragon PHP** karena system PHP tidak lengkap.

```bash
# Option 1: Gunakan batch file (sudah auto)
artisan.bat migrate
artisan.bat tinker
artisan.bat serve

# Option 2: Full path
D:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan [command]
```

### Akses via Browser
1. Buka Laragon Manager
2. Click **laravel-konekin** domain atau buka: `http://laravel-konekin.test`
3. Siap! 🎉

---

## 📝 Testing

### Register User Baru
1. Klik **"Daftar Sekarang"** di navbar
2. Pilih tipe: Creative Worker atau UMKM
3. Isi form dengan lengkap
4. Klik Register
5. Auto login & redirect ke dashboard

### Login
1. Klik **"Masuk"** di navbar
2. Gunakan email & password yang terdaftar
3. Redirect otomatis ke dashboard sesuai tipe user

### Update Profile
Di dashboard, click **"Edit Profil"** untuk update data profil

---

## 📦 Database Schema

### Users Table
```
- id (bigint, PK)
- name (string)
- email (string, unique)
- password (string, hashed)
- type (enum: creative_worker, umkm)
- phone (string, nullable)
- address (string, nullable)
- city (string, nullable)
- bio (text, nullable)
- email_verified_at (timestamp, nullable)
- remember_token (string, nullable)
- created_at, updated_at (timestamps)
```

---

## 🚀 Fitur yang Coming Soon

### Creative Worker
- 📁 Portfolio Management
- 💰 Earn Tracking
- ⭐ Rating & Reviews
- 🤝 UMKM Connections

### UMKM
- 📊 Project Management
- 👥 Browse Creative Workers
- 📝 Team Management
- 💼 Budget Management

---

## 🔗 Routes

### Public Routes
```
GET  /                          - Home (Landing page)
GET  /kreator                   - Browse Creative Workers
GET  /umkm                      - Browse UMKM Projects
GET  /tentang-kami              - About Us
```

### Authentication Routes
```
GET  /auth/register             - Register form
POST /auth/register             - Process register
GET  /auth/login                - Login form
POST /auth/login                - Process login
POST /auth/logout               - Logout (protected)
```

### Dashboard Routes (Protected)
```
GET  /dashboard/creative        - Creative Worker Dashboard
GET  /dashboard/umkm            - UMKM Dashboard
PUT  /profile/update            - Update Profile (both types)
```

---

## 💡 Architecture

### MVC Pattern
- **Models**: `app/Models/User.php`
- **Controllers**: 
  - `app/Http/Controllers/AuthController.php` - Register & Login
  - `app/Http/Controllers/DashboardController.php` - Dashboard logic
- **Views**:
  - `resources/views/auth/` - Register & Login pages
  - `resources/views/dashboard/` - Dashboard pages

### Database Driver
- **Development**: MySQL via Laragon
- **Future**: Can migrate to MongoDB for scaling

---

## 🐛 Troubleshooting

### Error: "Route not found"
- Clear cache: `artisan.bat config:clear`

### Database connection error
- Pastikan MySQL service running di Laragon
- Check `.env` file untuk DB credentials

### Extensions missing
- Gunakan full path: `D:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan [command]`
- Jangan pakai `php artisan` dari command line biasa

---

## 📧 User Types

### Creative Worker
- Tujuan: Mencari project dari UMKM
- Profil: Nama, Contact, Biodata, Kota
- Future: Portfolio, Rating, Earnings

### UMKM
- Tujuan: Mencari talent untuk project
- Profil: Nama Usaha, Contact, Alamat, Deskripsi
- Future: Project posting, Budget management

---

## 🎯 Next Steps

1. ✅ Add project/job posting system
2. ✅ Add messaging/chat system
3. ✅ Add payment integration
4. ✅ Add review & rating system
5. ✅ Add portfolio display (Creative Workers)
6. ✅ Add matching algorithm
7. ✅ Add notification system

---

**Happy Coding!** 🚀
Jika ada pertanyaan atau issue, silakan tanyakan!
