import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../config/app_config.dart';

class ApiService {
  static const FlutterSecureStorage _storage = FlutterSecureStorage();
  static String? _token;
  static const String _tokenKey = 'auth_token';

  // Initialize token from storage
  static Future<void> init() async {
    _token = await _storage.read(key: _tokenKey);
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
    await _storage.write(key: _tokenKey, value: token);
  }

  // Clear token
  static Future<void> clearToken() async {
    _token = null;
    await _storage.delete(key: _tokenKey);
  }

  // Check if authenticated
  static bool get isAuthenticated => _token != null;

  // Login
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
    String? deviceName,
  }) async {
    final response = await http.post(
      Uri.parse('${AppConfig.apiBaseUrl}/login'),
      headers: await _getHeaders(includeAuth: false),
      body: jsonEncode({
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
      final error = data['message'] ?? 'Login failed';
      throw ApiException(error, response.statusCode);
    }
  }

  // Get authenticated user
  static Future<Map<String, dynamic>> getUser() async {
    final response = await http.get(
      Uri.parse('${AppConfig.apiBaseUrl}/user'),
      headers: await _getHeaders(),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw ApiException('Failed to get user', response.statusCode);
    }
  }

  // Switch school context
  static Future<Map<String, dynamic>> switchContext({
    required String network,
    required String school,
  }) async {
    final response = await http.post(
      Uri.parse('${AppConfig.apiBaseUrl}/switch-context'),
      headers: await _getHeaders(),
      body: jsonEncode({
        'network': network,
        'school': school,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw ApiException('Failed to switch context', response.statusCode);
    }
  }

  // Logout
  static Future<void> logout() async {
    try {
      await http.post(
        Uri.parse('${AppConfig.apiBaseUrl}/logout'),
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
        Uri.parse('${AppConfig.apiBaseUrl}/logout-all'),
        headers: await _getHeaders(),
      );
    } finally {
      await clearToken();
    }
  }

  // Generic GET request
  static Future<Map<String, dynamic>> get(
    String endpoint, {
    Map<String, String>? queryParameters,
    Map<String, String>? headers,
  }) async {
    var uri = Uri.parse('${AppConfig.apiBaseUrl}$endpoint');
    if (queryParameters != null && queryParameters.isNotEmpty) {
      uri = uri.replace(queryParameters: queryParameters);
    }

    final response = await http.get(
      uri,
      headers: await _getHeaders(additionalHeaders: headers),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      final error = jsonDecode(response.body);
      throw ApiException(
        error['message'] ?? 'Request failed',
        response.statusCode,
      );
    }
  }

  // Generic POST request
  static Future<Map<String, dynamic>> post(
    String endpoint, {
    Map<String, dynamic>? body,
    Map<String, String>? headers,
  }) async {
    final response = await http.post(
      Uri.parse('${AppConfig.apiBaseUrl}$endpoint'),
      headers: await _getHeaders(additionalHeaders: headers),
      body: body != null ? jsonEncode(body) : null,
    );

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonDecode(response.body);
    } else {
      final error = jsonDecode(response.body);
      throw ApiException(
        error['message'] ?? 'Request failed',
        response.statusCode,
      );
    }
  }

  // Generic PUT request
  static Future<Map<String, dynamic>> put(
    String endpoint, {
    Map<String, dynamic>? body,
    Map<String, String>? headers,
  }) async {
    final response = await http.put(
      Uri.parse('${AppConfig.apiBaseUrl}$endpoint'),
      headers: await _getHeaders(additionalHeaders: headers),
      body: body != null ? jsonEncode(body) : null,
    );

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonDecode(response.body);
    } else {
      final error = jsonDecode(response.body);
      throw ApiException(
        error['message'] ?? 'Request failed',
        response.statusCode,
      );
    }
  }

  // Generic DELETE request
  static Future<void> delete(
    String endpoint, {
    Map<String, String>? headers,
  }) async {
    final response = await http.delete(
      Uri.parse('${AppConfig.apiBaseUrl}$endpoint'),
      headers: await _getHeaders(additionalHeaders: headers),
    );

    if (response.statusCode < 200 || response.statusCode >= 300) {
      final error = jsonDecode(response.body);
      throw ApiException(
        error['message'] ?? 'Request failed',
        response.statusCode,
      );
    }
  }

  // Multipart POST for file uploads
  static Future<Map<String, dynamic>> postMultipart(
    String endpoint,
    Map<String, String> fields,
    String fileKey,
    List<int> fileBytes,
    String fileName,
  ) async {
    final request = http.MultipartRequest(
      'POST',
      Uri.parse('${AppConfig.apiBaseUrl}$endpoint'),
    );

    request.headers.addAll(await _getHeaders());
    request.fields.addAll(fields);
    request.files.add(
      http.MultipartFile.fromBytes(
        fileKey,
        fileBytes,
        filename: fileName,
      ),
    );

    final streamedResponse = await request.send();
    final response = await http.Response.fromStream(streamedResponse);

    if (response.statusCode >= 200 && response.statusCode < 300) {
      return jsonDecode(response.body);
    } else {
      final error = jsonDecode(response.body);
      throw ApiException(
        error['message'] ?? 'Upload failed',
        response.statusCode,
      );
    }
  }
}

class ApiException implements Exception {
  final String message;
  final int? statusCode;

  ApiException(this.message, [this.statusCode]);

  @override
  String toString() => message;
}
