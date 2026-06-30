import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:provider/provider.dart';
import '../providers/app_state_provider.dart';
import '../../core/theme/app_theme.dart';

class ReportScreen extends StatefulWidget {
  const ReportScreen({super.key});

  @override
  State<ReportScreen> createState() => _ReportScreenState();
}

class _ReportScreenState extends State<ReportScreen> {
  // Dummy reports data
  final List<Map<String, dynamic>> _reports = [
    {
      'id': 1,
      'title': 'Laporan Sentimen DPRD Banten (Mei 2026)',
      'keyword': 'DPRD Banten',
      'period': '01 Mei - 31 Mei 2026',
      'status': 'Exported',
      'created_at': '01 Jun 2026',
    },
    {
      'id': 2,
      'title': 'Laporan Analisis Krisis Isu Jalan Banten Selatan',
      'keyword': 'Kinerja DPRD Banten',
      'period': '10 Jun - 20 Jun 2026',
      'status': 'Generated',
      'created_at': '21 Jun 2026',
    },
  ];

  @override
  Widget build(BuildContext context) {
    final appState = Provider.of<AppStateProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('LAPORAN HUMAS'),
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16.0),
        itemCount: _reports.length,
        itemBuilder: (context, index) {
          final report = _reports[index];
          return Padding(
            padding: const EdgeInsets.only(bottom: 12.0),
            child: _buildReportCard(context, report),
          );
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        backgroundColor: AppTheme.primary,
        foregroundColor: Colors.white,
        icon: const Icon(Icons.add),
        label: const Text('Buat Laporan'),
        onPressed: () {
          _showCreateReportDialog(context, appState);
        },
      ),
    );
  }

  Widget _buildReportCard(BuildContext context, Map<String, dynamic> report) {
    Color statusColor;
    switch (report['status']) {
      case 'Exported':
        statusColor = AppTheme.positive;
        break;
      case 'Generated':
        statusColor = AppTheme.accent;
        break;
      default:
        statusColor = AppTheme.neutral;
    }

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: statusColor.withOpacity(0.1),
                    borderRadius: BorderRadius.circular(6),
                    border: Border.all(color: statusColor.withOpacity(0.4)),
                  ),
                  child: Text(
                    report['status'],
                    style: TextStyle(color: statusColor, fontSize: 10, fontWeight: FontWeight.bold),
                  ),
                ),
                Text(
                  'Dibuat: ${report['created_at']}',
                  style: const TextStyle(color: AppTheme.textSecondary, fontSize: 11),
                ),
              ],
            ),
            const SizedBox(height: 12),
            Text(
              report['title'],
              style: const TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Row(
              children: [
                const Icon(Icons.key, size: 12, color: AppTheme.textSecondary),
                const SizedBox(width: 4),
                Text('Keyword: ${report['keyword']}', style: const TextStyle(color: AppTheme.textSecondary, fontSize: 12)),
              ],
            ),
            const SizedBox(height: 4),
            Row(
              children: [
                const Icon(Icons.date_range, size: 12, color: AppTheme.textSecondary),
                const SizedBox(width: 4),
                Text('Periode: ${report['period']}', style: const TextStyle(color: AppTheme.textSecondary, fontSize: 12)),
              ],
            ),
            const Divider(height: 24, color: AppTheme.borderCard),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                TextButton.icon(
                  onPressed: () {
                    _simulatePdfPreview(context, report['title']);
                  },
                  icon: const Icon(Icons.picture_as_pdf, size: 16, color: AppTheme.accent),
                  label: const Text('Preview PDF', style: TextStyle(color: AppTheme.accent, fontSize: 13)),
                ),
                const SizedBox(width: 12),
                ElevatedButton.icon(
                  onPressed: () {
                    ScaffoldMessenger.of(context).showSnackBar(
                      const SnackBar(content: Text('Mengunduh laporan PDF ke penyimpanan local...')),
                    );
                    setState(() {
                      report['status'] = 'Exported';
                    });
                  },
                  icon: const Icon(Icons.download, size: 14),
                  label: const Text('Unduh PDF', style: TextStyle(fontSize: 12)),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppTheme.primary,
                    foregroundColor: Colors.white,
                    padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  void _showCreateReportDialog(BuildContext context, AppStateProvider appState) {
    String selectedKeyword = appState.userKeywords.first;
    DateTimeRange? selectedDateRange;

    showDialog(
      context: context,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setDialogState) {
            return AlertDialog(
              backgroundColor: AppTheme.surfaceCard,
              title: const Text('Buat Laporan Baru', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              content: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.stretch,
                children: [
                  // Keyword Dropdown
                  const Text('Pilih Kata Kunci:', style: TextStyle(color: AppTheme.textSecondary, fontSize: 12)),
                  const SizedBox(height: 6),
                  DropdownButtonFormField<String>(
                    dropdownColor: AppTheme.surfaceCard,
                    value: selectedKeyword,
                    decoration: const InputDecoration(contentPadding: EdgeInsets.symmetric(horizontal: 12)),
                    items: appState.userKeywords.map((kw) {
                      return DropdownMenuItem<String>(
                        value: kw,
                        child: Text(kw, style: const TextStyle(fontSize: 13)),
                      );
                    }).toList(),
                    onChanged: (val) {
                      if (val != null) {
                        setDialogState(() {
                          selectedKeyword = val;
                        });
                      }
                    },
                  ),
                  const SizedBox(height: 16),

                  // Date range picker
                  const Text('Rentang Tanggal:', style: TextStyle(color: AppTheme.textSecondary, fontSize: 12)),
                  const SizedBox(height: 6),
                  ElevatedButton(
                    onPressed: () async {
                      final range = await showDateRangePicker(
                        context: context,
                        firstDate: DateTime.now().subtract(const Duration(days: 90)),
                        lastDate: DateTime.now(),
                      );
                      if (range != null) {
                        setDialogState(() {
                          selectedDateRange = range;
                        });
                      }
                    },
                    style: ElevatedButton.styleFrom(backgroundColor: AppTheme.darkBg),
                    child: Text(
                      selectedDateRange == null
                          ? 'Pilih Tanggal'
                          : '${DateFormat('dd/MM/yyyy').format(selectedDateRange!.start)} - ${DateFormat('dd/MM/yyyy').format(selectedDateRange!.end)}',
                      style: const TextStyle(color: Colors.white, fontSize: 13),
                    ),
                  ),
                ],
              ),
              actions: [
                TextButton(
                  onPressed: () => Navigator.pop(context),
                  child: const Text('Batal', style: TextStyle(color: AppTheme.textSecondary)),
                ),
                ElevatedButton(
                  onPressed: selectedDateRange == null
                      ? null
                      : () {
                          // Tambahkan laporan ke data dummy lokal
                          setState(() {
                            _reports.add({
                              'id': _reports.length + 1,
                              'title': 'Laporan Sentimen ($selectedKeyword)',
                              'keyword': selectedKeyword,
                              'period': '${DateFormat('dd MMM').format(selectedDateRange!.start)} - ${DateFormat('dd MMM yyyy').format(selectedDateRange!.end)}',
                              'status': 'Generated',
                              'created_at': DateFormat('dd MMM yyyy').format(DateTime.now()),
                            });
                          });
                          Navigator.pop(context);
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text('Berhasil men-generate laporan baru!')),
                          );
                        },
                  style: ElevatedButton.styleFrom(backgroundColor: AppTheme.primary),
                  child: const Text('Generate', style: TextStyle(color: Colors.white)),
                ),
              ],
            );
          },
        );
      },
    );
  }

  void _simulatePdfPreview(BuildContext context, String reportTitle) {
    showDialog(
      context: context,
      builder: (context) {
        return Dialog.fullscreen(
          backgroundColor: Colors.grey[900],
          child: Scaffold(
            appBar: AppBar(
              title: Text(reportTitle, style: const TextStyle(fontSize: 14)),
              leading: IconButton(
                icon: const Icon(Icons.close),
                onPressed: () => Navigator.pop(context),
              ),
            ),
            body: Container(
              color: Colors.white,
              margin: const EdgeInsets.all(24),
              padding: const EdgeInsets.all(24),
              child: const SingleChildScrollView(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'LAPORAN ANALISIS MEDIA MONITORING',
                      style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                    Text(
                      'SEKRETARIAT DPRD PROVINSI BANTEN',
                      style: TextStyle(color: Colors.black54, fontSize: 12),
                    ),
                    Divider(color: Colors.black87, thickness: 2, height: 24),
                    Text(
                      'Ringkasan Eksekutif:',
                      style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                    SizedBox(height: 8),
                    Text(
                      'Berdasarkan pengumpulan data berita media digital terkait kata kunci pencarian yang ditentukan, sentimen publik terhadap DPRD Banten pada periode pelaporan ini didominasi oleh pemberitaan Netral (50%) diikuti oleh sentimen Positif (30%) dan Negatif (20%).',
                      style: TextStyle(color: Colors.black87, fontSize: 11),
                    ),
                    SizedBox(height: 20),
                    Text(
                      'Rasio Distribusi Sentimen:',
                      style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                    SizedBox(height: 8),
                    Text('• Positif: 12 Berita\n• Netral: 20 Berita\n• Negatif: 8 Berita', style: TextStyle(color: Colors.black87, fontSize: 11)),
                    SizedBox(height: 20),
                    Text(
                      'Daftar Topik Utama:',
                      style: TextStyle(color: Colors.black, fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                    SizedBox(height: 8),
                    Text('1. Pembahasan APBD Banten\n2. Peraturan Daerah Tenaga Kerja Lokal\n3. Reses Persidangan Legislatif', style: TextStyle(color: Colors.black87, fontSize: 11)),
                  ],
                ),
              ),
            ),
          ),
        );
      },
    );
  }
}
