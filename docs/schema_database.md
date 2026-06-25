# 🧱 DATABASE MEDIA MONITORING AI (FINAL PHASE 1)

---

# 👤 1. USER & SECURITY

## Tabel: users

| Field      | Type      | Keterangan      |
| ---------- | --------- | --------------- |
| id         | bigint PK | ID user         |
| name       | string    | nama user       |
| email      | string    | email login     |
| password   | string    | password hash   |
| role_id    | bigint FK | relasi ke roles |
| created_at | timestamp | waktu dibuat    |
| updated_at | timestamp | waktu update    |

Fungsi:

Menyimpan semua pengguna sistem (admin, analyst, viewer).

Relasi:

* users.role_id → roles.id

---

## Tabel: roles

| Field | Type      | Keterangan               |
| ----- | --------- | ------------------------ |
| id    | bigint PK | ID role                  |
| name  | string    | admin / analyst / viewer |

Fungsi:

Menentukan level akses user.

Relasi:
roles.id → users.role_id

---

## Tabel: permissions

| Field | Type      | Keterangan       |
| ----- | --------- | ---------------- |
| id    | bigint PK | ID permission    |
| name  | string    | nama akses fitur |

Fungsi:

Hak akses granular (opsional untuk sistem lebih advanced).

---

## Tabel: model_has_roles

| Field   | Type      | Keterangan  |
| ------- | --------- | ----------- |
| user_id | bigint FK | relasi user |
| role_id | bigint FK | relasi role |

Fungsi:

Pivot tabel untuk sistem role multi-user.

Relasi:

* users ↔ roles (many-to-many)

---

## Tabel: audit_logs

| Field       | Type      | Keterangan   |
| ----------- | --------- | ------------ |
| id          | bigint PK | log ID       |
| user_id     | bigint FK | pelaku       |
| action      | string    | aksi         |
| table_name  | string    | tabel target |
| description | text      | detail       |
| created_at  | timestamp | waktu        |

Fungsi:

Mencatat semua aktivitas penting user di sistem.

---

## Tabel: login_logs

| Field      | Type      | Keterangan  |
| ---------- | --------- | ----------- |
| id         | bigint PK | log ID      |
| user_id    | bigint FK | user        |
| ip_address | string    | IP login    |
| device     | string    | device      |
| login_time | timestamp | waktu login |

Fungsi:

Tracking login untuk keamanan (cybersecurity layer).

---

# 🧠 2. KEYWORD CORE (JANTUNG SISTEM)

## Tabel: keywords

| Field        | Type      | Keterangan          |
| ------------ | --------- | ------------------- |
| id           | bigint PK | keyword ID          |
| user_id      | bigint FK | pembuat keyword     |
| keyword_text | string    | contoh: DPRD Banten |
| region_scope | string    | Banten / Indonesia  |
| status       | string    | active / inactive   |
| created_at   | timestamp | waktu dibuat        |

Fungsi:

Keyword yang diketik user sebagai pemicu crawling.

Contoh:

“DPRD Banten”, “Pemilu 2026”

Relasi:

* users.id → keywords.user_id
* keywords.id → news.keyword_id

---

## Tabel: keyword_runs

| Field       | Type      | Keterangan                |
| ----------- | --------- | ------------------------- |
| id          | bigint PK | run ID                    |
| keyword_id  | bigint FK | keyword                   |
| started_at  | timestamp | mulai crawl               |
| finished_at | timestamp | selesai                   |
| status      | string    | processing / done / error |

Fungsi:

Melacak proses crawling setiap keyword.

Relasi:

* keywords.id → keyword_runs.keyword_id

---

# 🌐 3. NEWS & CRAWLING

## Tabel: news_sources

| Field | Type      | Keterangan |
| ----- | --------- | ---------- |
| id    | bigint PK | source ID  |
| name  | string    | nama media |
| url   | string    | RSS link   |
| type  | string    | rss / api  |

Fungsi:

Sumber berita (Google RSS, Kompas, Detik, dll)

---

## Tabel: news

| Field        | Type      | Keterangan     |
| ------------ | --------- | -------------- |
| id           | bigint PK | news ID        |
| keyword_id   | bigint FK | keyword        |
| source_id    | bigint FK | sumber berita  |
| title        | string    | judul          |
| content      | text      | isi berita     |
| url          | string    | link asli      |
| published_at | datetime  | waktu publish  |
| hash         | string    | anti duplicate |

Fungsi:

Hasil berita yang sudah di-crawl dari RSS.

Relasi:
* news.keyword_id → keywords.id
* news.source_id → news_sources.id
* news.id → sentiments.news_id
* news.id → news_topics.news_id
* news.id → news_entities.news_id
* news.id → news_regions.news_id

---

## Tabel: crawled_logs

| Field         | Type      | Keterangan     |
| ------------- | --------- | -------------- |
| id            | bigint PK | log            |
| keyword_id    | bigint FK | keyword        |
| status        | string    | success / fail |
| total_fetched | int       | jumlah ambil   |
| total_saved   | int       | jumlah simpan  |
| error_message | text      | error          |
| created_at    | timestamp | waktu          |

Fungsi:

Log proses crawling (debug & monitoring sistem).

---

# 🤖 4. AI SENTIMENT SYSTEM

## Tabel: sentiments

| Field            | Type      | Keterangan                    |
| ---------------- | --------- | ----------------------------- |
| id               | bigint PK | sentiment ID                  |
| news_id          | bigint FK | berita                        |
| final_sentiment  | string    | positive / neutral / negative |
| confidence_score | float     | keyakinan AI                  |
| model_version    | string    | versi model                   |

Fungsi:

Hasil akhir analisis sentiment berita.

Relasi:

* news.id → sentiments.news_id

---

## Tabel: ai_model_logs

| Field         | Type      | Keterangan     |
| ------------- | --------- | -------------- |
| id            | bigint PK | log            |
| news_id       | bigint FK | berita         |
| model_a_score | float     | lexicon        |
| model_b_score | float     | ML             |
| model_c_score | float     | DL             |
| final_score   | float     | hasil ensemble |

Fungsi:

Menyimpan hasil tiap model AI (ensemble system).

Model contoh:
* Model A = Lexicon based
* Model B = Machine Learning (SVM / Naive Bayes)
* Model C = Deep Learning (LSTM / Transformer kecil)

---

# 🧠 5. TOPIC & ENTITY

## Tabel: topics

| Field | Type      | Keterangan        |
| ----- | --------- | ----------------- |
| id    | bigint PK | topic ID          |
| name  | string    | politik / ekonomi |

Fungsi:

Kategori topik berita (politik, ekonomi, hukum)

---

## Tabel: news_topics

| Field    | Type      | Keterangan |
| -------- | --------- | ---------- |
| news_id  | bigint FK | berita     |
| topic_id | bigint FK | topic      |

Fungsi:

Relasi many-to-many news dan topic.

---

## Tabel: entities

| Field | Type      | Keterangan            |
| ----- | --------- | --------------------- |
| id    | bigint PK | entity ID             |
| name  | string    | nama orang / instansi |
| type  | string    | person / org / place  |

Fungsi:

Nama entitas penting (orang, instansi, lokasi).

---

## Tabel: news_entities

| Field     | Type      | Keterangan |
| --------- | --------- | ---------- |
| news_id   | bigint FK | berita     |
| entity_id | bigint FK | entity     |

Fungsi:

Relasi berita dengan entitas yang disebut.

---

# 📊 6. ANALYTICS

## Tabel: daily_stats

| Field      | Type      | Keterangan |
| ---------- | --------- | ---------- |
| id         | bigint PK | ID         |
| keyword_id | bigint FK | keyword    |
| date       | date      | tanggal    |
| total_news | int       | total      |
| positive   | int       | positif    |
| neutral    | int       | netral     |
| negative   | int       | negatif    |

Fungsi:

Statistik harian sentiment.

---

## Tabel: media_rankings

| Field     | Type      | Keterangan |
| --------- | --------- | ---------- |
| id        | bigint PK | ID         |
| source_id | bigint FK | media      |
| score     | float     | ranking    |

Fungsi:

Ranking media berdasarkan kualitas sentiment.

## weekly_stats

| Field      | Tipe   |
| ---------- | ------ |
| id         | bigint |
| keyword_id | bigint |
| week_start | date   |
| week_end   | date   |
| summary    | text   |


Fungsi:

Ringkasan mingguan.

---

## Tabel: sentiment_trends

| Field                  | Type      | Keterangan  |
| ---------------------- | --------- | ----------- |
| id                     | bigint PK | ID          |
| keyword_id             | bigint FK | keyword     |
| date                   | date      | tanggal     |
| sentiment_distribution | json      | data grafik |

Fungsi:

Trend naik-turun sentiment.

---

# 🗺️ 7. GEO BANTEN

## Tabel: regions

| Field | Type      | Keterangan |
| ----- | --------- | ---------- |
| id    | bigint PK | wilayah    |
| name  | string    | kab/kota   |

Fungsi:

Wilayah (Banten: Serang, Tangerang, dll)

---

## Tabel: news_regions

| Field     | Type      | Keterangan |
| --------- | --------- | ---------- |
| news_id   | bigint FK | berita     |
| region_id | bigint FK | wilayah    |

Fungsi:

Relasi berita dengan wilayah.

---

## geo_locations

| Field     | Tipe   |
| --------- | ------ |
| id        | bigint |
| region_id | bigint |
| lat       | float  |
| lng       | float  |

Fungsi:

Koordinat wilayah.


---

## Tabel: heatmap_data

| Field           | Type      | Keterangan        |
| --------------- | --------- | ----------------- |
| id              | bigint PK | ID                |
| region_id       | bigint FK | wilayah           |
| intensity_score | float     | kekuatan sentimen |

Fungsi:

Data visual heatmap.


---

# 🔔 8. NOTIFIKASI

## Tabel: notifications

| Field   | Type      | Keterangan |
| ------- | --------- | ---------- |
| id      | bigint PK | ID         |
| user_id | bigint FK | user       |
| title   | string    | judul      |
| message | text      | isi        |
| type    | string    | alert/info |
| is_read | boolean   | status     |

Fungsi:

Notifikasi ke user.

---

## Tabel: alert_rules

| Field      | Type      | Keterangan |
| ---------- | --------- | ---------- |
| id         | bigint PK | ID         |
| keyword_id | bigint FK | keyword    |
| condition  | string    | rule       |
| action     | string    | aksi       |

Fungsi:

Rule otomatis trigger alert.

---

## Tabel: alert_logs

| Field         | Type      | Keterangan |
| ------------- | --------- | ---------- |
| id            | bigint PK | ID         |
| alert_rule_id | bigint FK | rule       |
| triggered_at  | timestamp | waktu      |

Fungsi:

Log alert yang pernah terjadi.

---

# 📄 9. REPORTING

## Tabel: reports

| Field        | Type      | Keterangan |
| ------------ | --------- | ---------- |
| id           | bigint PK | ID         |
| keyword_id   | bigint FK | keyword    |
| title        | string    | judul      |
| period_start | date      | awal       |
| period_end   | date      | akhir      |

Fungsi:

Ini adalah header laporan utama

Artinya:

* 1 report = 1 keyword + 1 periode waktu

Contoh :
```
Keyword: "DPRD Banten"
Periode: 1 - 30 Juni

→ REPORT: "Analisis Sentimen DPRD Banten Juni 2026" 
```
Relasi:
* reports.keyword_id → keywords.id
* reports.id → report_items.report_id
* reports.id → export_logs.report_id
---

## Tabel: report_items

| Field     | Type      | Keterangan |
| --------- | --------- | ---------- |
| id        | bigint PK | ID         |
| report_id | bigint FK | laporan    |
| news_id   | bigint FK | berita     |

fungsi:

Ini adalah isi laporan (detail berita)

Artinya:

semua berita yang masuk ke report ditaruh di sini
Isi report_items = daftar berita

Contoh:
```
Report DPRD Banten:

berita 1 → positif
berita 2 → netral
berita 3 → negatif

```
Relasi:
* report_items.report_id → reports.id
* report_items.news_id → news.id

---

## Tabel: export_logs

| Field       | Type      | Keterangan |
| ----------- | --------- | ---------- |
| id          | bigint PK | ID         |
| report_id   | bigint FK | laporan    |
| format      | string    | pdf/excel  |
| exported_at | timestamp | waktu      |

Fungsi:

Ini adalah catatan ketika user download laporan
```
Contoh:
Admin download:
- PDF report DPRD Banten
- Excel report DPRD Banten
```
Relasi:
* export_logs.report_id → reports.id

---

# 🔗 RELASI UTAMA (FIXED)

```txt
users → keywords → keyword_runs
                 ↓
               news → sentiments
                 ↓        ↓
     news_sources   ai_model_logs
                 ↓
     topics & entities
                 ↓
          analytics
                 ↓
      reports & notifications
                 ↓
           heatmap (regions)
```

---

