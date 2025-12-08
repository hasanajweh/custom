# 🔧 Fix: Missing `personal_access_tokens` Table

## ❌ Error

```
Table 'enterprise.personal_access_tokens' doesn't exist
```

This error occurs because Laravel Sanctum needs the `personal_access_tokens` table to store API tokens, but it hasn't been created yet.

## ✅ Solution

### Step 1: Migration File Created

I've created the migration file: `database/migrations/2025_01_01_000000_create_personal_access_tokens_table.php`

### Step 2: Run Migrations on Your Server

**On your deployed server (`enterprise.scholders.com`), run:**

```bash
php artisan migrate
```

This will create the `personal_access_tokens` table.

### Step 3: Verify

After running migrations, check if the table exists:

```bash
php artisan tinker
>>> Schema::hasTable('personal_access_tokens')
=> true
```

## 🚀 Complete Fix Steps

1. **Commit the migration file:**
   ```bash
   git add database/migrations/2025_01_01_000000_create_personal_access_tokens_table.php
   git commit -m "Add personal_access_tokens migration for Sanctum"
   git push
   ```

2. **Deploy to server** (or it should auto-deploy if using Forge)

3. **SSH into your server** and run:
   ```bash
   cd /home/forge/enterprise.scholders.com
   php artisan migrate
   ```

4. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## ✅ After This Fix

- ✅ API login will work
- ✅ Tokens will be stored in `personal_access_tokens` table
- ✅ Flutter app can authenticate successfully

## 📝 Note

The migration file has been created with the correct structure for Laravel Sanctum. It includes:
- `tokenable` polymorphic relationship (for User model)
- `name` (device name)
- `token` (unique token hash)
- `abilities` (permissions)
- `last_used_at` and `expires_at` (optional)
- Timestamps

## 🐛 If Migration Fails

If you get errors during migration:

1. Check database connection in `.env`
2. Verify database permissions
3. Check if table already exists: `SHOW TABLES LIKE 'personal_access_tokens';`
4. If exists but empty, you can skip: `php artisan migrate --pretend` to see what would run
