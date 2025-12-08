# 🔧 Fix Deployment Error

## Problem
Your deployment is failing because:
```
Required package "laravel/sanctum" is not present in the lock file.
```

This happens when you add a package to `composer.json` but don't update `composer.lock`.

## ✅ Solution

### Step 1: Update composer.lock locally

Run this in your local project:

```bash
composer update laravel/sanctum
```

Or if you want to update everything:

```bash
composer update
```

### Step 2: Commit and Push

```bash
git add composer.lock
git commit -m "Add laravel/sanctum to composer.lock"
git push
```

### Step 3: Redeploy

Once pushed, trigger a new deployment on Laravel Forge. It should work now!

## ⚠️ Important Notes

- **Always commit `composer.lock`** - This file locks dependency versions
- **Don't edit `composer.json` manually** - Use `composer require` instead
- **Run `composer update`** after adding new packages

## 🚀 After Deployment Succeeds

Once your backend is deployed:

1. **Update Flutter API URL** in `flutter_app/lib/config/app_config.dart`:
   ```dart
   static const String apiBaseUrl = 'https://enterprise.scholders.com/api';
   ```

2. **Run Flutter app**:
   ```bash
   cd flutter_app
   flutter run -d chrome
   ```

Your Flutter app will connect to your deployed backend! 🎉
