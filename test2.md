Edited test.md
Viewed test.md:457-501

Oke, saya akan evaluasi lengkap — cocokkan konsep kamu dengan schema yang ada, lalu jawab semua pertanyaanmu.

---

## 1. Soal User & Akses — Ini Penting

Kamu bilang:
- **Tidak ada registrasi** — admin yang buat akun
- **Operator & Pimpinan pakai Mobile** — bukan Filament
- **Filament hanya untuk Admin/IT**

Ini benar secara arsitektur. Artinya ada **2 jalur akses terpisah:**

```
Filament Admin Panel
→ hanya bisa diakses Role: Admin / Super Admin

Mobile Flutter
→ diakses oleh: Operator, Pimpinan (via Sanctum token)
```

**Yang perlu diubah:** Sekarang Filament tidak ada pembatasan role. Harus ditambahkan guard di panel provider agar hanya Admin yang bisa masuk Filament. Operator login via API dapat token, tapi tidak bisa buka `/admin`.

---

## 2. Cocokkan Konsep vs Schema Saat Ini

### ✅ Yang Sudah Ada di Schema

| Fitur dari Konsep | Tabel yang Ada |
|---|---|
| Users, Roles, Permissions | `users`, Spatie tables |
| Login Logs | `login_logs` ✅ |
| Audit Logs | `audit_logs` ✅ |
| Keywords, User Keywords, Keyword Runs | `keywords`, `user_keywords`, `keyword_runs` ✅ |
| RSS Sources | `news_sources` ✅ |
| Crawled Logs | `crawled_logs` ✅ |
| News | `news` ✅ |
| Sentiments, AI Model Logs | `sentiments`, `ai_model_logs` ✅ |
| Daily Stats, Weekly Stats | `daily_stats`, `weekly_stats` ✅ |
| Media Rankings | `media_rankings` ✅ |
| Sentiment Trends | `sentiment_trends` ✅ |
| Regions, Geo Locations, Heatmap | `regions`, `geo_locations`, `heatmap_data` ✅ |
| Alert Rules, Alert Logs | `alert_rules`, `alert_logs` ✅ |
| Notifications | `app_notifications` ✅ |
| Reports, Export Logs | `reports`, `report_items`, `export_logs` ✅ |

### ❌ Yang Belum Ada di Schema (Perlu Ditambah)

**1. Keyword Groups** — konsep kamu: "DPRD Banten", "DPRD Provinsi Banten", "Dewan Banten" dianggap satu entitas

Perlu tambah 2 tabel:
- `keyword_groups` (id, name, description)
- `keyword_group_items` pivot (keyword_group_id, keyword_id)

**2. Deteksi Duplikat Berita** — `news` perlu kolom `is_duplicate` dan `duplicate_of` (FK ke news.id sendiri)

**3. `news_sources` perlu kolom tambahan** — `priority` (urutan crawl) dan `is_active` (enable/disable per sumber)

**4. Report Templates** — `report_templates` (id, name, structure JSON) untuk simpan template laporan yang bisa dipakai ulang

**5. System Monitoring** — Queue/Job/Scheduler monitor ini bukan dari database kita, tapi dari **Laravel Horizon** (package terpisah). Ini next phase.

---

## 3. Dashboard — Konsep yang Logis

Dashboard Filament harus jadi **halaman pertama yang langsung kasih gambaran situasi hari ini**. Bukan grafik kosong.

**Baris 1 — Status Operasional (real-time):**
- Crawler sedang berjalan: berapa keyword aktif running
- Crawler error hari ini: berapa yang gagal
- Alert belum ditangani: berapa alert menunggu tindakan
- AI backlog: berapa berita belum dianalisis

**Baris 2 — Angka Hari Ini:**
- Total berita masuk
- Breakdown sentimen: positif / netral / negatif + persentase
- Berita baru sejak 1 jam terakhir

**Baris 3 — Grafik:**
- Line chart: tren sentimen 7 hari (3 garis: positif, netral, negatif)
- Bar chart: berita per keyword hari ini

**Baris 4 — Tabel Cepat:**
- 5 berita terbaru (dengan badge warna sentimen)
- Status per keyword: terakhir crawl kapan, berapa dapat, sukses/gagal
- Ranking media hari ini: 3 teratas paling negatif

---

## 4. Struktur Navigasi Filament yang Diusulkan

Sesuai konsep kamu, ini versi yang cocok dengan schema:

```
Dashboard (Command Center)

── User Management
   ├── Users (CRUD - admin buat akun, tidak ada self-register)
   ├── Roles & Permissions (kelola RBAC)
   ├── Login Logs (read-only)
   └── Audit Logs (read-only)

── Keyword Management
   ├── Keywords (CRUD + toggle active)
   ├── Keyword Groups (NEW - gabung keyword serupa)
   ├── User Keywords (lihat siapa pakai keyword apa)
   └── Keyword Runs (riwayat & status crawling per keyword)

── Crawling Center
   ├── RSS Sources (CRUD - tambah/disable sumber)
   ├── Crawled Logs (monitoring hasil crawl)
   └── Failed Crawls (filter dari crawled_logs status=fail)

── News Center
   ├── All News (list + filter + override sentimen)
   └── News Sources (referensi sumber)

── AI Monitoring
   ├── Sentiments (lihat hasil analisis per berita)
   └── AI Model Logs (skor model A/B/C per berita)

── Analytics
   ├── Media Rankings (ranking media paling negatif/positif)
   ├── Daily Statistics
   ├── Weekly Statistics
   └── Sentiment Trends

── Geographic Intel
   ├── Regions (data wilayah)
   └── Heatmap Data
   (peta visual → next phase)

── Alert Center
   ├── Alert Rules (setup IF-THEN)
   ├── Alert Logs (riwayat alert terpicu)
   └── Notifications (notif ke user)

── Reporting
   ├── Reports (buat & kelola laporan)
   ├── Export Logs (riwayat export)
   └── Templates (next phase)

── System (khusus Admin IT)
   ├── Login History (security)
   └── Audit Trail
```

---

## 5. Apa yang Perlu Dilakukan Sekarang

**Prioritas 1 — Fix schema yang kurang (migrasi baru):**
- Tambah `keyword_groups` + `keyword_group_items`
- Tambah kolom `is_duplicate`, `duplicate_of` di `news`
- Tambah kolom `priority`, `is_active` di `news_sources`

**Prioritas 2 — Proteksi Filament hanya untuk Admin:**
- Edit panel provider agar redirect jika role bukan Admin

**Prioritas 3 — Rebuild semua Filament resource** sesuai struktur di atas (yang sekarang cuma 5 resource sederhana)

---

**Mau saya langsung eksekusi mulai dari mana?** Saran saya: mulai dari fix schema dulu (migrasi 3 hal yang kurang), lalu proteksi Filament, lalu rebuild resource satu per satu dari yang paling penting (Dashboard → Keyword Management → News Center → AI Monitoring).