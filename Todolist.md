

# 🚀 Todolist Media Monitoring DPRD Banten
### Versi Revisi — Berdasarkan Kondisi Aktual Kode

> **Prinsip urutan**: Backend dulu → Database lengkap → Admin Panel → API → Mobile UI → AI → Integrasi → Polish
>
> Tandai `[x]` saat selesai, `[/]` saat sedang dikerjakan.

---

# ✅ FASE 1 — Verifikasi Setup (Cek Dulu Bisa Jalan)

> Tujuan: Pastikan semua tools bisa berjalan sebelum mulai coding fitur.

## Backend Laravel

* [ ] Jalankan `php artisan serve` — pastikan tidak error
* [ ] Cek koneksi PostgreSQL (`media_monitoring` DB sudah ada?)
* [ ] Jalankan `php artisan migrate` — pastikan semua migrasi yang ada berhasil
* [ ] Login ke Filament Admin (`/admin`) — pastikan bisa akses
* [ ] Buat user admin pertama via `php artisan make:filament-user`

## Mobile Flutter

* [ ] Jalankan `flutter doctor` — pastikan tidak ada error
* [ ] Jalankan emulator Android
* [ ] Jalankan `flutter run` — pastikan counter app default bisa berjalan

## Checklist Lingkungan

* [ ] PHP 8.3 tersedia
* [ ] Composer tersedia
* [ ] Node.js & npm tersedia
* [ ] Flutter SDK tersedia
* [ ] PostgreSQL berjalan di port 5432
* [ ] Python 3.10+ tersedia (untuk AI nanti)

---

# 🗄️ FASE 2 — Lengkapi Database (Migrasi Semua Tabel)

> Tujuan: Semua tabel dari `docs/schema_database.md` harus ada di DB.
>
> Tabel yang sudah ada: `users`, `keywords`, `news`, `sentiments`, `keyword_runs`, `news_sources`, `crawled_logs`, `permission_tables`, `user_keywords`

## Tabel yang Masih Kurang — Harus Dibuat

### Group: Security & Audit
* [ ] Migration: `audit_logs` (user_id, action, table_name, description)
* [ ] Migration: `login_logs` (user_id, ip_address, device, login_time)

### Group: AI & Analytics
* [ ] Migration: `ai_model_logs` (news_id, model_a_score, model_b_score, model_c_score, final_score)
* [ ] Migration: `daily_stats` (keyword_id, date, total_news, positive, neutral, negative)
* [ ] Migration: `weekly_stats` (keyword_id, week_start, week_end, summary)
* [ ] Migration: `media_rankings` (source_id, score)
* [ ] Migration: `sentiment_trends` (keyword_id, date, sentiment_distribution JSON)

### Group: Topic & Entity
* [ ] Migration: `topics` (name)
* [ ] Migration: `news_topics` (pivot: news_id, topic_id)
* [ ] Migration: `entities` (name, type: person/org/place)
* [ ] Migration: `news_entities` (pivot: news_id, entity_id)

### Group: Geo Banten
* [ ] Migration: `regions` (name: Serang, Tangerang, Cilegon, dll)
* [ ] Migration: `news_regions` (pivot: news_id, region_id)
* [ ] Migration: `geo_locations` (region_id, lat, lng)
* [ ] Migration: `heatmap_data` (region_id, intensity_score)

### Group: Notifikasi & Alert
* [ ] Migration: `notifications` (user_id, title, message, type, is_read)
* [ ] Migration: `alert_rules` (keyword_id, condition, action)
* [ ] Migration: `alert_logs` (alert_rule_id, triggered_at)

### Group: Reporting
* [ ] Migration: `reports` (keyword_id, title, period_start, period_end)
* [ ] Migration: `report_items` (report_id, news_id)
* [ ] Migration: `export_logs` (report_id, format, exported_at)

## Model Eloquent yang Masih Kurang
* [ ] Model: `AuditLog`
* [ ] Model: `LoginLog`
* [ ] Model: `AiModelLog`
* [ ] Model: `DailyStat`
* [ ] Model: `WeeklyStat`
* [ ] Model: `MediaRanking`
* [ ] Model: `SentimentTrend`
* [ ] Model: `Topic`
* [ ] Model: `Entity`
* [ ] Model: `Region`
* [ ] Model: `GeoLocation`
* [ ] Model: `HeatmapData`
* [ ] Model: `Notification` (custom, bukan Laravel built-in)
* [ ] Model: `AlertRule`
* [ ] Model: `AlertLog`
* [ ] Model: `Report`
* [ ] Model: `ReportItem`
* [ ] Model: `ExportLog`

## Seeder Data Awal
* [ ] Seeder: `RolesSeeder` (Admin, Operator, Pimpinan)
* [ ] Seeder: `TopicsSeeder` (Politik, Ekonomi, Hukum, Pendidikan, Infrastruktur, Kesehatan, Anggaran)
* [ ] Seeder: `RegionsSeeder` (8 kab/kota Banten + koordinat)
* [ ] Seeder: `NewsSourcesSeeder` (Google News RSS, Kompas, Tribun, dll)
* [ ] Seeder: `AdminUserSeeder` (akun admin default)

---

# 🔐 FASE 3 — Keamanan & Auth

> Tujuan: Sistem auth yang aman untuk mobile dan admin panel.

## Laravel Sanctum (untuk Mobile API)
* [ ] Install Sanctum: `composer require laravel/sanctum`
* [ ] Publish config Sanctum
* [ ] Setup middleware `auth:sanctum` di API routes
* [ ] Endpoint: `POST /api/login` → return token
* [ ] Endpoint: `POST /api/logout` → revoke token
* [ ] Endpoint: `GET /api/me` → data user login

## Spatie Permission (RBAC)
* [ ] Assign permission ke role Admin
* [ ] Assign permission ke role Operator
* [ ] Assign permission ke role Pimpinan
* [ ] Middleware `role:admin` untuk endpoint admin-only
* [ ] Middleware `role:operator` untuk endpoint operator

## Keamanan Tambahan
* [ ] Rate limiting di API routes (max 60 req/menit)
* [ ] Validasi input di semua request (FormRequest classes)
* [ ] Password policy (min 8 karakter, huruf + angka)
* [ ] Log setiap login ke `login_logs`
* [ ] Log setiap aksi penting ke `audit_logs`

---

# 🖥️ FASE 4 — Admin Panel Filament

> Tujuan: Admin bisa kelola sistem dari web tanpa harus ke database langsung.

## Resource: User Management
* [ ] Filament Resource: `UserResource` (CRUD user)
* [ ] Tampilkan: nama, email, role, tanggal buat
* [ ] Filter by role
* [ ] Assign role saat buat/edit user

## Resource: Keyword Management
* [ ] Filament Resource: `KeywordResource` (CRUD keyword)
* [ ] Tampilkan: keyword_text, region_scope, status, pembuat
* [ ] Toggle active/inactive keyword
* [ ] Tombol "Jalankan Crawling" manual

## Resource: News Management
* [ ] Filament Resource: `NewsResource` (read-only list)
* [ ] Tampilkan: judul, sumber, tanggal, sentiment badge (warna)
* [ ] Filter by: keyword, sumber, sentiment, tanggal
* [ ] Search by judul

## Resource: Crawl Monitoring
* [ ] Filament Resource: `CrawledLogResource` (read-only)
* [ ] Tampilkan status crawling: success/fail, jumlah ambil/simpan

## Resource: Report Management
* [ ] Filament Resource: `ReportResource`
* [ ] Buat laporan manual (pilih keyword + periode)
* [ ] Daftar laporan + status export

## Dashboard Filament
* [ ] Widget: Total berita hari ini
* [ ] Widget: Breakdown sentimen (pie chart)
* [ ] Widget: Keyword paling aktif
* [ ] Widget: Alert terbaru

---

# 🌐 FASE 5 — REST API untuk Mobile

> Tujuan: Semua endpoint yang dibutuhkan Flutter sudah tersedia.

## Auth API
* [ ] `POST /api/v1/auth/login`
* [ ] `POST /api/v1/auth/logout`
* [ ] `GET /api/v1/auth/me`

## News API
* [ ] `GET /api/v1/news` (list berita + filter keyword/sentimen/tanggal)
* [ ] `GET /api/v1/news/{id}` (detail berita)

## Keyword API
* [ ] `GET /api/v1/keywords` (list keyword milik user)
* [ ] `POST /api/v1/keywords` (tambah keyword baru)
* [ ] `DELETE /api/v1/keywords/{id}`
* [ ] `POST /api/v1/keywords/{id}/analyze` (trigger crawling manual)

## Dashboard API
* [ ] `GET /api/v1/dashboard/stats` (total, sentimen breakdown, tren)
* [ ] `GET /api/v1/dashboard/chart` (data grafik harian/mingguan)
* [ ] `GET /api/v1/dashboard/top-media` (ranking media)
* [ ] `GET /api/v1/dashboard/top-issues` (isu paling banyak)

## Geo API
* [ ] `GET /api/v1/geo/heatmap` (data heatmap per wilayah Banten)
* [ ] `GET /api/v1/geo/regions` (list wilayah + koordinat)

## Laporan API
* [ ] `GET /api/v1/reports` (list laporan)
* [ ] `GET /api/v1/reports/{id}` (detail laporan)
* [ ] `POST /api/v1/reports/{id}/export/pdf` (generate & download PDF)

## Notifikasi API
* [ ] `GET /api/v1/notifications` (list notif user)
* [ ] `PUT /api/v1/notifications/{id}/read` (tandai sudah dibaca)

---

# 📱 FASE 6 — Mobile UI Flutter

> Tujuan: Semua halaman UI sudah jadi (dummy dulu, tanpa API).
> Tambahkan package yang dibutuhkan ke `pubspec.yaml` dulu.

## Setup Dependencies Flutter
* [ ] Tambah ke pubspec.yaml:
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
* [ ] Buat struktur folder di atas

## Halaman: Auth
* [ ] Screen: `LoginScreen` — form email + password, tombol login
* [ ] Handling error login (wrong credentials)
* [ ] Simpan token ke secure storage

## Halaman: Home / Dashboard
* [ ] Screen: `HomeScreen` dengan BottomNavigationBar (5 tab)
* [ ] Widget: Summary card (total berita, positif/negatif/netral)
* [ ] Widget: Grafik sentimen 7 hari terakhir (line chart)
* [ ] Widget: Top 5 media paling aktif
* [ ] Widget: Top 5 isu trending

## Halaman: Monitoring
* [ ] Screen: `MonitoringScreen` — list berita dengan filter
* [ ] Filter: by keyword, by sentiment (chip selector)
* [ ] Filter: by tanggal (date picker)
* [ ] Card berita: judul, sumber, tanggal, badge sentiment (warna)
* [ ] Screen: `NewsDetailScreen` — detail berita + hasil AI

## Halaman: Peta Banten
* [ ] Screen: `MapScreen` — peta interaktif Banten
* [ ] Tampilkan heatmap per kab/kota berdasarkan intensitas sentimen
* [ ] Legend warna: merah (negatif), kuning (netral), hijau (positif)
* [ ] Tap wilayah → popup info berita di daerah itu

## Halaman: Laporan
* [ ] Screen: `ReportScreen` — list laporan
* [ ] Buat laporan baru (pilih keyword + rentang tanggal)
* [ ] Tombol download PDF
* [ ] Preview PDF di dalam app

## Halaman: Profil
* [ ] Screen: `ProfileScreen` — info user (nama, email, role)
* [ ] Tombol logout
* [ ] Info versi aplikasi

## Notifikasi
* [ ] Setup Firebase Messaging
* [ ] Handle notif saat app foreground
* [ ] Handle notif saat app background/killed
* [ ] Screen: `NotificationScreen` — riwayat notifikasi

---

# 🕷️ FASE 7 — Crawling Engine

> Tujuan: Sistem bisa otomatis ambil berita dari RSS.

## News Crawler Service
* [ ] Buat `CrawlerService` class di Laravel
* [ ] Method: `crawlByKeyword(Keyword $keyword)`
* [ ] Fetch Google News RSS: `https://news.google.com/rss/search?q={keyword}&hl=id&gl=ID`
* [ ] Parse XML feed (SimpleXML atau library)
* [ ] Filter berita duplikat (cek `hash` dari URL)
* [ ] Simpan ke tabel `news`
* [ ] Log hasil ke tabel `crawled_logs`
* [ ] Update `keyword_runs` status

## Queue Job
* [ ] Buat Job: `CrawlNewsJob`
* [ ] Dispatch job saat keyword dibuat atau dianalisis manual
* [ ] Handle failed job (retry 3x, lalu catat error)

## Scheduler Otomatis
* [ ] Setup Laravel Scheduler
* [ ] Crawl semua keyword aktif setiap 1 jam
* [ ] Generate `daily_stats` setiap tengah malam
* [ ] Kirim alert jika ada berita negatif baru

---

# 🤖 FASE 8 — AI Sentiment Service (Python)

> Tujuan: Python microservice yang bisa menerima teks dan mengembalikan label sentimen.

## Setup Python Service
* [ ] Buat folder `ai-service/` di root proyek
* [ ] Setup virtual environment Python
* [ ] Buat `requirements.txt`:
  - `flask` atau `fastapi` (REST API)
  - `scikit-learn` (SVM/Naive Bayes)
  - `nltk` atau `Sastrawi` (preprocessing Bahasa Indonesia)
  - `transformers` + `torch` (IndoBERT)
  - `numpy`, `pandas`
* [ ] Buat file `app.py` (entry point API)

## Preprocessing
* [ ] Implementasi text preprocessing Indonesia:
  - Lowercase
  - Hapus tanda baca & angka tidak penting
  - Stopword removal (bahasa Indonesia)
  - Stemming (Sastrawi)

## Model A: Lexicon-Based
* [ ] Siapkan kamus kata positif Indonesia
* [ ] Siapkan kamus kata negatif Indonesia
* [ ] Implementasi scoring lexicon
* [ ] Output: score 0.0 - 1.0

## Model B: Machine Learning (SVM)
* [ ] Siapkan dataset sentiment berita Indonesia (atau cari dataset publik)
* [ ] Training SVM dengan TF-IDF features
* [ ] Simpan model trained ke file `.pkl`
* [ ] Load model saat service start

## Model C: Deep Learning (IndoBERT)
* [ ] Download model IndoBERT dari HuggingFace
* [ ] Fine-tune untuk klasifikasi 3 kelas (opsional jika sudah ada pretrained)
* [ ] Implementasi inference

## Ensemble
* [ ] Gabungkan skor 3 model (Lexicon 20% + SVM 40% + BERT 40%)
* [ ] Output final: `{ sentiment: "positive/neutral/negative", confidence: 0.75 }`

## API Endpoint Python
* [ ] `POST /analyze` → input: `{ text: "..." }` → output: sentiment + score
* [ ] `GET /health` → status service

## Integrasi Laravel → Python
* [ ] Buat `AiService` class di Laravel
* [ ] Call Python API setelah crawling selesai
* [ ] Simpan hasil ke tabel `sentiments` dan `ai_model_logs`
* [ ] Handle jika Python service down (graceful degradation)

---

# 📊 FASE 9 — Dashboard & Analytics

> Tujuan: Data sentimen bisa divisualisasikan secara informatif.

## Backend Analytics
* [ ] Buat command/job: `GenerateDailyStats` (isi tabel `daily_stats`)
* [ ] Buat command/job: `GenerateWeeklyStats` (isi tabel `weekly_stats`)
* [ ] Hitung `media_rankings` berdasarkan proporsi sentimen
* [ ] Hitung `sentiment_trends` per keyword per hari
* [ ] Hitung `heatmap_data` per wilayah Banten

## API Analytics (sudah masuk Fase 5, pastikan data real)
* [ ] Dashboard API sudah return data real dari DB
* [ ] Geo API sudah return heatmap data real

## Mobile: Grafik & Visualisasi
* [ ] Grafik line chart sentimen harian
* [ ] Pie chart distribusi positif/netral/negatif
* [ ] Bar chart top media
* [ ] Heatmap peta Banten (warna per wilayah)

---

# 📄 FASE 10 — Laporan & Notifikasi

> Tujuan: Fitur akhir yang langsung berguna untuk pimpinan.

## Generate Laporan PDF
* [ ] Install dompdf: `composer require barryvdh/laravel-dompdf`
* [ ] Buat template blade untuk laporan
* [ ] Isi: periode, keyword, ringkasan sentimen, tabel berita, grafik
* [ ] Endpoint: generate PDF dan simpan ke storage
* [ ] Catat ke `export_logs`

## Firebase Push Notification
* [ ] Setup Firebase project
* [ ] Install Firebase SDK ke Flutter
* [ ] Install `laravel-notification-channels/fcm` di Laravel
* [ ] Trigger notif saat ada berita negatif baru (cek alert_rules)
* [ ] Trigger notif saat crawling selesai

## Alert System
* [ ] Cek `alert_rules` setelah setiap crawling selesai
* [ ] Jika ditemukan kondisi (misal: > 5 berita negatif baru), kirim notif
* [ ] Simpan ke `alert_logs` dan `notifications`

## Ringkasan Bulanan Otomatis
* [ ] Scheduler: setiap tanggal 1 generate laporan bulan lalu otomatis
* [ ] Kirim notif ke semua Operator & Pimpinan

---

# 🧪 FASE 11 — Testing & QA

> Fase tambahan yang krusial sebelum presentasi/submit.

## Backend Testing
* [ ] Test semua endpoint API dengan Postman (simpan collection)
* [ ] Test skenario: login gagal, token expired, akses tanpa role
* [ ] Test crawling dengan keyword nyata
* [ ] Test AI service dengan berbagai teks berita

## Mobile Testing
* [ ] Tes di emulator Android
* [ ] Tes semua flow: login → monitoring → laporan → logout
* [ ] Tes notifikasi masuk saat app background

## Integrasi End-to-End
* [ ] Keyword dibuat di mobile → crawl → AI → hasil muncul di dashboard
* [ ] Laporan bisa di-generate dan di-download sebagai PDF

---

# 📝 CATATAN PENTING

> **Urutan yang tidak boleh dibalik:**
> 1. Database harus LENGKAP dulu sebelum bikin API
> 2. API harus JADI dulu sebelum integrasikan ke Flutter
> 3. Crawler harus JALAN dulu sebelum integrasikan AI
> 4. AI service harus BISA HIT dulu sebelum connect ke Laravel

> **Penamaan Role yang dipakai:** Admin / Operator / Pimpinan
> (bukan admin/analyst/viewer — yang di schema_database.md itu salah ketik)

> **Database yang dipakai:** PostgreSQL (`media_monitoring`)
> **Driver Queue:** database (sudah di .env)
> **Driver Cache:** database (sudah di .env)
