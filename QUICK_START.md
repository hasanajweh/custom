# Quick Start: Flutter + Laravel Sanctum

## 🎯 What You Need

**YES, Laravel Sanctum is exactly what you need!** It's perfect for authenticating mobile apps with your Laravel multi-tenant backend.

## ✅ Backend Setup (Already Done!)

1. **Install Sanctum** (run this):
   ```bash
   composer install
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   php artisan migrate
   ```

2. **That's it!** The backend is configured with:
   - ✅ API authentication endpoints
   - ✅ Multi-tenant support (network + school)
   - ✅ Token-based authentication
   - ✅ User info and context switching endpoints

## 📱 What You Need to Do in Flutter

1. **Install packages** in `pubspec.yaml`:
   ```yaml
   dependencies:
     http: ^1.1.0
     shared_preferences: ^2.2.2
     flutter_secure_storage: ^9.0.0
   ```

2. **Create API service** (see `FLUTTER_SETUP.md` for full code)

3. **Login with network + school**:
   ```dart
   await ApiService.login(
     network: 'latin',
     school: 'latin1',
     email: 'user@example.com',
     password: 'password',
   );
   ```

4. **Use the token** - All subsequent API calls automatically include the token

## 🔗 API Base URL

Update in your Flutter `ApiService`:
- **Production**: `https://your-domain.com/api`
- **Local Dev (Android)**: `http://10.0.2.2:8000/api`
- **Local Dev (iOS)**: `http://localhost:8000/api`

## 📚 Full Documentation

- **Backend Setup**: See `SANCTUM_SETUP.md`
- **Flutter Integration**: See `FLUTTER_SETUP.md`

## 🎨 UI/UX Notes

Since you want the **same UI as your web app**:
1. Look at your Laravel Blade views in `resources/views/`
2. Replicate the UI in Flutter using Material/Cupertino widgets
3. Use the same color scheme, fonts, and layout
4. Make API calls using the `ApiService` instead of form submissions

## 🔐 Security

- Tokens are stored securely using `flutter_secure_storage`
- Each device gets its own token
- Users can logout from all devices
- Multi-tenant access control is enforced on the backend

## 💡 Key Points

1. **Sanctum is perfect** for mobile apps - it uses simple token authentication
2. **Multi-tenant works** - you pass network + school on login
3. **Same UI** - just rebuild your web UI in Flutter
4. **Same backend** - your existing Laravel code works with the API

Need help? Check the detailed guides in `FLUTTER_SETUP.md` and `SANCTUM_SETUP.md`!
