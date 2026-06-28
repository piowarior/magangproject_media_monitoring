# 📋 Pemahaman Proyek: Media Monitoring & Analisis Sentimen AI
## Sistem untuk DPRD Banten

---

## 🎯 Tujuan Sistem

Sebuah sistem **Aplikasi Mobile Media Monitoring dan Analisis Sentimen Berita Berbasis AI** untuk mendukung evaluasi persepsi media terhadap **DPRD Banten (Dewan Perwakilan Rakyat Daerah Provinsi Banten)**.

Sistem ini memungkinkan:
- Crawling berita otomatis berdasarkan keyword (misal: "DPRD Banten")
- Analisis sentimen berita menggunakan AI (Positif / Netral / Negatif)
- Visualisasi data melalui dashboard mobile
- Peta heatmap sentimen per wilayah Banten
- Laporan PDF otomatis
- Notifikasi alert berita negatif

---

## 🏗️ Arsitektur Sistem (3-Tier)

```
┌─────────────────────────────────────────────┐
│          MOBILE APP (Flutter)               │
│   Login | Dashboard | Monitoring | Peta     │
│   Laporan | Profil | Notifikasi             │
└─────────────────┬───────────────────────────┘
                  │ REST API (Laravel Sanctum)
┌─────────────────▼───────────────────────────┐
│        BACKEND API (Laravel 13 + Filament)  │
│   Admin Panel | News Crawler | Queue Jobs   │
│   Spatie Permission | PostgreSQL            │
└─────────────────┬───────────────────────────┘
                  │ HTTP Call
┌─────────────────▼───────────────────────────┐
│     AI SERVICE (Python - belum dibuat)      │
│   IndoBERT | SVM | Lexicon Ensemble         │
└─────────────────────────────────────────────┘
```

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Mobile | Flutter (Dart) |
| Backend | Laravel 13 (PHP 8.3) |
| Admin Panel | Filament v5 |
| Auth | Laravel Sanctum |
| RBAC | Spatie Laravel Permission |
| Database | PostgreSQL |
| Queue | Laravel Queue (database driver) |
| Crawler | Google News RSS |
| AI/ML | Python (IndoBERT + SVM + Lexicon) |
| Notifikasi Push | Firebase Cloud Messaging (FCM) |
| PDF Export | (belum ditentukan - dompdf/barryvdh) |

---

## 👥 Role Pengguna

| Role | Hak Akses |
|------|-----------|
| **Admin** | Kelola user, keyword, crawler |
| **Operator** | Monitoring berita, generate laporan |
| **Pimpinan** | Lihat dashboard & laporan saja |

> ⚠️ **Catatan**: Di `schema_database.md` role disebut `admin / analyst / viewer`, 
> tapi di Todolist disebut `Admin / Operator / Pimpinan`. 
> **Yang benar = Todolist** (Admin / Operator / Pimpinan) karena lebih sesuai konteks lembaga pemerintah.

---

## 🗄️ Struktur Database (dari docs/schema_database.md)

### 9 Kelompok Tabel:

1. **User & Security**: `users`, `roles`, `permissions`, `model_has_roles`, `audit_logs`, `login_logs`
2. **Keyword Core**: `keywords`, `keyword_runs`
3. **News & Crawling**: `news_sources`, `news`, `crawled_logs`
4. **AI Sentiment**: `sentiments`, `ai_model_logs`
5. **Topic & Entity**: `topics`, `news_topics`, `entities`, `news_entities`
6. **Analytics**: `daily_stats`, `media_rankings`, `weekly_stats`, `sentiment_trends`
7. **Geo Banten**: `regions`, `news_regions`, `geo_locations`, `heatmap_data`
8. **Notifikasi**: `notifications`, `alert_rules`, `alert_logs`
9. **Reporting**: `reports`, `report_items`, `export_logs`

---

## 🤖 Alur AI Sentiment Analysis

```
RSS News Data
  → Preprocessing (lowercase, hapus stopword, stemming)
  → Feature Engineering (TF-IDF + Word2Vec + IndoBERT)
  → 3 Model Ensemble:
      Model A: Lexicon-based (20%)
      Model B: ML Classifier - SVM/Naive Bayes (40%)
      Model C: Deep Learning - IndoBERT (40%)
  → Final Score → Label (Positif/Netral/Negatif)
  → Simpan ke tabel sentiments
```

---

## 🔄 Alur Crawling

```
User input keyword → Simpan ke DB (tabel keywords)
  → Trigger News Crawler Service
  → Fetch Google News RSS
  → Parse XML → Filter & dedup → Simpan ke tabel news
  → Trigger AI Service
```

---

## 📁 Kondisi Kode Saat Ini

### Backend (`beckend-api/`) - Laravel
**Sudah ada:**
- ✅ Laravel 13 + Filament v5 + Spatie Permission terinstall
- ✅ PostgreSQL terkonfigurasi (`media_monitoring` DB)
- ✅ Migrations: `users`, `keywords`, `news`, `sentiments`, `keyword_runs`, `news_sources`, `crawled_logs`, `permission_tables`, `user_keywords`
- ✅ Models: `User`, `Keyword`, `KeywordRun`, `News`, `NewsSource`, `Sentiment`, `CrawledLog`

**Belum ada:**
- ❌ Migrations untuk: `topics`, `entities`, `daily_stats`, `reports`, `regions`, `notifications`, `alert_rules`, `heatmap_data`, dll (banyak tabel dari schema)
- ❌ API Controllers (folder `Http/Controllers` kosong)
- ❌ Sanctum setup
- ❌ Filament Resources (Admin Panel belum dibangun)
- ❌ Crawler Service
- ❌ Queue Jobs

### Mobile App (`mobile_app/`) - Flutter
**Sudah ada:**
- ✅ Proyek Flutter fresh (boilerplate counter app)
- ✅ Hanya ada `main.dart` default - **belum ada satupun halaman aplikasi**

**Belum ada:**
- ❌ Semua halaman (Login, Home, Monitoring, Peta, Laporan, Profil)
- ❌ Dependency: http, provider/riverpod, fl_chart, dll
- ❌ Struktur folder (features, models, services, dll)

### Python AI Service
- ❌ Belum dibuat sama sekali (folder belum ada)

---

## 🗺️ Roadmap 10 Fase (dari Todolist.md)

| Fase | Nama | Status |
|------|------|--------|
| 1 | Setup Project | 🔴 Belum |
| 2 | Perancangan Sistem | 🔴 Belum |
| 3 | Database | 🟡 Sebagian (migrasi dasar ada) |
| 4 | Cyber Security | 🟡 Sebagian (Spatie terinstall) |
| 5 | Admin Panel (Filament) | 🔴 Belum |
| 6 | Mobile UI | 🔴 Belum |
| 7 | Crawling Berita | 🔴 Belum |
| 8 | AI (Sentiment) | 🔴 Belum |
| 9 | Dashboard & Peta | 🔴 Belum |
| 10 | Laporan & Notifikasi | 🔴 Belum |

---

## ⚠️ Perbedaan README vs Docs

README.md **kosong** (0 bytes), jadi tidak ada konflik. 
Semua arsitektur mengacu ke folder `docs/`.

---

## 🚀 Apa yang Perlu Dikerjakan Selanjutnya?

Berdasarkan urutan fase Todolist:

1. **Verifikasi setup**: Apakah Laravel & PostgreSQL sudah jalan? Apakah Flutter & emulator ready?
2. **Lengkapi migrasi database**: Masih banyak tabel yang belum dibuat
3. **Setup Sanctum API**: Untuk auth mobile
4. **Bangun Filament Admin Panel**: User, Keyword, News management
5. **Mobile UI Flutter**: Semua halaman dari nol
6. **Crawler service**: Google News RSS
7. **Python AI service**: IndoBERT ensemble
