import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:intl/intl.dart';
import '../providers/app_state_provider.dart';
import '../../core/theme/app_theme.dart';
import 'news_detail_screen.dart';

class MonitoringScreen extends StatefulWidget {
  const MonitoringScreen({super.key});

  @override
  State<MonitoringScreen> createState() => _MonitoringScreenState();
}

class _MonitoringScreenState extends State<MonitoringScreen> {
  final TextEditingController _searchController = TextEditingController();

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final appState = Provider.of<AppStateProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('MONITORING BERITA'),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          // 1. Search Bar
          Padding(
            padding: const EdgeInsets.fromLTRB(16, 16, 16, 8),
            child: TextField(
              controller: _searchController,
              onChanged: (val) => appState.setSearchQuery(val),
              style: const TextStyle(color: AppTheme.textPrimary),
              decoration: InputDecoration(
                hintText: 'Cari berita, media, atau topik...',
                prefixIcon: const Icon(Icons.search, color: AppTheme.textSecondary),
                suffixIcon: _searchController.text.isNotEmpty
                    ? IconButton(
                        icon: const Icon(Icons.clear, color: AppTheme.textSecondary),
                        onPressed: () {
                          _searchController.clear();
                          appState.setSearchQuery('');
                        },
                      )
                    : null,
              ),
            ),
          ),

          // 2. Filter: Sentiment Chips & Date Picker
          SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Row(
              children: [
                _buildFilterChip(appState, 'All', 'Semua'),
                const SizedBox(width: 8),
                _buildFilterChip(appState, 'positive', 'Positif', color: AppTheme.positive),
                const SizedBox(width: 8),
                _buildFilterChip(appState, 'neutral', 'Netral', color: AppTheme.neutral),
                const SizedBox(width: 8),
                _buildFilterChip(appState, 'negative', 'Negatif', color: AppTheme.negative),
                const SizedBox(width: 16),
                
                // Date picker trigger button
                ActionChip(
                  avatar: const Icon(Icons.calendar_today, size: 14, color: AppTheme.accent),
                  label: const Text('Filter Tanggal', style: TextStyle(fontSize: 12, color: AppTheme.textPrimary)),
                  backgroundColor: AppTheme.surfaceCard,
                  shape: const RoundedRectangleBorder(
                    borderRadius: BorderRadius.all(Radius.circular(8)),
                    side: BorderSide(color: AppTheme.borderCard),
                  ),
                  onPressed: () async {
                    final dateRange = await showDateRangePicker(
                      context: context,
                      firstDate: DateTime.now().subtract(const Duration(days: 30)),
                      lastDate: DateTime.now(),
                      builder: (context, child) {
                        return Theme(
                          data: Theme.of(context).copyWith(
                            colorScheme: const ColorScheme.dark(
                              primary: AppTheme.primary,
                              onPrimary: AppTheme.textPrimary,
                              surface: AppTheme.surfaceCard,
                              onSurface: AppTheme.textPrimary,
                            ),
                          ),
                          child: child!,
                        );
                      },
                    );
                    if (dateRange != null) {
                      // Apply date range filter (visual notification for simulation)
                      ScaffoldMessenger.of(context).showSnackBar(
                        SnackBar(
                          content: Text(
                            'Memfilter tanggal: ${DateFormat('dd MMM').format(dateRange.start)} - ${DateFormat('dd MMM').format(dateRange.end)}',
                          ),
                          duration: const Duration(seconds: 2),
                        ),
                      );
                    }
                  },
                ),
              ],
            ),
          ),

          // 3. News List
          Expanded(
            child: appState.filteredNews.isEmpty
                ? _buildEmptyState()
                : ListView.builder(
                    padding: const EdgeInsets.all(16.0),
                    itemCount: appState.filteredNews.length,
                    itemBuilder: (context, index) {
                      final news = appState.filteredNews[index];
                      return Padding(
                        padding: const EdgeInsets.only(bottom: 12.0),
                        child: _buildNewsCard(context, news),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterChip(AppStateProvider appState, String filterValue, String label, {Color? color}) {
    final isSelected = appState.selectedSentiment == filterValue;
    return ChoiceChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (selected) {
        if (selected) {
          appState.setSentimentFilter(filterValue);
        }
      },
      selectedColor: color?.withOpacity(0.3) ?? AppTheme.primary.withOpacity(0.3),
      backgroundColor: AppTheme.surfaceCard,
      checkmarkColor: color ?? AppTheme.accent,
      labelStyle: TextStyle(
        color: isSelected ? (color ?? AppTheme.accent) : AppTheme.textSecondary,
        fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
        fontSize: 12,
      ),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(8),
        side: BorderSide(
          color: isSelected ? (color ?? AppTheme.primary) : AppTheme.borderCard,
        ),
      ),
    );
  }

  Widget _buildNewsCard(BuildContext context, dynamic news) {
    Color sentimentColor;
    String sentimentText;
    IconData sentimentIcon;

    switch (news.sentiment) {
      case 'positive':
        sentimentColor = AppTheme.positive;
        sentimentText = 'Positif';
        sentimentIcon = Icons.sentiment_satisfied;
        break;
      case 'negative':
        sentimentColor = AppTheme.negative;
        sentimentText = 'Negatif';
        sentimentIcon = Icons.sentiment_very_dissatisfied;
        break;
      default:
        sentimentColor = AppTheme.neutral;
        sentimentText = 'Netral';
        sentimentIcon = Icons.sentiment_neutral;
    }

    return Card(
      child: InkWell(
        borderRadius: BorderRadius.circular(16),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (context) => NewsDetailScreen(news: news),
            ),
          );
        },
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header Card (Media, Wilayah & Sentiment Badge)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      const Icon(Icons.source_outlined, size: 14, color: AppTheme.textSecondary),
                      const SizedBox(width: 4),
                      Text(
                        news.source,
                        style: const TextStyle(
                          color: AppTheme.textSecondary,
                          fontSize: 12,
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      const SizedBox(width: 8),
                      Container(
                        padding: const EdgeInsets.symmetric(horizontal: 6, vertical: 2),
                        decoration: BoxDecoration(
                          color: AppTheme.borderCard,
                          borderRadius: BorderRadius.circular(4),
                        ),
                        child: Text(
                          news.region,
                          style: const TextStyle(color: AppTheme.textSecondary, fontSize: 10),
                        ),
                      ),
                    ],
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: sentimentColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(20),
                      border: Border.all(color: sentimentColor.withOpacity(0.5)),
                    ),
                    child: Row(
                      children: [
                        Icon(sentimentIcon, color: sentimentColor, size: 12),
                        const SizedBox(width: 4),
                        Text(
                          sentimentText,
                          style: TextStyle(
                            color: sentimentColor,
                            fontSize: 10,
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),

              // Title
              Text(
                news.title,
                maxLines: 2,
                overflow: TextOverflow.ellipsis,
                style: const TextStyle(
                  color: AppTheme.textPrimary,
                  fontSize: 14,
                  fontWeight: FontWeight.bold,
                  height: 1.3,
                ),
              ),
              const SizedBox(height: 12),

              // Footer Card (Date)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    DateFormat('dd MMM yyyy, HH:mm').format(news.publishedAt),
                    style: const TextStyle(color: AppTheme.textSecondary, fontSize: 11),
                  ),
                  const Row(
                    children: [
                      Text(
                        'Detail AI',
                        style: TextStyle(color: AppTheme.accent, fontSize: 11, fontWeight: FontWeight.bold),
                      ),
                      Icon(Icons.arrow_forward_ios, color: AppTheme.accent, size: 10),
                    ],
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildEmptyState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.search_off_outlined, size: 64, color: AppTheme.textSecondary.withOpacity(0.5)),
          const SizedBox(height: 16),
          const Text(
            'Tidak ada berita ditemukan',
            style: TextStyle(color: AppTheme.textPrimary, fontSize: 16, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          const Text(
            'Cobalah mengubah filter atau kata kunci pencarian Anda.',
            style: TextStyle(color: AppTheme.textSecondary, fontSize: 12),
          ),
        ],
      ),
    );
  }
}
