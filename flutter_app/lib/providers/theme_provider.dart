import 'package:flutter/material.dart';
import '../services/storage_service.dart';

class ThemeProvider with ChangeNotifier {
  ThemeMode _themeMode = ThemeMode.light;

  ThemeMode get themeMode => _themeMode;

  ThemeProvider() {
    _loadTheme();
  }

  Future<void> _loadTheme() async {
    final themeString = StorageService.getString('theme_mode') ?? 'light';
    _themeMode = themeString == 'dark' 
        ? ThemeMode.dark 
        : themeString == 'system'
            ? ThemeMode.system
            : ThemeMode.light;
    notifyListeners();
  }

  Future<void> setThemeMode(ThemeMode mode) async {
    _themeMode = mode;
    await StorageService.setString(
      'theme_mode',
      mode == ThemeMode.dark 
          ? 'dark' 
          : mode == ThemeMode.system
              ? 'system'
              : 'light',
    );
    notifyListeners();
  }

  void toggleTheme() {
    setThemeMode(
      _themeMode == ThemeMode.light 
          ? ThemeMode.dark 
          : ThemeMode.light,
    );
  }
}
