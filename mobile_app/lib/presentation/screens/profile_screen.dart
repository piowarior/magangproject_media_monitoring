import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/app_state_provider.dart';
import '../../core/theme/app_theme.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final TextEditingController _keywordController = TextEditingController();

  @override
  void dispose() {
    _keywordController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final appState = Provider.of<AppStateProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('PROFIL OPERATOR'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // 1. Profil Info Card
            _buildProfileCard(appState),
            const SizedBox(height: 24),

            // 2. Keyword Management (Langganan Keyword)
            const Text(
              'Kelola Kata Kunci Pantauan',
              style: TextStyle(color: AppTheme.textPrimary, fontSize: 15, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 12),
            _buildKeywordManagerCard(appState),
            const SizedBox(height: 32),

            // 3. Tombol Logout
            ElevatedButton.icon(
              onPressed: () {
                _showLogoutConfirmation(context, appState);
              },
              icon: const Icon(Icons.logout),
              label: const Text('Keluar dari Akun'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.negative.withOpacity(0.1),
                foregroundColor: AppTheme.negative,
                padding: const EdgeInsets.symmetric(vertical: 14),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                  side: const BorderSide(color: AppTheme.negative, width: 1),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileCard(AppStateProvider appState) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(20.0),
        child: Column(
          children: [
            CircleAvatar(
              radius: 40,
              backgroundColor: AppTheme.primary.withOpacity(0.2),
              child: const Icon(Icons.person, size: 48, color: AppTheme.accent),
            ),
            const SizedBox(height: 16),
            Text(
              appState.userName,
              style: const TextStyle(color: AppTheme.textPrimary, fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 4),
            Text(
              appState.userEmail,
              style: const TextStyle(color: AppTheme.textSecondary, fontSize: 13),
            ),
            const SizedBox(height: 12),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
              decoration: BoxDecoration(
                color: AppTheme.surfaceCard,
                borderRadius: BorderRadius.circular(20),
                border: Border.all(color: AppTheme.borderCard),
              ),
              child: Text(
                'Akses: ${appState.userRole}',
                style: const TextStyle(color: AppTheme.accent, fontSize: 12, fontWeight: FontWeight.bold),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildKeywordManagerCard(AppStateProvider appState) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            // List of active keywords
            Wrap(
              spacing: 8,
              runSpacing: 8,
              children: appState.userKeywords.map((kw) {
                return Chip(
                  backgroundColor: AppTheme.darkBg,
                  label: Text(kw, style: const TextStyle(fontSize: 12, color: AppTheme.textPrimary)),
                  deleteIcon: const Icon(Icons.close, size: 14, color: AppTheme.negative),
                  shape: const RoundedRectangleBorder(
                    borderRadius: BorderRadius.all(Radius.circular(8)),
                    side: BorderSide(color: AppTheme.borderCard),
                  ),
                  onDeleted: () {
                    appState.removeKeyword(kw);
                  },
                );
              }).toList(),
            ),
            const SizedBox(height: 16),

            // Form to add new keyword
            Row(
              children: [
                Expanded(
                  child: TextField(
                    controller: _keywordController,
                    style: const TextStyle(color: AppTheme.textPrimary, fontSize: 13),
                    decoration: const InputDecoration(
                      hintText: 'Tambah kata kunci...',
                      contentPadding: EdgeInsets.symmetric(horizontal: 12, vertical: 10),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                IconButton.filled(
                  style: IconButton.styleFrom(
                    backgroundColor: AppTheme.primary,
                  ),
                  icon: const Icon(Icons.add, color: Colors.white),
                  onPressed: () {
                    if (_keywordController.text.trim().isNotEmpty) {
                      appState.addKeyword(_keywordController.text.trim());
                      _keywordController.clear();
                    }
                  },
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  void _showLogoutConfirmation(BuildContext context, AppStateProvider appState) {
    showDialog(
      context: context,
      builder: (context) {
        return AlertDialog(
          backgroundColor: AppTheme.surfaceCard,
          title: const Text('Konfirmasi Logout', style: TextStyle(fontSize: 15, fontWeight: FontWeight.bold)),
          content: const Text('Apakah Anda yakin ingin keluar dari akun?', style: TextStyle(fontSize: 13, color: AppTheme.textSecondary)),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal', style: TextStyle(color: Colors.white)),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
                appState.logout();
              },
              style: ElevatedButton.styleFrom(backgroundColor: AppTheme.negative),
              child: const Text('Keluar', style: TextStyle(color: Colors.white)),
            ),
          ],
        );
      },
    );
  }
}
