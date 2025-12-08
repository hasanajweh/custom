# Flutter Mobile App Setup Guide

This guide will help you connect your Flutter mobile application to your multi-tenant Laravel backend using Laravel Sanctum.

## Prerequisites

1. **Laravel Backend Setup Complete**
   - Laravel Sanctum installed and configured
   - API routes configured
   - CORS enabled for mobile apps

2. **Flutter Setup**
   - Flutter SDK installed
   - Your Flutter project created

## Step 1: Install Required Flutter Packages

Add these dependencies to your `pubspec.yaml`:

```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.2
  flutter_secure_storage: ^9.0.0  # For storing tokens securely
  provider: ^6.1.1  # For state management (optional but recommended)
```

Run:
```bash
flutter pub get
```

## Step 2: Create API Service

Create `lib/services/api_service.dart`:

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService {
  static const String baseUrl = 'https://your-api-domain.com/api'; // Change this
  static const FlutterSecureStorage _storage = FlutterSecureStorage();
  static String? _token;

  // Initialize token from storage
  static Future<void> init() async {
    _token = await _storage.read(key: 'auth_token');
  }

  // Get headers with authentication
  static Future<Map<String, String>> _getHeaders({
    Map<String, String>? additionalHeaders,
    bool includeAuth = true,
  }) async {
    final headers = <String, String>{
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...?additionalHeaders,
    };

    if (includeAuth && _token != null) {
      headers['Authorization'] = 'Bearer $_token';
    }

    return headers;
  }

  // Save token
  static Future<void> saveToken(String token) async {
    _token = token;
    await _storage.write(key: 'auth_token', value: token);
  }

  // Clear token
  static Future<void> clearToken() async {
    _token = null;
    await _storage.delete(key: 'auth_token');
  }

  // Login
  static Future<Map<String, dynamic>> login({
    required String network,
    required String school,
    required String email,
    required String password,
    String? deviceName,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/login'),
      headers: await _getHeaders(includeAuth: false),
      body: jsonEncode({
        'network': network,
        'school': school,
        'email': email,
        'password': password,
        'device_name': deviceName ?? 'Flutter App',
      }),
    );

    final data = jsonDecode(response.body);

    if (response.statusCode == 200) {
      await saveToken(data['token']);
      return data;
    } else {
      throw Exception(data['message'] ?? 'Login failed');
    }
  }

  // Get authenticated user
  static Future<Map<String, dynamic>> getUser() async {
    final response = await http.get(
      Uri.parse('$baseUrl/user'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to get user');
    }
  }

  // Switch school context
  static Future<Map<String, dynamic>> switchContext({
    required String network,
    required String school,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/switch-context'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'network': network,
        'school': school,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to switch context');
    }
  }

  // Logout
  static Future<void> logout() async {
    try {
      await http.post(
        Uri.parse('$baseUrl/logout'),
        headers: await _getHeaders(),
      );
    } finally {
      await clearToken();
    }
  }

  // Logout from all devices
  static Future<void> logoutAll() async {
    try {
      await http.post(
        Uri.parse('$baseUrl/logout-all'),
        headers: await _getHeaders(),
      );
    } finally {
      await clearToken();
    }
  }

  // Generic GET request
  static Future<Map<String, dynamic>> get(
    String endpoint, {
    Map<String, String>? headers,
  }) async {
    final response = await http.get(
      Uri.parse('$baseUrl$endpoint'),
      headers: await _getHeaders(additionalHeaders: headers),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Request failed');
    }
  }

  // Generic POST request
  static Future<Map<String, dynamic>> post(
    String endpoint, {
    Map<String, dynamic>? body,
    Map<String, String>? headers,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl$endpoint'),
      headers: await _getHeaders(additionalHeaders: headers),
      body: body != null ? jsonEncode(body) : null,
    );

    if (response.statusCode == 200 || response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      final error = jsonDecode(response.body);
      throw Exception(error['message'] ?? 'Request failed');
    }
  }
}
```

## Step 3: Create Auth Provider (State Management)

Create `lib/providers/auth_provider.dart`:

```dart
import 'package:flutter/foundation.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  Map<String, dynamic>? _user;
  Map<String, dynamic>? _currentContext;
  List<Map<String, dynamic>>? _availableContexts;
  bool _isLoading = false;
  bool _isAuthenticated = false;

  Map<String, dynamic>? get user => _user;
  Map<String, dynamic>? get currentContext => _currentContext;
  List<Map<String, dynamic>>? get availableContexts => _availableContexts;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _isAuthenticated;

  Future<void> init() async {
    await ApiService.init();
    await checkAuth();
  }

  Future<void> checkAuth() async {
    try {
      final data = await ApiService.getUser();
      _user = data['user'];
      _availableContexts = List<Map<String, dynamic>>.from(data['available_contexts'] ?? []);
      _isAuthenticated = true;
      notifyListeners();
    } catch (e) {
      _isAuthenticated = false;
      notifyListeners();
    }
  }

  Future<bool> login({
    required String network,
    required String school,
    required String email,
    required String password,
    String? deviceName,
  }) async {
    _isLoading = true;
    notifyListeners();

    try {
      final data = await ApiService.login(
        network: network,
        school: school,
        email: email,
        password: password,
        deviceName: deviceName,
      );

      _user = data['user'];
      _currentContext = data['current_context'];
      _availableContexts = List<Map<String, dynamic>>.from(data['available_contexts'] ?? []);
      _isAuthenticated = true;
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      notifyListeners();
      rethrow;
    }
  }

  Future<void> switchContext({
    required String network,
    required String school,
  }) async {
    try {
      final data = await ApiService.switchContext(
        network: network,
        school: school,
      );
      _currentContext = data['current_context'];
      notifyListeners();
    } catch (e) {
      rethrow;
    }
  }

  Future<void> logout() async {
    await ApiService.logout();
    _user = null;
    _currentContext = null;
    _availableContexts = null;
    _isAuthenticated = false;
    notifyListeners();
  }
}
```

## Step 4: Create Login Screen

Create `lib/screens/login_screen.dart`:

```dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _networkController = TextEditingController();
  final _schoolController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text('Login'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: ListView(
            children: [
              TextFormField(
                controller: _networkController,
                decoration: const InputDecoration(
                  labelText: 'Network Slug',
                  hintText: 'e.g., latin',
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter network slug';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _schoolController,
                decoration: const InputDecoration(
                  labelText: 'School Slug',
                  hintText: 'e.g., latin1',
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter school slug';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _emailController,
                decoration: const InputDecoration(
                  labelText: 'Email',
                  hintText: 'user@example.com',
                ),
                keyboardType: TextInputType.emailAddress,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter email';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 16),
              TextFormField(
                controller: _passwordController,
                decoration: const InputDecoration(
                  labelText: 'Password',
                ),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter password';
                  }
                  return null;
                },
              ),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: authProvider.isLoading
                    ? null
                    : () async {
                        if (_formKey.currentState!.validate()) {
                          try {
                            await authProvider.login(
                              network: _networkController.text.trim(),
                              school: _schoolController.text.trim(),
                              email: _emailController.text.trim(),
                              password: _passwordController.text,
                            );
                            if (mounted) {
                              Navigator.of(context).pushReplacementNamed('/home');
                            }
                          } catch (e) {
                            if (mounted) {
                              ScaffoldMessenger.of(context).showSnackBar(
                                SnackBar(content: Text(e.toString())),
                              );
                            }
                          }
                        }
                      },
                child: authProvider.isLoading
                    ? const CircularProgressIndicator()
                    : const Text('Login'),
              ),
            ],
          ),
        ),
      ),
    );
  }

  @override
  void dispose() {
    _networkController.dispose();
    _schoolController.dispose();
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}
```

## Step 5: Update main.dart

Update your `lib/main.dart`:

```dart
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'providers/auth_provider.dart';
import 'screens/login_screen.dart';
import 'screens/home_screen.dart'; // You'll need to create this

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (_) => AuthProvider()..init(),
      child: MaterialApp(
        title: 'Your App Name',
        theme: ThemeData(
          primarySwatch: Colors.blue,
        ),
        home: Consumer<AuthProvider>(
          builder: (context, auth, _) {
            if (auth.isLoading) {
              return const Scaffold(
                body: Center(child: CircularProgressIndicator()),
              );
            }
            return auth.isAuthenticated
                ? const HomeScreen()
                : const LoginScreen();
          },
        ),
        routes: {
          '/login': (context) => const LoginScreen(),
          '/home': (context) => const HomeScreen(),
        },
      ),
    );
  }
}
```

## Step 6: API Configuration

### Update API Base URL

In `lib/services/api_service.dart`, update the `baseUrl`:

```dart
static const String baseUrl = 'https://your-domain.com/api';
```

### For Local Development

If testing with localhost:
- **iOS Simulator**: `http://localhost:8000/api`
- **Android Emulator**: `http://10.0.2.2:8000/api`
- **Physical Device**: `http://YOUR_IP:8000/api` (replace YOUR_IP with your computer's IP)

### Android Network Security Config

For Android, add `android/app/src/main/res/xml/network_security_config.xml`:

```xml
<?xml version="1.0" encoding="utf-8"?>
<network-security-config>
    <domain-config cleartextTrafficPermitted="true">
        <domain includeSubdomains="true">localhost</domain>
        <domain includeSubdomains="true">10.0.2.2</domain>
    </domain-config>
</network-security-config>
```

Update `android/app/src/main/AndroidManifest.xml`:

```xml
<application
    android:networkSecurityConfig="@xml/network_security_config"
    ...>
```

### iOS Info.plist (for localhost only)

Add to `ios/Runner/Info.plist`:

```xml
<key>NSAppTransportSecurity</key>
<dict>
    <key>NSAllowsArbitraryLoads</key>
    <true/>
</dict>
```

## Step 7: Backend Configuration

### Install Sanctum

Run in your Laravel project:
```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### Configure CORS

Laravel 11 handles CORS automatically, but ensure your `.env` has:

```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,your-domain.com
SESSION_DRIVER=cookie
SESSION_DOMAIN=null
```

### For Mobile Apps

Add to `config/sanctum.php`:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

## Step 8: Using the API in Your Flutter App

### Making Authenticated Requests

```dart
// Example: Get dashboard data
final data = await ApiService.get('/dashboard');

// Example: Post data
final result = await ApiService.post('/submit', body: {
  'field1': 'value1',
  'field2': 'value2',
});
```

### Switching School Context

If a user has access to multiple schools:

```dart
await authProvider.switchContext(
  network: 'latin',
  school: 'latin2',
);
```

## Testing

1. **Test Login**: Try logging in with valid credentials
2. **Test Token Storage**: Close and reopen app - should stay logged in
3. **Test API Calls**: Make authenticated requests to your API endpoints
4. **Test Logout**: Verify token is cleared

## Troubleshooting

### Token Not Persisting
- Check `flutter_secure_storage` permissions
- Ensure app has proper storage permissions

### CORS Errors
- Verify `SANCTUM_STATEFUL_DOMAINS` includes your domain
- Check Laravel CORS configuration

### 401 Unauthorized
- Verify token is being sent in headers
- Check if token expired (Sanctum tokens don't expire by default)
- Ensure user is still active in database

### Network Errors
- Verify base URL is correct
- Check device can reach server
- For Android, check network security config

## Next Steps

1. Create your home screen and other app screens
2. Implement your app's features using the `ApiService`
3. Add error handling and loading states
4. Implement refresh tokens if needed (requires backend changes)
5. Add push notifications if needed

## Security Notes

- Tokens are stored securely using `flutter_secure_storage`
- Always use HTTPS in production
- Implement token refresh if needed
- Handle token expiration gracefully
