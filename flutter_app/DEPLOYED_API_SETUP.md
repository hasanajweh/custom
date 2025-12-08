# Connecting Flutter App to Deployed Laravel API

## 🎯 Quick Setup

### Step 1: Update API URL in Flutter App

Open `lib/config/app_config.dart` and change line 10:

```dart
static const String apiBaseUrl = 'https://your-actual-domain.com/api';
```

**Replace `your-actual-domain.com` with your deployed Laravel backend domain!**

Example:
```dart
static const String apiBaseUrl = 'https://api.scholder.com/api';
// OR
static const String apiBaseUrl = 'https://scholder.com/api';
// OR whatever your deployed URL is
```

### Step 2: Run the App

```bash
cd flutter_app
flutter run -d chrome
```

## 🔧 Alternative: Use Environment Variables

Instead of hardcoding, you can pass the API URL as a parameter:

```bash
# For Chrome/Web
flutter run -d chrome --dart-define=API_BASE_URL=https://your-domain.com/api

# For Android
flutter run -d android --dart-define=API_BASE_URL=https://your-domain.com/api

# For iOS
flutter run -d ios --dart-define=API_BASE_URL=https://your-domain.com/api
```

## ✅ What Should Work Now

1. **Login** - Uses email + password only (no network/school selection)
2. **API Calls** - All requests go to your deployed backend
3. **Authentication** - Token-based auth with Sanctum
4. **Multi-tenant** - Automatically detects user's school/network

## 🐛 Troubleshooting

### Error: XMLHttpRequest error / CORS Error

**Problem:** Your deployed backend isn't allowing requests from localhost.

**Solution:** See `BACKEND_CORS_SETUP.md` to configure CORS on your Laravel backend.

### Error: Connection refused

**Problem:** Wrong API URL or backend is down.

**Solution:**
1. Verify your API URL is correct
2. Test the API directly: `curl https://your-domain.com/api/login`
3. Check if your backend is running and accessible

### Error: 401 Unauthorized

**Problem:** Login credentials are wrong or token issue.

**Solution:**
1. Verify email/password are correct
2. Check backend logs for authentication errors
3. Ensure Sanctum is properly configured on backend

## 📝 Backend Requirements

Your deployed Laravel backend needs:

1. ✅ Laravel Sanctum installed and configured
2. ✅ CORS configured to allow your Flutter app origin
3. ✅ API routes accessible at `/api/*`
4. ✅ Login endpoint at `/api/login` accepting:
   ```json
   {
     "email": "user@example.com",
     "password": "password"
   }
   ```

## 🚀 Production Checklist

- [ ] API URL updated in `app_config.dart`
- [ ] Backend CORS configured (see `BACKEND_CORS_SETUP.md`)
- [ ] HTTPS enabled on deployed API
- [ ] Test login with real credentials
- [ ] Verify token storage works
- [ ] Test all API endpoints

## 💡 Pro Tips

1. **Create a config file for different environments:**
   ```dart
   // lib/config/environments.dart
   class Environments {
     static const String dev = 'http://localhost:8000/api';
     static const String prod = 'https://your-domain.com/api';
   }
   ```

2. **Use different configs for debug/release:**
   ```dart
   static const String apiBaseUrl = kDebugMode 
       ? 'http://localhost:8000/api' 
       : 'https://your-domain.com/api';
   ```

3. **Test with Postman first** to verify your API works before connecting Flutter
