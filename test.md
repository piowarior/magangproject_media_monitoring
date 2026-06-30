Justru untuk sistem seperti ini, **admin panel Laravel Filament bukan bagian kecil**, malah dia adalah **pusat kendali (control center)** dari seluruh ekosistem Media Monitoring AI.

Yang terlihat di aplikasi mobile hanyalah **hasil akhirnya**.

Kalau diibaratkan:

```text
Flutter Mobile
=
ruang kerja pegawai DPRD

Laravel Filament
=
ruang server + ruang operator + ruang kontrol pusat
```

Bahkan pada implementasi nyata di instansi pemerintah, sering kali:

* 80% kompleksitas ada di backend/admin panel
* 20% ada di aplikasi mobile

---

# Posisi Masing-masing Sistem

## 1. Mobile Flutter

Digunakan oleh:

* Operator media DPRD
* Pimpinan DPRD
* Sekretariat DPRD
* Kepala bagian humas

Fungsinya:

* melihat dashboard
* membuat keyword monitoring
* melihat berita
* melihat sentimen
* melihat tren
* melihat laporan
* menerima notifikasi

Mereka hanya menggunakan hasil.

---

## 2. Laravel Filament Admin Panel

Digunakan oleh:

* Super Admin
* Administrator Sistem
* Tim IT
* Operator Monitoring Pusat

Mereka mengelola mesin di belakang layar.

---

# Gambaran Besar Filament

```text
                    FILAMENT ADMIN PANEL

User Management
Keyword Management
RSS Management
Crawler Monitoring
AI Monitoring
Analytics Management
Report Management
Notification Management
System Monitoring
Audit Log
```

---

# 1. User Management

Menu:

```text
Users
Roles
Permissions
Login Logs
Audit Logs
```

Fungsi:

* membuat akun operator baru
* membuat akun pimpinan
* mengatur hak akses
* menonaktifkan akun
* melihat aktivitas pengguna

Contoh:

```text
Administrator
│
├── Operator Humas DPRD
├── Ketua DPRD
├── Wakil Ketua DPRD
├── Sekretaris DPRD
└── Kepala Bagian Humas
```

---

# 2. Keyword Management

Menu:

```text
Keywords
Keyword Groups
User Keywords
Keyword Runs
```

Administrator dapat:

* melihat keyword aktif
* melihat siapa yang membuat keyword
* melihat keyword paling sering digunakan
* menggabungkan keyword duplikat
* menonaktifkan keyword

Contoh:

```text
"DPRD Banten"
"DPRD Provinsi Banten"
"Dewan Banten"
```

Admin dapat menentukan:

```text
semua keyword tersebut dianggap satu entitas
```

Ini cukup umum pada sistem media monitoring profesional.

---

# 3. RSS Source Management

Menu:

```text
RSS Sources
```

Contoh sumber:

* Google News RSS
* Kompas RSS
* Detik RSS
* CNN RSS
* Antara RSS
* Tempo RSS
* Republika RSS

Admin dapat:

* menambah RSS baru
* menghapus RSS mati
* mengubah prioritas sumber
* menonaktifkan sumber tertentu

Contoh:

```text
Detik RSS Error
↓
Admin disable sementara
↓
Crawler tidak mengambil dari Detik
```

---

# 4. Crawler Monitoring

Ini salah satu menu terbesar.

Menu:

```text
Crawler Jobs
Keyword Runs
Crawled Logs
Failed Crawls
Queue Monitoring
```

Admin dapat melihat:

```text
Keyword:
DPRD Banten

Status:
Running

Total ditemukan:
132 berita

Total disimpan:
124 berita

Duplicate:
8 berita
```

---

Contoh lain:

```text
Keyword:
APBD Banten

Status:
Error

Penyebab:
RSS timeout
```

---

# 5. News Management

Menu:

```text
News
Duplicate News
News Sources
```

Admin dapat:

* melihat semua berita
* melihat berita duplikat
* melakukan reprocess AI
* menghapus berita spam
* memperbaiki data rusak

---

# 6. AI Monitoring

Ini biasanya tidak dimiliki sistem sederhana.

Menu:

```text
Sentiments
AI Model Logs
AI Version
Model Performance
```

Contoh:

## Berita:

```text
"DPRD Banten mengesahkan APBD baru."
```

Hasil:

```text
Lexicon Model:
0.82 positif

SVM:
0.76 positif

LSTM:
0.88 positif

Ensemble:
0.82 positif
```

---

Admin dapat:

* melihat hasil masing-masing model
* mengganti bobot ensemble
* melakukan retraining model
* mengganti versi model AI

---

# 7. Analytics Management

Menu:

```text
Daily Statistics
Weekly Statistics
Media Rankings
Sentiment Trends
```

Admin dapat melihat:

```text
Media paling negatif bulan ini:

1. Media X
2. Media Y
3. Media Z
```

atau:

```text
Sentimen DPRD Banten:

Positif : 62%
Netral  : 24%
Negatif : 14%
```

---

# 8. Geo Intelligence

Menu:

```text
Regions
Heatmap
Geo Locations
```

Contoh:

```text
Kabupaten Tangerang
72 berita negatif

Kota Serang
12 berita negatif

Pandeglang
5 berita negatif
```

Lalu divisualisasikan pada peta.

---

# 9. Alert Center

Menu:

```text
Alert Rules
Alert Logs
Notifications
```

Contoh aturan:

```text
IF
negative sentiment > 60%

THEN
send notification
```

---

Contoh notifikasi:

```text
PERINGATAN

Sentimen negatif DPRD Banten
meningkat 35% dalam 24 jam terakhir.
```

---

# 10. Reporting Center

Menu:

```text
Reports
Exports
Templates
```

Admin dapat:

* membuat laporan bulanan
* membuat laporan triwulan
* export PDF
* export Excel
* export Word

---

Contoh:

```text
Laporan Sentimen DPRD Banten
Periode Juni 2026

Total berita:
456

Positif:
301

Netral:
102

Negatif:
53
```

---

# 11. System Monitoring

Ini biasanya khusus admin IT.

Menu:

```text
Queue Monitor
Job Monitor
Scheduler Monitor
Server Health
Storage Usage
API Status
```

Admin dapat melihat:

```text
CPU Usage:
42%

RAM:
61%

Crawler Queue:
12 pending jobs

AI Queue:
4 pending jobs
```

---

# Struktur Filament Secara Keseluruhan

```text
Dashboard
│
├── User Management
│   ├── Users
│   ├── Roles
│   ├── Permissions
│   ├── Login Logs
│   └── Audit Logs
│
├── Keyword Management
│   ├── Keywords
│   ├── User Keywords
│   ├── Keyword Runs
│   └── Keyword Groups
│
├── Crawling Center
│   ├── RSS Sources
│   ├── Crawled Logs
│   ├── Failed Crawls
│   └── Queue Monitoring
│
├── News Center
│   ├── News
│   ├── Duplicate Detection
│   └── News Sources
│
├── Artificial Intelligence
│   ├── Sentiments
│   ├── AI Model Logs
│   ├── Model Versions
│   └── Performance Metrics
│
├── Analytics
│   ├── Daily Stats
│   ├── Weekly Stats
│   ├── Media Rankings
│   └── Sentiment Trends
│
├── Geographic Intelligence
│   ├── Regions
│   ├── Geo Locations
│   └── Heatmap Data
│
├── Notifications
│   ├── Alert Rules
│   ├── Alert Logs
│   └── Notifications
│
├── Reporting
│   ├── Reports
│   ├── Report Items
│   └── Export Logs
│
└── System Monitoring
    ├── Queue Monitor
    ├── Scheduler Monitor
    ├── Server Health
    └── API Status
```

Kalau melihat struktur database yang kamu punya sekarang, kemungkinan pembagian pekerjaan nantinya kurang lebih seperti ini:

| Komponen            | Teknologi                         |
| ------------------- | --------------------------------- |
| Mobile App Pengguna | Flutter                           |
| REST API            | Laravel                           |
| Admin Panel         | Filament                          |
| Database            | PostgreSQL                        |
| Crawler             | Laravel Queue + Scheduler         |
| AI Service          | Python                            |
| Sentiment Engine    | Lexicon + SVM + LSTM/Transformer  |
| Cache               | Redis                             |
| Background Job      | Laravel Queue                     |
| Authentication      | Laravel Sanctum                   |
| Role Permission     | Spatie Permission                 |
| Visualisasi         | Filament Widgets + Flutter Charts |

Jadi pada arsitektur ini:

```text
Flutter
    ↓
Laravel API
    ↓
PostgreSQL
    ↓
Crawler Engine
    ↓
AI Engine
    ↓
Filament Admin Panel
```

atau dari sudut pandang operasional:

```text
Pimpinan DPRD
        ↓
Operator Media Monitoring
        ↓
Flutter Mobile App
        ↓
Laravel API
        ↓
AI Media Monitoring Engine
        ↓
Filament Admin Panel
        ↓
Administrator Sistem
```

Dengan kata lain, **Filament bukan sekadar halaman CRUD**, tetapi merupakan **pusat operasional, pusat pengawasan, dan pusat administrasi seluruh sistem media monitoring berbasis AI** yang memastikan proses crawling, analisis sentimen, pelaporan, dan pengelolaan pengguna berjalan dengan baik.
