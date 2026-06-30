import 'dart:math';

class DummyNews {
  final int id;
  final String title;
  final String content;
  final String source;
  final String keyword;
  final String sentiment; // positive, neutral, negative
  final double confidenceScore;
  final DateTime publishedAt;
  final String region;

  DummyNews({
    required this.id,
    required this.title,
    required this.content,
    required this.source,
    required this.keyword,
    required this.sentiment,
    required this.confidenceScore,
    required this.publishedAt,
    required this.region,
  });
}

class DummyService {
  static final List<String> sources = [
    'Kompas', 'Detik News', 'Antara News', 'Tempo', 'CNN Indonesia', 
    'Tribun Banten', 'Radar Banten', 'Kabar Banten', 'Bantenhits'
  ];

  static final List<String> regions = [
    'Serang Kota', 'Serang Kab', 'Cilegon', 'Tangerang Kota', 
    'Tangerang Kab', 'Tangerang Selatan', 'Lebak', 'Pandeglang'
  ];

  static final List<Map<String, String>> newsTitles = [
    {
      'title': 'DPRD Banten Dorong Peningkatan Infrastruktur Jalan di Wilayah Selatan',
      'sentiment': 'positive',
      'content': 'DPRD Provinsi Banten mendesak Dinas Pekerjaan Umum untuk segera menyelesaikan perbaikan jalan lintas kabupaten di Banten Selatan guna mendukung konektivitas ekonomi masyarakat menjelang libur akhir tahun.'
    },
    {
      'title': 'Warga Keluhkan Pelayanan Publik, Anggota DPRD Banten Gelar Reses',
      'sentiment': 'neutral',
      'content': 'Dalam agenda reses masa persidangan ke-II, beberapa anggota DPRD Banten menerima masukan dari masyarakat terkait peningkatan sarana prasarana kesehatan tingkat puskesmas serta akses air bersih.'
    },
    {
      'title': 'Alokasi Anggaran Dipertanyakan, Sidang Paripurna DPRD Banten Berlangsung Alot',
      'sentiment': 'negative',
      'content': 'Pembahasan rancangan APBD Perubahan di ruang rapat paripurna DPRD Banten berjalan tegang setelah sejumlah fraksi menyoroti tingginya alokasi belanja operasional dibanding belanja modal publik.'
    },
    {
      'title': 'DPRD Banten Apresiasi Kinerja Pemprov Raih Penghargaan Investasi Terbaik',
      'sentiment': 'positive',
      'content': 'Ketua DPRD Banten memberikan apresiasi kepada jajaran pemerintah provinsi atas keberhasilan mempertahankan peringkat realisasi investasi tertinggi di tingkat regional.'
    },
    {
      'title': 'Evaluasi Kinerja BUMD Banten, DPRD Minta Transparansi Laporan Keuangan',
      'sentiment': 'neutral',
      'content': 'Komisi III DPRD Banten menjadwalkan rapat dengar pendapat (RDP) bersama jajaran direksi BUMD untuk meninjau secara mendalam laporan kinerja kuartal ketiga.'
    },
    {
      'title': 'Lambat Antisipasi Banjir, Kinerja Penanggulangan Bencana DPRD Banten Dikritik',
      'sentiment': 'negative',
      'content': 'Sejumlah organisasi masyarakat sipil menyayangkan minimnya alokasi anggaran penanggulangan dampak banjir yang disetujui legislatif dalam APBD tahun anggaran berjalan.'
    },
    {
      'title': 'DPRD Banten Sahkan Perda Perlindungan Tenaga Kerja Lokal',
      'sentiment': 'positive',
      'content': 'Rapat Paripurna DPRD Provinsi Banten secara resmi mengesahkan Peraturan Daerah tentang pemberdayaan dan jaminan perlindungan bagi tenaga kerja lokal di kawasan industri.'
    },
    {
      'title': 'DPRD Banten Tinjau Kesiapan Logistik Pemilu di Gudang KPU Wilayah',
      'sentiment': 'neutral',
      'content': 'Komisi I DPRD Provinsi Banten memantau langsung kesiapan distribusi logistik kotak suara dan surat suara guna memastikan kelancaran pemilu yang demokratis.'
    },
  ];

  static List<DummyNews> getDummyNewsList() {
    final Random random = Random(42); // Seed agar konsisten
    List<DummyNews> list = [];

    for (int i = 0; i < 40; i++) {
      final titleMap = newsTitles[random.nextInt(newsTitles.length)];
      final source = sources[random.nextInt(sources.length)];
      final region = regions[random.nextInt(regions.length)];
      final daysAgo = random.nextInt(15);
      final publishedAt = DateTime.now().subtract(Duration(days: daysAgo, hours: random.nextInt(24)));
      final conf = 0.70 + random.nextDouble() * 0.28; // 70% - 98%

      list.add(DummyNews(
        id: i + 1,
        title: titleMap['title']!,
        content: titleMap['content']!,
        source: source,
        keyword: 'DPRD Banten',
        sentiment: titleMap['sentiment']!,
        confidenceScore: conf,
        publishedAt: publishedAt,
        region: region,
      ));
    }
    
    // Urutkan berdasarkan tanggal terbaru
    list.sort((a, b) => b.publishedAt.compareTo(a.publishedAt));
    return list;
  }
}
