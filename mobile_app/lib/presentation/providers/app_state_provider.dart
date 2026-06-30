import 'package:flutter/material.dart';
import '../../data/services/dummy_service.dart';

class AppStateProvider extends ChangeNotifier {
  bool _isLoggedIn = false;
  String _userEmail = '';
  String _userName = '';
  String _userRole = ''; // Operator or Pimpinan

  List<DummyNews> _allNews = [];
  List<DummyNews> _filteredNews = [];
  String _selectedSentiment = 'All'; // All, positive, neutral, negative
  String _searchQuery = '';
  String _selectedRegion = 'All';

  // Keyword list
  final List<String> _userKeywords = ['DPRD Banten', 'Kinerja DPRD Banten'];

  // Notifications
  final List<Map<String, dynamic>> _notifications = [
    {
      'id': 1,
      'title': '🚨 Laporan Krisis Sentimen Negatif',
      'message': 'Sentimen negatif untuk keyword "DPRD Banten" melonjak hingga 65% dalam 12 jam terakhir terkait isu banjir.',
      'time': '10 menit yang lalu',
      'isRead': false,
    },
    {
      'id': 2,
      'title': '📰 Berita Baru Terdeteksi',
      'message': 'Media "Radar Banten" menerbitkan berita baru: "Pembahasan APBD Banten Alot".',
      'time': '1 jam yang lalu',
      'isRead': true,
    },
  ];

  // Getters
  bool get isLoggedIn => _isLoggedIn;
  String get userEmail => _userEmail;
  String get userName => _userName;
  String get userRole => _userRole;
  List<DummyNews> get filteredNews => _filteredNews;
  String get selectedSentiment => _selectedSentiment;
  String get selectedRegion => _selectedRegion;
  List<String> get userKeywords => _userKeywords;
  List<Map<String, dynamic>> get notifications => _notifications;
  int get unreadNotifCount => _notifications.where((n) => !n['isRead']).length;

  AppStateProvider() {
    _allNews = DummyService.getDummyNewsList();
    _filteredNews = List.from(_allNews);
  }

  // Auth Actions
  bool login(String email, String password) {
    if (email.trim() == 'operator@dprd-banten.go.id' && password == 'Operator@12345') {
      _isLoggedIn = true;
      _userEmail = email;
      _userName = 'Operator Media Banten';
      _userRole = 'Operator';
      notifyListeners();
      return true;
    } else if (email.trim() == 'pimpinan@dprd-banten.go.id' && password == 'Pimpinan@12345') {
      _isLoggedIn = true;
      _userEmail = email;
      _userName = 'Ketua DPRD Banten';
      _userRole = 'Pimpinan';
      notifyListeners();
      return true;
    }
    return false;
  }

  void logout() {
    _isLoggedIn = false;
    _userEmail = '';
    _userName = '';
    _userRole = '';
    notifyListeners();
  }

  // Keywords Actions
  void addKeyword(String keyword) {
    if (keyword.isNotEmpty && !_userKeywords.contains(keyword)) {
      _userKeywords.add(keyword);
      notifyListeners();
    }
  }

  void removeKeyword(String keyword) {
    _userKeywords.remove(keyword);
    notifyListeners();
  }

  // Filter Actions
  void setSentimentFilter(String sentiment) {
    _selectedSentiment = sentiment;
    _applyFilters();
  }

  void setRegionFilter(String region) {
    _selectedRegion = region;
    _applyFilters();
  }

  void setSearchQuery(String query) {
    _searchQuery = query.toLowerCase();
    _applyFilters();
  }

  void _applyFilters() {
    _filteredNews = _allNews.where((news) {
      final matchesSentiment = _selectedSentiment == 'All' || news.sentiment == _selectedSentiment;
      final matchesRegion = _selectedRegion == 'All' || news.region == _selectedRegion;
      final matchesSearch = news.title.toLowerCase().contains(_searchQuery) ||
          news.content.toLowerCase().contains(_searchQuery) ||
          news.source.toLowerCase().contains(_searchQuery);

      return matchesSentiment && matchesRegion && matchesSearch;
    }).toList();
    notifyListeners();
  }

  // Notification Actions
  void markAllNotificationsAsRead() {
    for (var n in _notifications) {
      n['isRead'] = true;
    }
    notifyListeners();
  }

  void markNotificationAsRead(int id) {
    final idx = _notifications.indexWhere((n) => n['id'] == id);
    if (idx != -1) {
      _notifications[idx]['isRead'] = true;
      notifyListeners();
    }
  }

  // Analytics helper getters
  int get totalNewsCount => _allNews.length;
  
  double get positivePercentage {
    if (_allNews.isEmpty) return 0;
    final count = _allNews.where((n) => n.sentiment == 'positive').length;
    return (count / _allNews.length) * 100;
  }

  double get negativePercentage {
    if (_allNews.isEmpty) return 0;
    final count = _allNews.where((n) => n.sentiment == 'negative').length;
    return (count / _allNews.length) * 100;
  }

  double get neutralPercentage {
    if (_allNews.isEmpty) return 0;
    final count = _allNews.where((n) => n.sentiment == 'neutral').length;
    return (count / _allNews.length) * 100;
  }

  Map<String, int> get topSources {
    Map<String, int> map = {};
    for (var n in _allNews) {
      map[n.source] = (map[n.source] ?? 0) + 1;
    }
    // Sorting map
    var sortedEntries = map.entries.toList()..sort((e1, e2) => e2.value.compareTo(e1.value));
    return Map.fromEntries(sortedEntries.take(5));
  }
}
