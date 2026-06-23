# 📄 ALUR KEYWORD → RSS DATA SIAP ANALISIS (MARKDOWN)

## 🧠 1. INPUT KEYWORD (USER)

User memasukkan keyword pada menu Monitoring.

Contoh:

* Keyword: `DPRD Banten`
* Periode: `7 hari` (opsional)

Lalu user klik:

* `[Analisis Sekarang]`

📌 Hasil:
Sistem menerima permintaan analisis.

---

## 💾 2. SIMPAN KEYWORD KE SISTEM

Sistem menyimpan keyword ke database.

Tabel:

* `keywords`

Data yang disimpan:

* keyword_text (contoh: "DPRD Banten")
* user_id
* created_at

📌 Fungsi:
Agar keyword bisa dilacak dan dipakai ulang.

---

## 🚀 3. TRIGGER CRAWLING ENGINE

Setelah keyword tersimpan, sistem otomatis menjalankan:

➡️ **News Crawler Service**

📌 Istilah:

* **Crawler (robot pengambil data)** = sistem yang mencari berita dari internet secara otomatis.

---

## 🌐 4. PENGAMBILAN DATA DARI NEWS RSS

Crawler mengakses sumber berita:

* Google News RSS
* atau RSS portal berita lain

Contoh request:

```
https://news.google.com/rss/search?q=DPRD+Banten
```

📌 Output:
RSS feed berisi list berita.

---

## 📦 5. PARSING DATA RSS

Sistem membaca data RSS dan mengubahnya menjadi struktur data internal.

Data yang diambil:

* Judul berita
* Link berita
* Tanggal publikasi
* Sumber media

📌 Istilah:

* **Parsing (membaca & mengubah data mentah jadi data terstruktur)**

---

## 🧹 6. FILTER & CLEANING DATA BERITA

Sistem melakukan penyaringan:

* Hapus berita tidak relevan
* Hapus duplikasi
* Validasi keyword muncul di konten

📌 Tujuan:
Agar hanya berita relevan yang diproses.

---

## 📊 7. HASIL AKHIR CRAWLING (READY FOR AI)

Setelah semua proses di atas selesai, sistem menghasilkan:

### 👉 NEWS DATA SIAP ANALISIS:

Contoh struktur:

* title: "DPRD Banten bahas anggaran pendidikan"
* source: Kompas
* date: 2026-06-23
* url: https://....

📌 Status:

```
READY FOR AI PROCESSING
```

---

# 🧠 OUTPUT FINAL DARI ALUR INI

Di titik akhir ini kamu sudah punya:

✔ Keyword dari user
✔ Data RSS dari Google News
✔ Data sudah dibersihkan
✔ Data sudah siap masuk AI sentiment

---

