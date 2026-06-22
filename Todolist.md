

# Roadmap Proyek Media Monitoring DPRD Banten

Saya bagi menjadi 10 fase.

---

# FASE 1 - Setup Project

Target:

Semua project bisa jalan.

## Backend

* [ ] Laravel berjalan
* [ ] PostgreSQL terkoneksi
* [ ] Filament terinstall
* [ ] Admin bisa login

## Mobile

* [ ] Flutter berjalan
* [ ] Emulator berjalan
* [ ] Struktur folder dibuat

## Repository

* [ ] GitHub dibuat
* [ ] Push pertama

---

# FASE 2 - Perancangan Sistem

Sebelum coding fitur.

## Dokumentasi

* [ ] Nama aplikasi final
* [ ] Flow aplikasi
* [ ] Use Case Diagram
* [ ] Activity Diagram
* [ ] ERD
* [ ] Hak akses user

---

## Role

### Admin

* Kelola user
* Kelola keyword
* Kelola crawler

---

### Operator

* Monitoring berita
* Generate laporan

---

### Pimpinan

* Lihat dashboard
* Lihat laporan

---

# FASE 3 - Database

Ini menurut saya prioritas berikutnya.

## users

* [ ] migration
* [ ] model

---

## keywords

* [ ] migration
* [ ] model

---

## news

* [ ] migration
* [ ] model

---

## sentiment_results

* [ ] migration
* [ ] model

---

## topics

* [ ] migration
* [ ] model

---

## reports

* [ ] migration
* [ ] model

---

## notifications

* [ ] migration
* [ ] model

---

## audit_logs

* [ ] migration
* [ ] model

---

# FASE 4 - Cyber Security

Karena dosen minta.

## Authentication

* [ ] Sanctum

---

## Authorization

* [ ] Spatie Permission

Role:

* Admin
* Operator
* Pimpinan

---

## Audit Log

* [ ] Activity Log

---

## Rate Limiting

* [ ] API Protection

---

## Password Security

* [ ] Password Policy

---

# FASE 5 - Admin Panel

Ini sebaiknya selesai dulu sebelum AI.

---

## User Management

* [ ] CRUD User

---

## Keyword Management

* [ ] CRUD Keyword

Misalnya:

```text
DPRD Banten
Ketua DPRD Banten
Anggota DPRD Banten
```

---

## Monitoring History

* [ ] Riwayat analisis

---

## News Management

* [ ] Daftar berita

---

## Report Management

* [ ] Daftar laporan

---

# FASE 6 - Mobile UI

Belum perlu API dulu.

Dummy dulu.

---

## Login

* [ ] UI

---

## Home

* [ ] UI

---

## Monitoring

* [ ] UI

---

## Peta

* [ ] UI

---

## Laporan

* [ ] UI

---

## Profil

* [ ] UI

---

# FASE 7 - Crawling Berita

Mulai fitur inti.

---

## Service

* [ ] Google News RSS

---

## Scheduler

* [ ] Laravel Scheduler

---

## Queue

* [ ] Queue Job

---

## Simpan ke Database

* [ ] News Table

---

# FASE 8 - AI

Baru sekarang.

---

## Sentiment Analysis

* [ ] Python Service

---

## IndoBERT

* [ ] Integrasi

---

## Sentiment

* [ ] Positif
* [ ] Netral
* [ ] Negatif

---

## Topic Classification

* [ ] Pendidikan
* [ ] Infrastruktur
* [ ] Anggaran
* [ ] Kesehatan

---

## Summary

* [ ] Ringkasan otomatis

---

# FASE 9 - Dashboard dan Peta

---

## Dashboard

* [ ] Statistik

---

## Grafik

* [ ] Sentiment Chart

---

## Top Media

* [ ] Ranking

---

## Top Isu

* [ ] Ranking

---

## Peta Banten

* [ ] GeoJSON

---

## Heatmap

* [ ] Warna sentimen

---

# FASE 10 - Laporan dan Notifikasi

Tahap akhir.

---

## PDF

* [ ] Generate PDF

---

## Export

* [ ] PDF

---

## Notification

* [ ] Firebase

---

## Alert

* [ ] Berita Negatif

---

## Ringkasan Bulanan

* [ ] Otomatis

---

