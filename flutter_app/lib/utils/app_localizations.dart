import 'package:flutter/material.dart';

class AppLocalizations {
  final Locale locale;

  AppLocalizations(this.locale);

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations)!;
  }

  static const LocalizationsDelegate<AppLocalizations> delegate =
      _AppLocalizationsDelegate();

  static final Map<String, Map<String, String>> _localizedValues = {
    'en': {
      'app_name': 'Scholder',
      'login': 'Login',
      'email': 'Email',
      'password': 'Password',
      'network': 'Network',
      'school': 'School',
      'sign_in': 'Sign In',
      'dashboard': 'Dashboard',
      'my_files': 'My Files',
      'upload_file': 'Upload File',
      'notifications': 'Notifications',
      'profile': 'Profile',
      'logout': 'Logout',
      'welcome': 'Welcome',
      'welcome_back': 'Welcome back',
    },
    'ar': {
      'app_name': 'شولدر',
      'login': 'تسجيل الدخول',
      'email': 'البريد الإلكتروني',
      'password': 'كلمة المرور',
      'network': 'الشبكة',
      'school': 'المدرسة',
      'sign_in': 'تسجيل الدخول',
      'dashboard': 'لوحة التحكم',
      'my_files': 'ملفاتي',
      'upload_file': 'رفع ملف',
      'notifications': 'الإشعارات',
      'profile': 'الملف الشخصي',
      'logout': 'تسجيل الخروج',
      'welcome': 'مرحباً',
      'welcome_back': 'مرحباً بعودتك',
    },
  };

  String translate(String key) {
    return _localizedValues[locale.languageCode]?[key] ?? key;
  }

  String get appName => translate('app_name');
  String get login => translate('login');
  String get email => translate('email');
  String get password => translate('password');
  String get network => translate('network');
  String get school => translate('school');
  String get signIn => translate('sign_in');
  String get dashboard => translate('dashboard');
  String get myFiles => translate('my_files');
  String get uploadFile => translate('upload_file');
  String get notifications => translate('notifications');
  String get profile => translate('profile');
  String get logout => translate('logout');
  String get welcome => translate('welcome');
  String get welcomeBack => translate('welcome_back');
}

class _AppLocalizationsDelegate
    extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  bool isSupported(Locale locale) => ['en', 'ar'].contains(locale.languageCode);

  @override
  Future<AppLocalizations> load(Locale locale) async {
    return AppLocalizations(locale);
  }

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}
