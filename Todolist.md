

# 🚀 Todolist Media Monitoring DPRD Banten
### Versi Revisi — Berdasarkan Kondisi Aktual Kode

> **Prinsip urutan**: Backend dulu → Database lengkap → Admin Panel → API → Mobile UI → AI → Integrasi → Polish
>
> Tandai `[x]` saat selesai, `[/]` saat sedang dikerjakan.

---

# ✅ FASE 1 — Verifikasi Setup ~~(Cek Dulu Bisa Jalan)~~

> **STATUS: ✅ SELESAI**

## Backend Laravel

* [x] Jalankan `php artisan serve` — pastikan tidak error
* [x] Cek koneksi PostgreSQL (`media_monitoring` DB sudah ada?)
* [x] Jalankan `php artisan migrate` — pastikan semua migrasi yang ada berhasil
* [x] Login ke Filament Admin (`/admin`) — pastikan bisa akses
* [x] Buat user admin pertama via `php artisan make:filament-user`

## Mobile Flutter

* [x] Jalankan `flutter doctor` — pastikan tidak ada error
* [x] Jalankan emulator Android
* [x] Jalankan `flutter run` — pastikan counter app default bisa berjalan

## Checklist Lingkungan

* [x] PHP 8.3 tersedia
* [x] Composer tersedia
* [x] Node.js & npm tersedia
* [x] Flutter SDK tersedia
* [x] PostgreSQL berjalan di port 5432
* [x] Python 3.10+ tersedia (untuk AI nanti)

---

# 🗄️ FASE 2 — Lengkapi Database ~~(Migrasi Semua Tabel)~~

> **STATUS: ✅ SELESAI** — 42 tabel berhasil di-migrate ke PostgreSQL
> Schema dari `docs/schema_database.md` terpenuhi 100% + bonus kolom tambahan

## Migrations — ✅ SELESAI SEMUA (32 migration)

### Group: Security & Audit
* [x] Migration: `audit_logs` ✅
* [x] Migration: `login_logs` ✅

### Group: AI & Analytics
* [x] Migration: `ai_model_logs` ✅
* [x] Migration: `daily_stats` ✅
* [x] Migration: `weekly_stats` ✅
* [x] Migration: `media_rankings` ✅
* [x] Migration: `sentiment_trends` ✅

### Group: Topic & Entity
* [x] Migration: `topics` ✅
* [x] Migration: `news_topics` (pivot) ✅
* [x] Migration: `entities` ✅
* [x] Migration: `news_entities` (pivot) ✅

### Group: Geo Banten
* [x] Migration: `regions` ✅
* [x] Migration: `news_regions` (pivot) ✅
* [x] Migration: `geo_locations` ✅
* [x] Migration: `heatmap_data` ✅

### Group: Notifikasi & Alert
* [x] Migration: `app_notifications` ✅ (nama beda, hindari konflik Laravel)
* [x] Migration: `alert_rules` ✅
* [x] Migration: `alert_logs` ✅

### Group: Reporting
* [x] Migration: `reports` ✅
* [x] Migration: `report_items` ✅
* [x] Migration: `export_logs` ✅

### Bug yang difix di migration existing
* [x] Fix typo `constraid` → `constrained` di `news` migration ✅
* [x] Fix enum typo `procesing/erorr` di `keyword_runs` migration ✅
* [x] Fix tipe `integer` → `text` di `crawled_logs.error_message` ✅

## Models Eloquent — ✅ SELESAI SEMUA (24 model)

### Model baru dibuat:
* [x] `AuditLog` ✅
* [x] `LoginLog` ✅
* [x] `AiModelLog` ✅
* [x] `DailyStat` ✅
* [x] `WeeklyStat` ✅
* [x] `MediaRanking` ✅
* [x] `SentimentTrend` ✅
* [x] `Topic` ✅
* [x] `Entity` ✅
* [x] `Region` ✅
* [x] `GeoLocation` ✅
* [x] `HeatmapData` ✅
* [x] `AppNotification` ✅
* [x] `AlertRule` ✅
* [x] `AlertLog` ✅
* [x] `Report` ✅
* [x] `ExportLog` ✅

### Model existing diupdate (relasi ditambahkan):
* [x] `User` — tambah relasi keywords, notifications, auditLogs, loginLogs ✅
* [x] `Keyword` — tambah semua relasi lengkap + SoftDeletes ✅
* [x] `News` — tambah 7 relasi (keyword, source, sentiment, topics, entities, regions, reports) ✅
* [x] `Sentiment` — tambah relasi + helper scopes ✅
* [x] `KeywordRun` — fix bug HasFactory + Keyword::class ✅
* [x] `CrawledLog` — fix nama kolom + tambah relasi ✅
* [x] `NewsSource` — tambah relasi news + rankings ✅

## Seeder Data Awal — ✅ SELESAI
* [x] Seeder: `RolesAndPermissionsSeeder` (Admin=13 perms, Operator=10, Pimpinan=4) ✅
* [x] Seeder: `TopicsSeeder` (10 topik: Politik, Ekonomi, Hukum, ...) ✅
* [x] Seeder: `RegionsSeeder` (9 wilayah Banten + koordinat GPS) ✅
* [x] Seeder: `NewsSourcesSeeder` (8 sumber: Google News, Kompas, Tribun Banten, dll) ✅
* [x] Seeder: `AdminUserSeeder` (3 akun default: admin/operator/pimpinan) ✅

---

# 🔐 FASE 3 — Keamanan & Auth ~~(Keamanan & Auth)~~

> **STATUS: ✅ SELESAI** — Auth berjalan, ditest via curl

## Laravel Sanctum (untuk Mobile API)
* [x] Install Sanctum v4.3.2 via `php artisan install:api` ✅
* [x] `personal_access_tokens` table sudah dibuat ✅
* [x] Middleware `auth:sanctum` aktif di protected routes ✅
* [x] Endpoint: `POST /api/v1/auth/login` → return token + role + permissions ✅
* [x] Endpoint: `POST /api/v1/auth/logout` → revoke token ✅
* [x] Endpoint: `GET /api/v1/auth/me` → data user login ✅

## Spatie Permission (RBAC)
* [x] Assign 13 permissions ke role Admin ✅
* [x] Assign 10 permissions ke role Operator ✅
* [x] Assign 4 permissions ke role Pimpinan ✅
* [x] Route protected butuh `auth:sanctum` middleware ✅

## Keamanan Tambahan
* [x] Rate limiting 60 req/menit (di AppServiceProvider) ✅
* [x] Rate limiting 10 req/menit untuk endpoint login (anti brute-force) ✅
* [x] FormRequest `LoginRequest` untuk validasi input login ✅
* [x] Password min 8 karakter (validasi di LoginRequest) ✅
* [x] Auto-log setiap login ke `login_logs` (sukses & gagal) ✅
* [x] Format JSON error konsisten (`success`, `message`, `errors`) ✅
* [x] Token lama otomatis dihapus saat login baru (satu device = satu token) ✅

---

# 🖥️ FASE 4 — Admin Panel Filament ~~(Admin Panel)~~

> **STATUS: ✅ SELESAI** — 5 Resource + 1 Widget, 14 admin routes aktif

## Resource: User Management
* [x] Filament Resource: `UserResource` (CRUD user) ✅
* [x] Tampilkan: nama, email, role badge warna, tanggal buat ✅
* [x] Filter by role ✅
* [x] Assign role saat buat/edit user ✅

## Resource: Keyword Management
* [x] Filament Resource: `KeywordResource` (CRUD keyword) ✅
* [x] Tampilkan: keyword_text, region_scope, status, jumlah berita ✅
* [x] Toggle active/inactive keyword (tombol langsung di tabel) ✅

## Resource: News Management
* [x] Filament Resource: `NewsResource` (read-only list) ✅
* [x] Tampilkan: judul, sumber, keyword, sentimen badge (warna) ✅
* [x] Filter by: keyword, sumber berita ✅
* [x] Search by judul ✅
* [x] Aksi: tombol buka URL berita asli ✅

## Resource: Crawl Monitoring
* [x] Filament Resource: `CrawledLogResource` (read-only) ✅
* [x] Tampilkan status: sukses/gagal, total ambil/simpan, pesan error ✅
* [x] Auto-refresh polling setiap 30 detik ✅

## Resource: Report Management
* [x] Filament Resource: `ReportResource` ✅
* [x] Buat laporan manual (pilih keyword + periode) ✅
* [x] Daftar laporan + status (draft/generated/exported) ✅

## Dashboard Widget
* [x] `SentimentOverviewWidget` — 4 stat cards ✅
  - Berita Hari Ini, Positif %, Negatif %, Keyword Aktif

---

# 🌐 FASE 5 — REST API untuk Mobile ~~(REST API)~~

> **STATUS: ✅ SELESAI** — 21 endpoint aktif, semua ditest dengan curl

## Auth API ✅ (dibuat di FASE 3)
* [x] `POST /api/v1/auth/login` ✅
* [x] `POST /api/v1/auth/logout` ✅
* [x] `GET /api/v1/auth/me` ✅

## Keyword API ✅
* [x] `GET /api/v1/keywords` — list keyword user ✅
* [x] `POST /api/v1/keywords` — subscribe keyword baru ✅
* [x] `DELETE /api/v1/keywords/{id}` — unsubscribe keyword ✅
* [x] `POST /api/v1/keywords/{id}/analyze` — trigger crawl manual ✅

## News API ✅
* [x] `GET /api/v1/news` — list berita + filter 5 parameter ✅
* [x] `GET /api/v1/news/{id}` — detail berita + data AI ✅

## Dashboard API ✅
* [x] `GET /api/v1/dashboard/stats` — ringkasan sentimen hari ini ✅
* [x] `GET /api/v1/dashboard/chart` — tren grafik harian ✅
* [x] `GET /api/v1/dashboard/top-media` — top 5 media terbanyak ✅
* [x] `GET /api/v1/dashboard/top-issues` — top 5 isu trending ✅

## Geo API ✅
* [x] `GET /api/v1/geo/regions` — 9 wilayah Banten + koordinat GPS ✅
* [x] `GET /api/v1/geo/heatmap` — data intensitas sentimen per wilayah ✅

## Laporan API ✅
* [x] `GET /api/v1/reports` — list laporan ✅
* [x] `POST /api/v1/reports` — buat laporan baru ✅
* [x] `GET /api/v1/reports/{id}` — detail laporan + list berita ✅

## Notifikasi API ✅
* [x] `GET /api/v1/notifications` — list notif + unread count ✅
* [x] `PUT /api/v1/notifications/{id}/read` — tandai satu dibaca ✅
* [x] `PUT /api/v1/notifications/read-all` — tandai semua dibaca ✅

---

# 📱 FASE 6 — Mobile UI Flutter

> Tujuan: Semua halaman UI sudah jadi (dummy dulu, tanpa API).
> Tambahkan package yang dibutuhkan ke `pubspec.yaml` dulu.

## Setup Dependencies Flutter
* [x] Tambah ke pubspec.yaml:
  - `http` atau `dio` (HTTP client)
  - `provider` atau `riverpod` (state management)
  - `flutter_secure_storage` (simpan token)
  - `fl_chart` (grafik sentiment)
  - `google_maps_flutter` atau `flutter_map` (peta)
  - `firebase_messaging` (push notification)
  - `pdf` + `printing` (buat/tampilkan PDF)
  - `intl` (format tanggal)
  - `shimmer` (loading skeleton)

## Struktur Folder Flutter
```
lib/
  core/
    constants/
    theme/
    utils/
  data/
    models/
    repositories/
    services/      ← HTTP client
  presentation/
    screens/
    widgets/
    providers/
```
* [x] Buat struktur folder di atas

## Halaman: Auth
* [x] Screen: `LoginScreen` — form email + password, tombol login
* [x] Handling error login (wrong credentials)
* [x] Simpan token ke secure storage

## Halaman: Home / Dashboard
* [x] Screen: `HomeScreen` dengan BottomNavigationBar (5 tab)
* [x] Widget: Summary card (total berita, positif/negatif/netral)
* [x] Widget: Grafik sentimen 7 hari terakhir (line chart)
* [x] Widget: Top 5 media paling aktif
* [x] Widget: Top 5 isu trending

## Halaman: Monitoring
* [x] Screen: `MonitoringScreen` — list berita dengan filter
* [x] Filter: by keyword, by sentiment (chip selector)
* [x] Filter: by tanggal (date picker)
* [x] Card berita: judul, sumber, tanggal, badge sentiment (warna)
* [x] Screen: `NewsDetailScreen` — detail berita + hasil AI

## Halaman: Peta Banten
* [x] Screen: `MapScreen` — peta interaktif Banten
* [x] Tampilkan heatmap per kab/kota berdasarkan intensitas sentimen
* [x] Legend warna: merah (negatif), kuning (netral), hijau (positif)
* [x] Tap wilayah → popup info berita di daerah itu

## Halaman: Laporan
* [x] Screen: `ReportScreen` — list laporan
* [x] Buat laporan baru (pilih keyword + rentang tanggal)
* [x] Tombol download PDF
* [x] Preview PDF di dalam app

## Halaman: Profil
* [x] Screen: `ProfileScreen` — info user (nama, email, role)
* [x] Tombol logout

---

# 🤖 FASE 7 — Python AI Microservice

> Tujuan: Layanan AI untuk analisis sentimen bisa menerima request dari Laravel.

## Setup Project Python
* [ ] Buat folder `ai-service/` di root project
* [ ] Setup virtual environment + requirements.txt
* [ ] Framework: FastAPI + uvicorn

## Model Sentimen Indonesia
* [ ] Download/setup IndoBERT (HuggingFace)
* [ ] Endpoint: `POST /analyze` → terima teks → return sentimen + skor
* [ ] Endpoint: `GET /health` → health check

## Integrasi dengan Laravel
* [ ] Laravel kirim request ke Python saat berita baru masuk
* [ ] Simpan hasil ke tabel `sentiments` + `ai_model_logs`

---

# 🔗 FASE 8 — Integrasi End-to-End

> Tujuan: Flutter ↔ Laravel ↔ Python semua terhubung.

* [ ] Flutter bisa login → dapat token
* [ ] Flutter fetch berita → data tampil di UI
* [ ] Crawling berita manual dari admin panel → berita masuk
* [ ] AI analisis berita baru → sentimen tersimpan
* [ ] Flutter dashboard update real-time

---

# 🚀 FASE 9 — Deploy & Polish

* [ ] Setup Docker Compose (Laravel + PostgreSQL + Python)
* [ ] Environment production (.env.production)
* [ ] Build APK Flutter release
* [ ] Testing end-to-end
* [ ] Dokumentasi API (Postman Collection / Swagger)
