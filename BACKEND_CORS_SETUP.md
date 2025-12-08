# Backend CORS Configuration for Deployed API

## ✅ What You Need to Configure

Since your Laravel backend is **deployed**, you need to ensure CORS is properly configured to allow requests from your Flutter app (especially when running locally).

## Step 1: Configure CORS in Laravel

Laravel 11 handles CORS automatically. Make sure your `config/cors.php` allows your Flutter app origins:

### Option 1: Allow All Origins (for development/testing)

Edit `config/cors.php` (create it if it doesn't exist):

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // Allow all origins

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
```

### Option 2: Specific Origins (recommended for production)

```php
<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Add your Flutter app origins (for web testing)
    'allowed_origins' => [
        'http://localhost:*',
        'http://127.0.0.1:*',
        'https://your-flutter-app-domain.com', // If you deploy Flutter web
    ],

    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/',  // Allow any localhost port
        '/^http:\/\/127\.0\.0\.1:\d+$/', // Allow any 127.0.0.1 port
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
```

## Step 2: Update .env File

In your deployed Laravel backend, ensure your `.env` has:

```env
APP_URL=https://your-domain.com
APP_ENV=production

# CORS Settings
SANCTUM_STATEFUL_DOMAINS=your-domain.com,www.your-domain.com

# If using Sanctum
SESSION_DRIVER=cookie
SESSION_DOMAIN=.your-domain.com  # Use dot prefix for subdomains
```

## Step 3: Publish CORS Config (if needed)

If `config/cors.php` doesn't exist:

```bash
php artisan config:publish cors
```

## Step 4: Clear Config Cache

After making changes:

```bash
php artisan config:clear
php artisan cache:clear
```

## Step 5: Verify API Endpoints

Make sure your deployed API is accessible:

```bash
# Test login endpoint
curl -X POST https://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"password"}'
```

## Common Issues

### CORS Errors

**Error:** `Access-Control-Allow-Origin` header missing

**Solution:**
1. Check `config/cors.php` exists and is configured
2. Clear config cache: `php artisan config:clear`
3. Ensure `allowed_origins` includes your origin

### Preflight Request Failing

**Error:** OPTIONS request returns 404 or 405

**Solution:**
1. Ensure `allowed_methods` includes 'OPTIONS'
2. Check web server (Apache/Nginx) isn't blocking OPTIONS requests

### 419 CSRF Token Mismatch

**For API endpoints, this shouldn't happen**, but if it does:
1. API routes are excluded from CSRF by default
2. Check `VerifyCsrfToken` middleware excludes `/api/*`

## Testing

Test CORS with a simple request:

```bash
# From your local machine
curl -X OPTIONS https://your-domain.com/api/login \
  -H "Origin: http://localhost:63167" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type" \
  -v
```

You should see `Access-Control-Allow-Origin` in the response headers.

## Security Notes

⚠️ **Production Recommendations:**

1. **Don't use `allowed_origins: ['*']` in production**
2. **Specify exact domains** your Flutter app will run from
3. **Use HTTPS** for your deployed API
4. **Enable rate limiting** on your API endpoints
5. **Monitor API usage** and set up proper logging

## Quick Fix for Testing

If you just need to test quickly, temporarily allow all origins:

```php
'allowed_origins' => ['*'],
```

Then restrict it once everything works!
