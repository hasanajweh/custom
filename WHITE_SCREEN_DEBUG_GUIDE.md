# Multi-Tenant Laravel White Screen Debug Guide

## Quick Diagnostic Checklist

### 1. Browser Console Check (CRITICAL)
- Open DevTools (F12) → Console tab
- Look for:
  - `Uncaught TypeError`
  - `Cannot read property of undefined`
  - `Failed to load resource (404, 500)`
- **If errors found**: JavaScript is halting page render → Fix JS errors first

### 2. Network Response Check
- DevTools → Network tab → Click the failing request
- Check:
  - **Status 500**: Backend error (check Laravel logs)
  - **Empty Response Body**: Exception swallowed or redirect issue
  - **HTML when expecting JSON**: Inertia/Livewire mismatch

### 3. Laravel Logs
```bash
tail -f storage/logs/laravel.log
```
Look for:
- `Tenant could not be identified`
- `Trying to access tenant route without initialized tenant`
- Any exception in middleware

### 4. Route Grouping Issues

**Current Setup:**
- Tenant routes: `{network:slug}/{branch:slug}/...`
- Middleware: `setlocale`, `setNetwork`, `setBranch`, `verify.tenant.access`

**Common Issues:**
- Routes defined outside tenant group
- Missing middleware on tenant routes
- Incorrect route parameter binding

### 5. Asset URL Issues

**Problem**: Logo/assets disappear after click
- **Cause**: Asset domain breaking or tenant context changed
- **Fix**: Always use `{{ asset('path/to/file') }}` not `/path/to/file`

### 6. CSRF Token Issues

**Symptoms**: White screen on POST requests
- **Fix**: Ensure `@csrf` in forms
- **AJAX**: Include CSRF token in headers

### 7. View Data Issues

**Problem**: Controller returns null data causing Blade errors
- **Test**: Return static view → If it loads, data is the issue
- **Check**: All variables passed to view are defined

### 8. JavaScript Errors in Layout

**Critical Line**: `resources/views/layouts/school.blade.php:1504`
```php
const previewDataUrlTemplate = @json(tenant_route('school.admin.file-browser.preview-data', [$school, '__FILE_ID__']));
```

**Issue**: `$school` might be null
**Fix**: Use safe fallback or check before calling

## Common Fixes Applied

### Fix 1: Safe tenant_route() in JavaScript
Changed line 1504 to use safe fallback:
```php
const previewDataUrlTemplate = @json($school ? tenant_route('school.admin.file-browser.preview-data', [$school, '__FILE_ID__']) : '#');
```

### Fix 2: Null-safe variable checks in layout
Added null checks for `$school`, `$network`, `$branch` in layout header

### Fix 3: Error handling in middleware
Added try-catch blocks in middleware to prevent silent failures

## Testing Steps

1. **Clear all caches:**
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
npm run build
```

2. **Test tenant routes:**
- Visit: `/{network-slug}/{school-slug}/dashboard`
- Check browser console for errors
- Check network tab for failed requests

3. **Test context switching:**
- Click context switch button
- Check if redirect works
- Verify tenant context is maintained

4. **Test asset loading:**
- Check if logo/images load
- Verify CSS/JS files load correctly

## Debugging Commands

```bash
# Check route list
php artisan route:list | grep tenant

# Check middleware
php artisan route:list --path={network}

# Clear all caches
php artisan optimize:clear

# Check logs
tail -f storage/logs/laravel.log

# Test database connection
php artisan tinker
>>> \App\Models\School::first()
```

## Still Having Issues?

Provide:
1. **URL path** that causes white screen
2. **Response body** from Network tab
3. **Console errors** (if any)
4. **Route group code** (tenant + central)
5. **Laravel log** snippet around the error time

