import 'package:flutter/material.dart';
import '../services/storage_service.dart';

class LocaleProvider with ChangeNotifier {
  Locale _locale = const Locale('en');

  Locale get locale => _locale;

  LocaleProvider() {
    _loadLocale();
  }

  Future<void> _loadLocale() async {
    final localeString = StorageService.getString('locale') ?? 'en';
    _locale = Locale(localeString);
    notifyListeners();
  }

  Future<void> setLocale(Locale locale) async {
    _locale = locale;
    await StorageService.setString('locale', locale.languageCode);
    notifyListeners();
  }

  void toggleLocale() {
    setLocale(_locale.languageCode == 'ar' 
        ? const Locale('en') 
        : const Locale('ar'));
  }
}
