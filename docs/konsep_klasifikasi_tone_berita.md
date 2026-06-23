# 🧠 ALUR SENTIMENT ANALYSIS (RSS → POSITIVE / NEGATIVE / NEUTRAL)

## ⚙️ (PAKAI AI MODEL BUATAN SENDIRI - HYBRID SYSTEM)

---

# 📄 1. INPUT DATA DARI RSS CRAWLING

Sistem sudah dapat data dari:

📡 **Google News RSS / sumber berita lain**

Contoh data mentah:

* Judul:
  “DPRD Banten kritik kinerja pembangunan infrastruktur”

* Isi (jika tersedia):
  paragraf berita panjang

* Source:
  Kompas / Tribun / dll

---

📌 Status:

```txt
RAW NEWS DATA
```

---

# 🧹 2. PREPROCESSING (BERSIHIN TEKS)

Sebelum masuk AI, teks “dicuci dulu”.

### Proses:

* lowercase → semua jadi kecil
* hapus tanda baca
* hapus angka tidak penting
* stopword removal (kata “yang”, “dan”, “di”)
* stemming (kata jadi bentuk dasar)

  * “membangun” → “bangun”

📌 Istilah:

* **Preprocessing = bikin teks rapi biar AI gampang ngerti**

---

# 🔎 3. FEATURE ENGINEERING (UBAH TEKS → ANGKA)

Karena AI tidak bisa baca teks langsung, jadi diubah ke angka.

Kita pakai:

## 🧠 Model 1: TF-IDF

* ngitung kata penting dalam berita

## 🧠 Model 2: Word Embedding (Word2Vec / FastText)

* ngerti makna kata secara konteks

## 🧠 Model 3 (opsional upgrade): IndoBERT embedding (offline)

* ngerti konteks kalimat lebih dalam

---

📌 Hasil:

```txt
VECTOR REPRESENTATION
(angka-angka dari teks)
```

---

# 🤖 4. MULTI AI MODEL SENTIMENT SYSTEM (INTI SISTEM)

Ini bagian penting.

Kita TIDAK pakai 1 AI, tapi 3 LAPIS MODEL:

---

## 🧠 MODEL A: Lexicon-Based (Rule System)

📌 Cara kerja:
pakai kamus kata:

* positif: bagus, meningkat, sukses
* negatif: korupsi, gagal, konflik

📌 Output:

* skor awal sentiment

---

## 🧠 MODEL B: Machine Learning Classifier

Algoritma:

* Naive Bayes / SVM / Logistic Regression

📌 Input:
TF-IDF vector

📌 Output:

* probability positive / negative / neutral

---

## 🧠 MODEL C: Deep Learning (optional advanced)

* LSTM / BiLSTM / IndoBERT fine-tuned

📌 Fungsi:

* memahami konteks kalimat panjang

---

# ⚖️ 5. MODEL ENSEMBLE (PENGGABUNGAN HASIL)

Semua model digabung:

```txt
Final Score = (A + B + C) / 3
```

atau weighted:

* Lexicon = 20%
* ML Model = 40%
* BERT Model = 40%

---

📌 Contoh hasil:

| Model   | Output       |
| ------- | ------------ |
| Lexicon | Negative 0.6 |
| SVM     | Negative 0.7 |
| BERT    | Neutral 0.5  |

➡️ Final:

```txt
NEGATIVE (0.6 confidence)
```

---

# 📊 6. KLASIFIKASI FINAL SENTIMENT

Setelah skor keluar:

### RULE FINAL:

* 0.0 – 0.4 → NEGATIVE ❌
* 0.4 – 0.6 → NEUTRAL ⚪
* 0.6 – 1.0 → POSITIVE ✅

---

# 🧾 7. SIMPAN HASIL KE DATABASE

Masuk ke tabel:

```txt
sentiments
```

Isi:

* news_id
* sentiment_label
* confidence_score
* model_version

---

# 📈 8. SIAP UNTUK DASHBOARD

Sekarang data bisa dipakai untuk:

* grafik sentimen harian
* berita paling negatif
* media paling sering negatif
* trend opini publik

---


# 🧠 KESIMPULAN (VERSI SIMPLE BANGET)

Berita jadi sentiment lewat proses:

```
RSS berita
→ dibersihkan
→ diubah jadi angka
→ masuk 3 AI model
→ digabung
→ keluar label (positif / negatif / netral)
```
