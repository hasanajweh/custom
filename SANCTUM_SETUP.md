# Laravel Sanctum Setup for Multi-Tenant Mobile App

## ✅ What Has Been Set Up

### 1. Laravel Sanctum Installation
- ✅ Added `laravel/sanctum` to `composer.json`
- ✅ Added `HasApiTokens` trait to User model
- ✅ Configured API guard in `config/auth.php`
- ✅ Enabled API routes in `bootstrap/app.php`

### 2. API Authentication
- ✅ Created `AuthController` with login, logout, user info, and context switching
- ✅ Multi-tenant aware authentication (requires network + school)
- ✅ Token-based authentication for mobile apps

### 3. Middleware
- ✅ Created `IdentifyTenantApi` middleware for API tenant context
- ✅ Integrated with API middleware stack

### 4. Routes
- ✅ API routes configured in `routes/api.php`
- ✅ Public login endpoint
- ✅ Protected endpoints with `auth:sanctum` middleware

## 📋 Installation Steps

### Step 1: Install Dependencies

```bash
composer install
```

This will install Laravel Sanctum.

### Step 2: Publish Sanctum Configuration

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

This creates the `personal_access_tokens` table.

### Step 4: Configure Sanctum for Mobile Apps

Edit `config/sanctum.php` (after publishing) and ensure:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

For mobile apps, this doesn't matter as much since we're using token authentication, not session cookies.

### Step 5: Update .env (Optional)

Add to your `.env` file:

```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
```

## 🔐 API Endpoints

### POST `/api/login`
Login and get authentication token.

**Request Body:**
```json
{
    "network": "latin",
    "school": "latin1",
    "email": "user@example.com",
    "password": "password",
    "device_name": "iPhone 15 Pro" // optional
}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "teacher",
        "is_super_admin": false,
        "is_main_admin": false
    },
    "current_context": {
        "network": {
            "id": 1,
            "slug": "latin",
            "name": "Latin Schools"
        },
        "school": {
            "id": 1,
            "slug": "latin1",
            "name": "Latin School 1"
        }
    },
    "available_contexts": [
        {
            "school_id": 1,
            "school_slug": "latin1",
            "school_name": "Latin School 1",
            "network_slug": "latin",
            "network_name": "Latin Schools",
            "role": "teacher"
        }
    ],
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### GET `/api/user`
Get authenticated user information.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "user@example.com",
        "role": "teacher",
        "is_super_admin": false,
        "is_main_admin": false
    },
    "available_contexts": [...]
}
```

### POST `/api/switch-context`
Switch to a different school context.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
    "network": "latin",
    "school": "latin2"
}
```

**Response:**
```json
{
    "current_context": {
        "network": {...},
        "school": {...}
    }
}
```

### POST `/api/logout`
Logout and revoke current token.

**Headers:**
```
Authorization: Bearer {token}
```

### POST `/api/logout-all`
Logout from all devices (revoke all tokens).

**Headers:**
```
Authorization: Bearer {token}
```

## 🔒 Security Features

1. **Multi-Tenant Validation**: Users must provide network and school on login
2. **Access Control**: Verifies user has access to the specified school
3. **Account Status Checks**: Validates user is active and not archived
4. **Token-Based Auth**: Secure token authentication for mobile apps
5. **Device Tracking**: Optional device name tracking for tokens

## 📱 Flutter Integration

See `FLUTTER_SETUP.md` for complete Flutter integration guide including:
- API service setup
- Authentication provider
- Login screen
- Token management
- Example code

## 🧪 Testing the API

### Using cURL

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "network": "latin",
    "school": "latin1",
    "email": "user@example.com",
    "password": "password"
  }'

# Get User (use token from login response)
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### Using Postman

1. Create a POST request to `/api/login`
2. Set Body to JSON with network, school, email, password
3. Copy the token from response
4. For protected routes, add header: `Authorization: Bearer {token}`

## 🚀 Next Steps

1. **Add Your API Endpoints**: Create controllers for your app features
2. **Add Tenant Middleware**: Use `IdentifyTenantApi` or route parameters
3. **Implement in Flutter**: Follow `FLUTTER_SETUP.md`
4. **Add Rate Limiting**: Already configured (60 requests/minute)
5. **Add API Versioning**: Consider `/api/v1/` prefix if needed

## 📝 Notes

- Tokens don't expire by default. You can add expiration in `config/sanctum.php`
- Each user can have multiple tokens (one per device)
- Tokens are stored in `personal_access_tokens` table
- The API respects the same multi-tenant access rules as your web app

## ⚠️ Important

- Always use HTTPS in production
- Keep tokens secure in your Flutter app (use `flutter_secure_storage`)
- Implement proper error handling in your mobile app
- Consider adding refresh token logic if you want token expiration
