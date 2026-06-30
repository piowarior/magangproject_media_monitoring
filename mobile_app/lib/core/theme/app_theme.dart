import 'package:flutter/material.dart';

class AppTheme {
  // Warna Utama (Professional Dashboard - Dark Mode Theme)
  static const Color darkBg = Color(0xFF0F172A);      // Slate 900
  static const Color surfaceCard = Color(0xFF1E293B);  // Slate 800
  static const Color borderCard = Color(0xFF334155);   // Slate 700
  
  static const Color primary = Color(0xFF3B82F6);      // Blue 500
  static const Color accent = Color(0xFF06B6D4);       // Cyan 500
  
  // Sentimen Badge Colors
  static const Color positive = Color(0xFF10B981);     // Emerald 500
  static const Color negative = Color(0xFFEF4444);     // Red 500
  static const Color neutral = Color(0xFFF59E0B);      // Amber 500

  // Text Colors
  static const Color textPrimary = Color(0xFFF8FAFC);  // Slate 50
  static const Color textSecondary = Color(0xFF94A3B8); // Slate 400

  static ThemeData get darkTheme {
    return ThemeData(
      brightness: Brightness.dark,
      scaffoldBackgroundColor: darkBg,
      primaryColor: primary,
      colorScheme: const ColorScheme.dark(
        primary: primary,
        secondary: accent,
        surface: surfaceCard,
        background: darkBg,
      ),
      fontFamily: 'Inter',
      appBarTheme: const AppBarTheme(
        backgroundColor: surfaceCard,
        elevation: 0,
        centerTitle: true,
        titleTextStyle: TextStyle(
          color: textPrimary,
          fontSize: 18,
          fontWeight: FontWeight.bold,
        ),
      ),
      cardTheme: const CardThemeData(
        color: surfaceCard,
        elevation: 2,
        margin: EdgeInsets.all(0),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(16)),
          side: BorderSide(color: borderCard, width: 1),
        ),
      ),
      inputDecorationTheme: const InputDecorationTheme(
        filled: true,
        fillColor: Color(0xFF0F172A),
        labelStyle: TextStyle(color: textSecondary),
        hintStyle: TextStyle(color: textSecondary),
        enabledBorder: OutlineInputBorder(
          borderRadius: BorderRadius.all(Radius.circular(12)),
          borderSide: BorderSide(color: borderCard, width: 1),
        ),
        focusedBorder: OutlineInputBorder(
          borderRadius: BorderRadius.all(Radius.circular(12)),
          borderSide: BorderSide(color: primary, width: 1.5),
        ),
      ),
    );
  }
}
