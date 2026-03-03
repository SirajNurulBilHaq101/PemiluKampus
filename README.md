# 🗳️ PemiluKampus — Sistem E-Voting Kampus

Aplikasi **e-voting berbasis web** untuk pemilihan kampus (BEM, Himpunan, dll). Dibangun dengan **Laravel 12**, **Tailwind CSS 4**, dan **DaisyUI**, menggunakan arsitektur **Service Layer** untuk memisahkan business logic dari controller.

---

## ✨ Fitur Utama

### 👨‍🎓 Mahasiswa
- **Dashboard** dengan _quick count_ real-time (bar chart & donut chart)
- **Voting** — pilih kandidat pada event yang sedang aktif
- **Scope Prodi** — hanya melihat event yang sesuai program studi
- **Visi & Misi** — lihat detail kandidat sebelum memilih
- **Satu suara per event** — tidak bisa vote dua kali

### 🔧 Admin
- **Kelola Event** — CRUD event dengan scope program studi (multi-select per fakultas)
- **Kelola Kandidat** — CRUD kandidat lengkap dengan foto, visi, misi
- **Kelola User** — lihat daftar pengguna terdaftar
- **Log Suara** — riwayat suara masuk (tanpa identitas pemilih = **rahasia**)

### 🔒 Keamanan
- **Role-based access** — `mahasiswa`, `panitia`, `admin`
- **Voting rahasia** — log suara tidak menampilkan identitas pemilih
- **Validasi prodi** — mahasiswa hanya bisa vote di event sesuai program studinya

---

## 🛠️ Tech Stack

| Layer         | Teknologi                          |
|---------------|------------------------------------|
| Backend       | Laravel 12 (PHP 8.2+)             |
| Frontend      | Blade + Tailwind CSS 4 + DaisyUI  |
| Build Tool    | Vite 7                            |
| Charting      | Chart.js 4                        |
| DataTable     | jQuery DataTables                  |
| Database      | MySQL / SQLite                     |

---

## 📁 Struktur Direktori

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── EventController.php        # CRUD event + scope prodi
│   │   ├── CandidateController.php    # CRUD kandidat
│   │   ├── UserController.php         # Lihat daftar user
│   │   └── VoteLogController.php      # Log suara (tanpa identitas)
│   ├── Auth/AuthController.php        # Login / Logout
│   ├── DashboardController.php        # Dashboard + quick count
│   ├── ProfileController.php          # Halaman profil
│   └── VoteController.php             # Flow voting mahasiswa
├── Models/
│   ├── User.php                       # + relasi studyProgram
│   ├── Event.php                      # + relasi studyPrograms, isAccessibleBy()
│   ├── Candidate.php
│   ├── Vote.php
│   ├── Faculty.php
│   └── StudyProgram.php
└── Services/
    ├── AuthService.php
    ├── EventService.php               # + sync prodi, getFacultiesWithPrograms
    ├── VoteService.php                # + filter event by prodi
    ├── VoteLogService.php
    ├── CandidateService.php
    └── UserService.php
```

---

## 🗄️ Database Schema

```
faculties
├── id, name

study_programs
├── id, faculty_id (FK), name

users
├── id, name, email, role, study_program_id (FK), password

events
├── id, name, description, start_date, end_date, status, created_by (FK)

event_study_program (pivot)
├── event_id (FK), study_program_id (FK)

candidates
├── id, event_id (FK), name, photo, vision, mission, candidate_number

votes
├── id, event_id (FK), candidate_id (FK), user_id (FK)
└── UNIQUE(event_id, user_id)
```

---

## 🚀 Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & npm
- MySQL / SQLite

### Langkah-langkah

```bash
# 1. Clone repository
git clone <repository-url>
cd pemilu

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_DATABASE=pemilu
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migrasi & seeder
php artisan migrate:fresh --seed

# 6. Jalankan development server
php artisan serve
npm run dev
```

Atau jalankan sekaligus:

```bash
composer dev
```

---

## 👤 Akun Demo (Seeder)

| Role       | Email                  | Password   | Prodi               |
|------------|------------------------|------------|----------------------|
| Admin      | `admin@admin.com`      | `admin`    | —                    |
| Panitia    | `panitia@panitia.com`  | `panitia`  | —                    |
| Mahasiswa  | `mhs@mhs.com`         | `mhs`      | Teknik Informatika   |
| Mahasiswa  | `siti@mhs.com`         | `mhs`      | Manajemen            |
| Mahasiswa  | `dewi@mhs.com`        | `mhs`      | Teknik Sipil         |
| Mahasiswa  | `budi@mhs.com`        | `mhs`      | Akuntansi            |

---

## 📐 Arsitektur

Aplikasi menggunakan **Service Layer Architecture**:

```
Request → Controller → Service → Model → Database
                ↓
              View (Blade)
```

- **Controller** — handle HTTP request/response, validasi input
- **Service** — business logic (create, update, filter, validasi akses)
- **Model** — Eloquent ORM, relasi antar tabel
- **View** — Blade template dengan komponen reusable (`x-layout`, `x-aside`)

---

## 📄 Lisensi

MIT License
