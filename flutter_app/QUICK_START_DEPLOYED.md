# 🚀 Quick Start: Connect to Deployed API

## ⚡ 30 Second Setup

1. **Open** `lib/config/app_config.dart`

2. **Change line 10:**
   ```dart
   static const String apiBaseUrl = 'https://YOUR-DEPLOYED-DOMAIN.com/api';
   ```
   
   Replace `YOUR-DEPLOYED-DOMAIN.com` with your actual Laravel backend URL!

3. **Run:**
   ```bash
   flutter run -d chrome
   ```

## ✅ That's It!

Your Flutter app will now connect to your deployed Laravel backend.

## 🔍 How to Find Your API URL

Your deployed Laravel API URL should be:
- `https://yourdomain.com/api` (if API is at root)
- `https://api.yourdomain.com/api` (if using subdomain)
- `https://yourdomain.com/api/v1` (if versioned)

## 🐛 Having Issues?

**CORS Error?** → Check `BACKEND_CORS_SETUP.md`

**Connection Error?** → Verify your API URL is correct

**401 Error?** → Check your email/password are correct

## 📝 Example

If your Laravel is deployed at `https://scholder.ajw.com`, then:

```dart
static const String apiBaseUrl = 'https://scholder.ajw.com/api';
```

**Done!** Login will now work with your deployed backend! 🎉
