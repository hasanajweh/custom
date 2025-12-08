# Quick Setup Guide

## ✅ What's Included

A complete Flutter mobile app that mirrors your Laravel web application structure:

### Features Implemented:
- ✅ Multi-tenant authentication (Network + School)
- ✅ Login screen with validation
- ✅ Role-based dashboards:
  - Teacher Dashboard
  - Admin Dashboard  
  - Supervisor Dashboard
- ✅ User profile screen
- ✅ Navigation structure
- ✅ Theme support (Light/Dark)
- ✅ RTL support (Arabic/English)
- ✅ State management with Provider
- ✅ Secure token storage
- ✅ API service layer

## 🚀 Quick Start

### 1. Update API URL

Edit `lib/config/app_config.dart`:

```dart
static const String apiBaseUrl = 'https://your-domain.com/api';
```

Or for local testing:
```dart
// Android Emulator
static const String apiBaseUrl = 'http://10.0.2.2:8000/api';

// iOS Simulator  
static const String apiBaseUrl = 'http://localhost:8000/api';

// Physical Device (replace YOUR_IP)
static const String apiBaseUrl = 'http://192.168.1.100:8000/api';
```

### 2. Install Dependencies

```bash
cd flutter_app
flutter pub get
```

### 3. Run the App

```bash
flutter run
```

## 📱 What You Can Do

1. **Login** - Use network slug, school slug, email, and password
2. **View Dashboard** - See role-specific dashboard
3. **Navigate** - Use bottom navigation
4. **Profile** - View and logout from profile

## 🔧 Next Steps to Complete

The app structure is ready! You need to:

1. **Add More API Endpoints** - Currently only auth endpoints are implemented
2. **Implement File Operations** - Upload, download, list files
3. **Add More Screens** - File browser, user management, etc.
4. **Connect Real Data** - Link dashboards to actual API data
5. **Add Notifications** - Implement push notifications

## 📂 File Structure Overview

```
lib/
├── config/
│   └── app_config.dart          # API URL configuration
├── models/                       # Data models
│   ├── user.dart
│   ├── school.dart
│   ├── file_submission.dart
│   └── user_context.dart
├── providers/                    # State management
│   ├── auth_provider.dart
│   ├── theme_provider.dart
│   └── locale_provider.dart
├── routes/
│   └── app_router.dart          # Navigation routes
├── screens/
│   ├── auth/login_screen.dart
│   ├── home/home_screen.dart
│   ├── teacher/teacher_dashboard_screen.dart
│   ├── admin/admin_dashboard_screen.dart
│   ├── supervisor/supervisor_dashboard_screen.dart
│   └── profile/profile_screen.dart
├── services/
│   ├── api_service.dart         # All API calls
│   └── storage_service.dart     # Local storage
├── theme/
│   └── app_theme.dart           # App theming
├── utils/
│   └── app_localizations.dart   # Translations
└── widgets/
    ├── main_scaffold.dart       # Main layout
    └── stat_card.dart           # Reusable cards
```

## 🎨 UI Customization

The app uses your brand colors:
- Primary: Blue (#3B82F6)
- Secondary: Purple (#9333EA)
- Accent: Pink (#EC4899)

Edit `lib/theme/app_theme.dart` to customize colors and styling.

## 🔐 Authentication Flow

1. User enters: Network, School, Email, Password
2. API call to `/api/login`
3. Token saved securely
4. User data loaded
5. Route to appropriate dashboard based on role

## 💡 Tips

- All API calls go through `ApiService` - add new endpoints there
- State management uses Provider - add new providers as needed
- Screens follow the same structure as your Laravel views
- Use `MainScaffold` widget for consistent layout

## 🐛 Troubleshooting

**Build Errors?**
```bash
flutter clean
flutter pub get
flutter run
```

**API Connection Issues?**
- Check API URL in `app_config.dart`
- Verify Laravel backend is running
- Check CORS settings in Laravel
- For Android, ensure network security config is set

**Token Issues?**
- Tokens are stored in secure storage
- Clear app data if login persists incorrectly
- Check token expiration in Laravel Sanctum config

## 📚 Documentation

- Main README: `README.md`
- Backend setup: `../SANCTUM_SETUP.md`
- Flutter guide: `../FLUTTER_SETUP.md`
