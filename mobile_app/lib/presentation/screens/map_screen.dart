import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import 'package:provider/provider.dart';
import '../providers/app_state_provider.dart';
import '../../core/theme/app_theme.dart';
import 'news_detail_screen.dart';

class MapScreen extends StatefulWidget {
  const MapScreen({super.key});

  @override
  State<MapScreen> createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  final MapController _mapController = MapController();

  // Koordinat Utama Kabupaten/Kota di Provinsi Banten
  final List<Map<String, dynamic>> _regionsCoords = [
    {
      'name': 'Serang Kota',
      'latlng': const LatLng(-6.1158, 106.1542),
      'sentiment': 'positive',
      'volume': 14,
    },
    {
      'name': 'Cilegon',
      'latlng': const LatLng(-6.0174, 106.0538),
      'sentiment': 'neutral',
      'volume': 8,
    },
    {
      'name': 'Tangerang Kota',
      'latlng': const LatLng(-6.1783, 106.6319),
      'sentiment': 'negative',
      'volume': 19,
    },
    {
      'name': 'Tangerang Selatan',
      'latlng': const LatLng(-6.2886, 106.7179),
      'sentiment': 'positive',
      'volume': 12,
    },
    {
      'name': 'Tangerang Kab',
      'latlng': const LatLng(-6.2755, 106.4678),
      'sentiment': 'neutral',
      'volume': 9,
    },
    {
      'name': 'Serang Kab',
      'latlng': const LatLng(-6.1017, 106.2412),
      'sentiment': 'positive',
      'volume': 11,
    },
    {
      'name': 'Lebak',
      'latlng': const LatLng(-6.3571, 106.2464),
      'sentiment': 'negative',
      'volume': 15,
    },
    {
      'name': 'Pandeglang',
      'latlng': const LatLng(-6.3079, 105.8398),
      'sentiment': 'neutral',
      'volume': 7,
    },
  ];

  @override
  Widget build(BuildContext context) {
    final appState = Provider.of<AppStateProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('PETA SENTIMEN BANTEN'),
      ),
      body: Stack(
        children: [
          // Flutter Map Component
          FlutterMap(
            mapController: _mapController,
            options: const MapOptions(
              initialCenter: LatLng(-6.2, 106.2), // Fokus di tengah Provinsi Banten
              initialZoom: 9.5,
              maxZoom: 13,
              minZoom: 8,
            ),
            children: [
              TileLayer(
                urlTemplate: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
                subdomains: const ['a', 'b', 'c', 'd'],
              ),
              MarkerLayer(
                markers: _regionsCoords.map((region) {
                  Color markerColor;
                  switch (region['sentiment']) {
                    case 'positive':
                      markerColor = AppTheme.positive;
                      break;
                    case 'negative':
                      markerColor = AppTheme.negative;
                      break;
                    default:
                      markerColor = AppTheme.neutral;
                  }

                  // Hitung ukuran marker berdasarkan volume berita
                  final double size = 30.0 + (region['volume'] * 1.5);

                  return Marker(
                    point: region['latlng'],
                    width: size,
                    height: size,
                    child: GestureDetector(
                      onTap: () {
                        _showRegionNewsBottomSheet(context, region['name'], appState);
                      },
                      child: Container(
                        decoration: BoxDecoration(
                          color: markerColor.withOpacity(0.2),
                          shape: BoxShape.circle,
                          border: Border.all(color: markerColor, width: 2),
                        ),
                        child: Center(
                          child: Container(
                            width: size * 0.5,
                            height: size * 0.5,
                            decoration: BoxDecoration(
                              color: markerColor,
                              shape: BoxShape.circle,
                            ),
                            child: Center(
                              child: Text(
                                '${region['volume']}',
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 10,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ),
                          ),
                        ),
                      ),
                    ),
                  );
                }).toList(),
              ),
            ],
          ),

          // Peta Floating Legend
          Positioned(
            bottom: 24,
            left: 16,
            right: 16,
            child: _buildLegendCard(),
          ),
        ],
      ),
    );
  }

  Widget _buildLegendCard() {
    return Card(
      color: AppTheme.surfaceCard.withOpacity(0.9),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(16),
        side: const BorderSide(color: AppTheme.borderCard),
      ),
      child: Padding(
        padding: const EdgeInsets.symmetric(horizontal: 16.0, vertical: 12.0),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            const Text(
              'Indikasi Sentimen:',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 11, fontWeight: FontWeight.bold),
            ),
            _buildLegendItem('Positif', AppTheme.positive),
            _buildLegendItem('Netral', AppTheme.neutral),
            _buildLegendItem('Negatif', AppTheme.negative),
          ],
        ),
      ),
    );
  }

  Widget _buildLegendItem(String label, Color color) {
    return Row(
      children: [
        Container(
          width: 10,
          height: 10,
          decoration: BoxDecoration(
            color: color,
            shape: BoxShape.circle,
          ),
        ),
        const SizedBox(width: 6),
        Text(
          label,
          style: const TextStyle(color: AppTheme.textSecondary, fontSize: 11),
        ),
      ],
    );
  }

  void _showRegionNewsBottomSheet(BuildContext context, String regionName, AppStateProvider appState) {
    // Filter berita dummy sesuai dengan nama region
    final regionNews = appState.filteredNews
        .where((news) => news.region.toLowerCase().contains(regionName.toLowerCase().split(' ')[0]))
        .toList();

    showModalBottomSheet(
      context: context,
      backgroundColor: AppTheme.surfaceCard,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return Container(
          padding: const EdgeInsets.all(16.0),
          height: 400,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Bottom sheet header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Berita di Wilayah: $regionName',
                    style: const TextStyle(color: AppTheme.textPrimary, fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close, color: AppTheme.textSecondary),
                    onPressed: () => Navigator.pop(context),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              
              // News list
              Expanded(
                child: regionNews.isEmpty
                    ? const Center(
                        child: Text(
                          'Belum ada berita mengenai wilayah ini.',
                          style: TextStyle(color: AppTheme.textSecondary),
                        ),
                      )
                    : ListView.builder(
                        itemCount: regionNews.length,
                        itemBuilder: (context, index) {
                          final news = regionNews[index];
                          Color badgeColor;
                          switch (news.sentiment) {
                            case 'positive':
                              badgeColor = AppTheme.positive;
                              break;
                            case 'negative':
                              badgeColor = AppTheme.negative;
                              break;
                            default:
                              badgeColor = AppTheme.neutral;
                          }

                          return Card(
                            margin: const EdgeInsets.only(bottom: 10),
                            color: AppTheme.darkBg,
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12),
                              side: const BorderSide(color: AppTheme.borderCard),
                            ),
                            child: ListTile(
                              title: Text(
                                news.title,
                                maxLines: 1,
                                overflow: TextOverflow.ellipsis,
                                style: const TextStyle(color: AppTheme.textPrimary, fontSize: 13, fontWeight: FontWeight.bold),
                              ),
                              subtitle: Text(
                                news.source,
                                style: const TextStyle(color: AppTheme.textSecondary, fontSize: 11),
                              ),
                              trailing: Icon(Icons.circle, color: badgeColor, size: 12),
                              onTap: () {
                                Navigator.pop(context);
                                Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                    builder: (context) => NewsDetailScreen(news: news),
                                  ),
                                );
                              },
                            ),
                          );
                        },
                      ),
              ),
            ],
          ),
        );
      },
    );
  }
}
