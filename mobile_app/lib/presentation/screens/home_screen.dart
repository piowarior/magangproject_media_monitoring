import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:fl_chart/fl_chart.dart';
import '../providers/app_state_provider.dart';
import '../../core/theme/app_theme.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final appState = Provider.of<AppStateProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('COMMAND CENTER'),
        actions: [
          IconButton(
            icon: Stack(
              children: [
                const Icon(Icons.notifications_outlined),
                if (appState.unreadNotifCount > 0)
                  Positioned(
                    right: 2,
                    top: 2,
                    child: CircleAvatar(
                      radius: 6,
                      backgroundColor: AppTheme.negative,
                      child: Text(
                        '${appState.unreadNotifCount}',
                        style: const TextStyle(fontSize: 8, color: Colors.white, fontWeight: FontWeight.bold),
                      ),
                    ),
                  ),
              ],
            ),
            onPressed: () {
              // Navigasi ke halaman notifikasi / tampilkan notifikasi dialog
              _showNotificationsDialog(context, appState);
            },
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Header Welcome
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      'Selamat Datang,',
                      style: TextStyle(color: AppTheme.textSecondary, fontSize: 14),
                    ),
                    Text(
                      appState.userName,
                      style: const TextStyle(
                        color: AppTheme.textPrimary,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ],
                ),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                  decoration: BoxDecoration(
                    color: AppTheme.primary.withOpacity(0.1),
                    border: Border.all(color: AppTheme.primary.withOpacity(0.5)),
                    borderRadius: BorderRadius.circular(20),
                  ),
                  child: Text(
                    appState.userRole,
                    style: const TextStyle(
                      color: AppTheme.accent,
                      fontSize: 12,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 24),

            // 1. Summary Cards (Total Berita, Positif, Netral, Negatif)
            const Text(
              'Ringkasan Sentimen',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            _buildSummaryGrid(appState),
            const SizedBox(height: 24),

            // 2. Line Chart: Tren Sentimen 7 Hari Terakhir
            const Text(
              'Tren Sentimen (7 Hari Terakhir)',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 16, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            _buildSentimentChart(),
            const SizedBox(height: 24),

            // 3. Top 5 Media Paling Aktif & Top 5 Isu Trending
            Row(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Top 5 Media Aktif',
                        style: TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 12),
                      _buildTopMediaList(appState),
                    ],
                  ),
                ),
                const SizedBox(width: 16),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text(
                        'Isu Terhangat',
                        style: TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 12),
                      _buildTopIssuesList(),
                    ],
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSummaryGrid(AppStateProvider appState) {
    return GridView.count(
      crossAxisCount: 2,
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      crossAxisSpacing: 12,
      mainAxisSpacing: 12,
      childAspectRatio: 1.5,
      children: [
        _buildStatCard(
          'Total Berita',
          '${appState.totalNewsCount}',
          Icons.newspaper,
          AppTheme.primary,
        ),
        _buildStatCard(
          'Positif',
          '${appState.positivePercentage.toStringAsFixed(1)}%',
          Icons.sentiment_satisfied_alt_outlined,
          AppTheme.positive,
        ),
        _buildStatCard(
          'Netral',
          '${appState.neutralPercentage.toStringAsFixed(1)}%',
          Icons.sentiment_neutral_outlined,
          AppTheme.neutral,
        ),
        _buildStatCard(
          'Negatif',
          '${appState.negativePercentage.toStringAsFixed(1)}%',
          Icons.sentiment_very_dissatisfied_outlined,
          AppTheme.negative,
        ),
      ],
    );
  }

  Widget _buildStatCard(String label, String value, IconData icon, Color color) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(
                  label,
                  style: const TextStyle(color: AppTheme.textSecondary, fontSize: 12),
                ),
                Icon(icon, color: color, size: 20),
              ],
            ),
            Text(
              value,
              style: const TextStyle(
                color: AppTheme.textPrimary,
                fontSize: 22,
                fontWeight: FontWeight.bold,
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildSentimentChart() {
    return Card(
      child: Padding(
        padding: const EdgeInsets.fromLTRB(12, 20, 20, 12),
        child: SizedBox(
          height: 180,
          child: LineChart(
            LineChartData(
              gridData: const FlGridData(show: false),
              titlesData: FlTitlesData(
                rightTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                topTitles: const AxisTitles(sideTitles: SideTitles(showTitles: false)),
                bottomTitles: AxisTitles(
                  sideTitles: SideTitles(
                    showTitles: true,
                    getTitlesWidget: (value, meta) {
                      const days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                      if (value >= 0 && value < days.length) {
                        return Padding(
                          padding: const EdgeInsets.only(top: 8.0),
                          child: Text(
                            days[value.toInt()],
                            style: const TextStyle(color: AppTheme.textSecondary, fontSize: 10),
                          ),
                        );
                      }
                      return const SizedBox.shrink();
                    },
                  ),
                ),
                leftTitles: AxisTitles(
                  sideTitles: SideTitles(
                    showTitles: true,
                    reservedSize: 28,
                    getTitlesWidget: (value, meta) {
                      return Text(
                        '${value.toInt()}',
                        style: const TextStyle(color: AppTheme.textSecondary, fontSize: 10),
                      );
                    },
                  ),
                ),
              ),
              borderData: FlBorderData(show: false),
              lineBarsData: [
                LineChartBarData(
                  spots: const [
                    FlSpot(0, 5),
                    FlSpot(1, 8),
                    FlSpot(2, 4),
                    FlSpot(3, 10),
                    FlSpot(4, 7),
                    FlSpot(5, 12),
                    FlSpot(6, 9),
                  ],
                  isCurved: true,
                  color: AppTheme.positive,
                  barWidth: 3,
                  dotData: const FlDotData(show: false),
                ),
                LineChartBarData(
                  spots: const [
                    FlSpot(0, 2),
                    FlSpot(1, 4),
                    FlSpot(2, 7),
                    FlSpot(3, 3),
                    FlSpot(4, 5),
                    FlSpot(5, 2),
                    FlSpot(6, 4),
                  ],
                  isCurved: true,
                  color: AppTheme.negative,
                  barWidth: 3,
                  dotData: const FlDotData(show: false),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildTopMediaList(AppStateProvider appState) {
    final topMedia = appState.topSources;
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12.0),
        child: Column(
          children: topMedia.entries.map((entry) {
            return Padding(
              padding: const EdgeInsets.symmetric(vertical: 6.0),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      entry.key,
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(color: AppTheme.textPrimary, fontSize: 12),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: AppTheme.primary.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Text(
                      '${entry.value} hal',
                      style: const TextStyle(color: AppTheme.primary, fontSize: 11, fontWeight: FontWeight.bold),
                    ),
                  ),
                ],
              ),
            );
          }).toList(),
        ),
      ),
    );
  }

  Widget _buildTopIssuesList() {
    final List<Map<String, dynamic>> issues = [
      {'issue': 'APBD Perubahan Banten', 'color': AppTheme.negative},
      {'issue': 'Pembangunan Jalan Selatan', 'color': AppTheme.positive},
      {'issue': 'Pemberdayaan Naker Lokal', 'color': AppTheme.positive},
      {'issue': 'Transparansi BUMD Banten', 'color': AppTheme.neutral},
      {'issue': 'Kesiapan Logistik Pemilu', 'color': AppTheme.neutral},
    ];

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(12.0),
        child: Column(
          children: issues.map((issueData) {
            return Padding(
              padding: const EdgeInsets.symmetric(vertical: 6.0),
              child: Row(
                children: [
                  CircleAvatar(
                    radius: 4,
                    backgroundColor: issueData['color'],
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: Text(
                      issueData['issue'],
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(color: AppTheme.textPrimary, fontSize: 12),
                    ),
                  ),
                ],
              ),
            );
          }).toList(),
        ),
      ),
    );
  }

  void _showNotificationsDialog(BuildContext context, AppStateProvider appState) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: AppTheme.surfaceCard,
          title: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              const Text('Notifikasi Real-time', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
              TextButton(
                onPressed: () {
                  appState.markAllNotificationsAsRead();
                },
                child: const Text('Read All', style: TextStyle(fontSize: 12, color: AppTheme.accent)),
              )
            ],
          ),
          content: SizedBox(
            width: double.maxFinite,
            child: ListView.builder(
              shrinkWrap: true,
              itemCount: appState.notifications.length,
              itemBuilder: (context, index) {
                final notif = appState.notifications[index];
                return ListTile(
                  contentPadding: EdgeInsets.zero,
                  leading: CircleAvatar(
                    backgroundColor: notif['isRead'] ? Colors.grey[800] : AppTheme.primary.withOpacity(0.2),
                    child: Icon(
                      notif['title'].contains('🚨') ? Icons.warning : Icons.info,
                      color: notif['isRead'] ? Colors.grey : AppTheme.accent,
                    ),
                  ),
                  title: Text(
                    notif['title'],
                    style: TextStyle(
                      fontSize: 14,
                      fontWeight: notif['isRead'] ? FontWeight.normal : FontWeight.bold,
                      color: AppTheme.textPrimary,
                    ),
                  ),
                  subtitle: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(notif['message'], style: const TextStyle(fontSize: 12, color: AppTheme.textSecondary)),
                      const SizedBox(height: 4),
                      Text(notif['time'], style: const TextStyle(fontSize: 10, color: Colors.grey)),
                    ],
                  ),
                  onTap: () {
                    appState.markNotificationAsRead(notif['id']);
                  },
                );
              },
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Tutup', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }
}
