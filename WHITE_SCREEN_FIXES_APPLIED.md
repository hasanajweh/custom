# White Screen Fixes Applied

## Summary
Fixed critical issues that could cause white screens in the multi-tenant Laravel application.

## Fixes Applied

### 1. Fixed Unsafe `tenant_route()` Call in JavaScript (CRITICAL)
**File**: `resources/views/layouts/school.blade.php:1504`

**Problem**: `tenant_route()` was called with potentially null `$school` variable, causing JavaScript errors that halt page rendering.

**Fix**: Added null check and try-catch block:
```php
@php
    $previewUrl = '#';
    if ($school && $school->network) {
        try {
            $previewUrl = tenant_route('school.admin.file-browser.preview-data', [$school, '__FILE_ID__']);
        } catch (\Exception $e) {
            \Log::warning('Failed to generate preview URL', ['error' => $e->getMessage()]);
        }
    }
@endphp
const previewDataUrlTemplate = @json($previewUrl);
```

### 2. Added Null Safety in Layout Header
**File**: `resources/views/layouts/school.blade.php:4-27`

**Problem**: Multiple variables could be null, causing undefined property errors.

**Fix**: Added null coalescing operators and null checks:
- `Auth::user()->school` → `auth()->check() && auth()->user()?->school ? auth()->user()->school : null`
- Added checks for all auth-related variables

### 3. Protected `tenant_route()` Calls in User Menu
**File**: `resources/views/layouts/school.blade.php:1155-1156, 1166`

**Problem**: `tenant_route()` calls without error handling could fail silently.

**Fix**: Wrapped in try-catch blocks:
```php
try {
    $profileUrl = $hasTenantContext && $school ? tenant_route('profile.edit', $school) : '#';
    $logoutUrl = $hasTenantContext && $school ? tenant_route('logout', $school) : '#';
} catch (\Exception $e) {
    $profileUrl = '#';
    $logoutUrl = '#';
}
```

### 4. Enhanced Middleware Error Handling
**Files**: 
- `app/Http/Middleware/SetBranch.php`
- `app/Http/Middleware/SetNetwork.php`

**Problem**: Middleware could fail silently, causing white screens without proper error messages.

**Fix**: Added:
- Parameter validation before database queries
- Try-catch blocks around all operations
- Detailed logging for debugging
- Clear error messages

**Example**:
```php
if (! $branchParam) {
    \Log::warning('SetBranch middleware: No branch parameter in route', [
        'route' => $request->route()?->getName(),
        'url' => $request->url(),
    ]);
    abort(404, 'Branch parameter is required');
}
```

## Testing Checklist

### 1. Clear All Caches
```bash
php artisan optimize:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
npm run build
```

### 2. Test Tenant Routes
- [ ] Visit: `/{network-slug}/{school-slug}/dashboard`
- [ ] Check browser console (F12) - should be no errors
- [ ] Check Network tab - all requests should return 200
- [ ] Verify page renders correctly

### 3. Test Context Switching
- [ ] Click context switch button in user menu
- [ ] Verify redirect works
- [ ] Check tenant context is maintained
- [ ] No white screen appears

### 4. Test Asset Loading
- [ ] Logo/images load correctly
- [ ] CSS/JS files load
- [ ] No 404 errors in Network tab

### 5. Test User Menu Links
- [ ] Profile link works
- [ ] Activity logs link works (for admins)
- [ ] Logout works
- [ ] No white screens on click

### 6. Test File Preview (Admin)
- [ ] Click preview button on a file
- [ ] Preview modal opens or file opens in new tab
- [ ] No JavaScript errors in console

## Debugging Commands

```bash
# Check route list
php artisan route:list | grep tenant

# Check middleware
php artisan route:list --path={network}

# Monitor logs in real-time
tail -f storage/logs/laravel.log

# Test database connection
php artisan tinker
>>> \App\Models\School::first()
>>> \App\Models\Network::first()
```

## Common Issues & Solutions

### Issue: Still seeing white screen
**Solution**: 
1. Check browser console for JavaScript errors
2. Check Network tab for failed requests
3. Check Laravel logs: `tail -f storage/logs/laravel.log`
4. Clear all caches and rebuild assets

### Issue: "Branch not found" error
**Solution**:
1. Verify route has `{branch:slug}` parameter
2. Check database has school with matching slug
3. Verify school belongs to the network in URL

### Issue: Assets not loading
**Solution**:
1. Run `npm run build` or `npm run dev`
2. Check `public/build` directory exists
3. Verify `APP_URL` in `.env` is correct
4. Clear browser cache

### Issue: Context switch not working
**Solution**:
1. Check session is working
2. Verify user has access to target school
3. Check `ActiveContext` service is working
4. Review logs for errors

## Next Steps

If issues persist:
1. **Capture Error Details**:
   - Browser console errors
   - Network tab response body
   - Laravel log snippet
   - Exact URL that fails

2. **Check Route Configuration**:
   - Verify tenant routes are in correct group
   - Check middleware order
   - Verify route parameter binding

3. **Verify Database**:
   - Schools exist with correct slugs
   - Networks exist with correct slugs
   - Relationships are correct

## Files Modified

1. `resources/views/layouts/school.blade.php` - Fixed null safety and error handling
2. `app/Http/Middleware/SetBranch.php` - Added error handling and logging
3. `app/Http/Middleware/SetNetwork.php` - Added error handling and logging
4. `WHITE_SCREEN_DEBUG_GUIDE.md` - Created comprehensive debugging guide

## Notes

- All fixes maintain backward compatibility
- Error handling is non-breaking (falls back to safe defaults)
- Logging added for easier debugging
- No breaking changes to existing functionality

