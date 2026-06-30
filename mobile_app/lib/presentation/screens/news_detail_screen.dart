import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../../core/theme/app_theme.dart';

class NewsDetailScreen extends StatelessWidget {
  final dynamic news;

  const NewsDetailScreen({super.key, required this.news});

  @override
  Widget build(BuildContext context) {
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

    return Scaffold(
      appBar: AppBar(
        title: const Text('DETAIL ANALISIS AI'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // Title
            Text(
              news.title,
              style: const TextStyle(
                color: AppTheme.textPrimary,
                fontSize: 18,
                fontWeight: FontWeight.bold,
                height: 1.4,
              ),
            ),
            const SizedBox(height: 16),

            // Metadata Row
            Row(
              children: [
                const Icon(Icons.source, size: 16, color: AppTheme.textSecondary),
                const SizedBox(width: 6),
                Text(
                  news.source,
                  style: const TextStyle(color: AppTheme.textSecondary, fontSize: 13, fontWeight: FontWeight.bold),
                ),
                const Spacer(),
                const Icon(Icons.calendar_today, size: 14, color: AppTheme.textSecondary),
                const SizedBox(width: 6),
                Text(
                  DateFormat('dd MMM yyyy, HH:mm').format(news.publishedAt),
                  style: const TextStyle(color: AppTheme.textSecondary, fontSize: 12),
                ),
              ],
            ),
            const Divider(height: 32, color: AppTheme.borderCard),

            // AI Sentiment Section (Confidence Score)
            _buildAiSentimentCard(sentimentColor, sentimentText, sentimentIcon),
            const SizedBox(height: 20),

            // News Content
            const Text(
              'Isi Berita',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              news.content,
              style: const TextStyle(color: AppTheme.textSecondary, fontSize: 14, height: 1.5),
            ),
            const SizedBox(height: 24),

            // Ensemble Model Breakdown
            const Text(
              'Rincian Klasifikasi Model AI (Ensemble)',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            _buildEnsembleBreakdownCard(news),
            const SizedBox(height: 30),

            // External link action button
            ElevatedButton.icon(
              onPressed: () {
                // Simulasi membuka browser
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text('Membuka browser untuk berita asli...')),
                );
              },
              icon: const Icon(Icons.open_in_browser),
              label: const Text('Buka Berita Asli'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.surfaceCard,
                foregroundColor: AppTheme.textPrimary,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                  side: const BorderSide(color: AppTheme.borderCard),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildAiSentimentCard(Color color, String text, IconData icon) {
    final confPct = (news.confidenceScore * 100).toStringAsFixed(1);
    return Card(
      color: color.withOpacity(0.08),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: BorderSide(color: color.withOpacity(0.3)),
      ),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Row(
          children: [
            CircleAvatar(
              radius: 24,
              backgroundColor: color.withOpacity(0.15),
              child: Icon(icon, color: color, size: 28),
            ),
            const SizedBox(width: 16),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    'Keputusan Sentimen AI',
                    style: TextStyle(color: AppTheme.textSecondary, fontSize: 12),
                  ),
                  const SizedBox(height: 2),
                  Text(
                    text,
                    style: TextStyle(color: color, fontSize: 20, fontWeight: FontWeight.bold),
                  ),
                ],
              ),
            ),
            Column(
              crossAxisAlignment: CrossAxisAlignment.end,
              children: [
                const Text(
                  'Akurasi/Keyakinan',
                  style: TextStyle(color: AppTheme.textSecondary, fontSize: 11),
                ),
                const SizedBox(height: 2),
                Text(
                  '$confPct%',
                  style: const TextStyle(color: AppTheme.textPrimary, fontSize: 18, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildEnsembleBreakdownCard(dynamic news) {
    // Generate logical mockup model scores based on the news sentiment
    double scoreA = news.sentiment == 'positive' ? 0.85 : (news.sentiment == 'negative' ? 0.15 : 0.50);
    double scoreB = news.sentiment == 'positive' ? 0.90 : (news.sentiment == 'negative' ? 0.10 : 0.52);
    double scoreC = news.sentiment == 'positive' ? 0.88 : (news.sentiment == 'negative' ? 0.12 : 0.48);

    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            _buildModelRow('Model A: Lexicon Based (Kamus Kata)', scoreA),
            const SizedBox(height: 12),
            _buildModelRow('Model B: Machine Learning (SVM)', scoreB),
            const SizedBox(height: 12),
            _buildModelRow('Model C: Deep Learning (Bi-LSTM)', scoreC),
            const Divider(height: 24, color: AppTheme.borderCard),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text(
                  'Kecepatan Pemrosesan AI',
                  style: TextStyle(color: AppTheme.textSecondary, fontSize: 12),
                ),
                Text(
                  '${(120 + (news.id * 15))} ms',
                  style: const TextStyle(color: AppTheme.accent, fontSize: 12, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildModelRow(String modelName, double score) {
    Color barColor;
    if (score > 0.6) {
      barColor = AppTheme.positive;
    } else if (score < 0.4) {
      barColor = AppTheme.negative;
    } else {
      barColor = AppTheme.neutral;
    }

    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(modelName, style: const TextStyle(color: AppTheme.textPrimary, fontSize: 12)),
            Text('${(score * 100).toInt()}%', style: TextStyle(color: barColor, fontSize: 12, fontWeight: FontWeight.bold)),
          ],
        ),
        const SizedBox(height: 6),
        ClipRRect(
          borderRadius: BorderRadius.circular(4),
          child: LinearProgressIndicator(
            value: score,
            backgroundColor: AppTheme.borderCard,
            color: barColor,
            minHeight: 8,
          ),
        ),
      ],
    );
  }
}
