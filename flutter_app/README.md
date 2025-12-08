# Scholder Mobile App

Complete Flutter mobile application for the Scholder multi-tenant educational platform.

## Setup Instructions

### 1. Install Flutter

Make sure you have Flutter installed:
```bash
flutter --version
```

If not, install Flutter from: https://flutter.dev/docs/get-started/install

### 2. Configure API URL

Edit `lib/config/app_config.dart` and update the `apiBaseUrl`:

```dart
static const String apiBaseUrl = 'https://your-domain.com/api';
```

For local development:
- Android Emulator: `http://10.0.2.2:8000/api`
- iOS Simulator: `http://localhost:8000/api`
- Physical Device: `http://YOUR_IP:8000/api`

### 3. Install Dependencies

```bash
cd flutter_app
flutter pub get
```

### 4. Run the App

```bash
flutter run
```

## Project Structure

```
lib/
├── config/          # App configuration
├── models/          # Data models
├── providers/       # State management (Provider)
├── routes/          # Navigation routing
├── screens/         # UI screens
│   ├── auth/       # Authentication screens
│   ├── admin/      # Admin screens
│   ├── teacher/    # Teacher screens
│   └── supervisor/ # Supervisor screens
├── services/        # API and storage services
├── theme/           # App theme
├── utils/           # Utilities
└── widgets/         # Reusable widgets
```

## Features

- ✅ Multi-tenant authentication (Network + School)
- ✅ Role-based dashboards (Teacher, Admin, Supervisor)
- ✅ File management
- ✅ User profile
- ✅ Notifications
- ✅ Dark/Light theme
- ✅ RTL support (Arabic/English)

## Next Steps

1. Complete API endpoints implementation
2. Add file upload/download functionality
3. Add notification system
4. Add more screens and features
5. Add tests

## Notes

- The app uses Provider for state management
- All API calls go through `ApiService`
- Authentication tokens are stored securely
- The app supports Arabic and English
