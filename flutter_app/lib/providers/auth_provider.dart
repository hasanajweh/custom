import 'package:flutter/foundation.dart';
import '../services/api_service.dart';
import '../models/user.dart';
import '../models/user_context.dart';
import '../models/school.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  CurrentContext? _currentContext;
  List<UserContext> _availableContexts = [];
  bool _isLoading = false;
  bool _isAuthenticated = false;
  String? _error;

  User? get user => _user;
  CurrentContext? get currentContext => _currentContext;
  List<UserContext> get availableContexts => _availableContexts;
  bool get isLoading => _isLoading;
  bool get isAuthenticated => _isAuthenticated;
  String? get error => _error;

  Future<void> init() async {
    await ApiService.init();
    if (ApiService.isAuthenticated) {
      await checkAuth();
    }
  }

  Future<void> checkAuth() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await ApiService.getUser();
      _user = User.fromJson(data['user'] as Map<String, dynamic>);
      
      // Get available contexts
      _availableContexts = (data['available_contexts'] as List?)
              ?.map((ctx) => UserContext.fromJson(ctx as Map<String, dynamic>))
              .toList() ?? [];
      
      // Set current context from available contexts if we have any
      if (_availableContexts.isNotEmpty && _currentContext == null) {
        final ctx = _availableContexts.first;
        _currentContext = CurrentContext(
          network: Network(
            id: 0,
            name: ctx.networkName ?? 'Unknown',
            slug: ctx.networkSlug ?? '',
          ),
          school: School(
            id: ctx.schoolId,
            name: ctx.schoolName,
            slug: ctx.schoolSlug,
            networkId: 0,
          ),
        );
      }
      
      _isAuthenticated = true;
    } catch (e) {
      _isAuthenticated = false;
      _error = e.toString();
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> login({
    required String email,
    required String password,
    String? deviceName,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await ApiService.login(
        email: email,
        password: password,
        deviceName: deviceName,
      );

      _user = User.fromJson(data['user'] as Map<String, dynamic>);
      
      // Set current context if available and valid
      if (data['current_context'] != null) {
        try {
          final contextData = data['current_context'] as Map<String, dynamic>;
          if (contextData['network'] != null && contextData['school'] != null) {
            _currentContext = CurrentContext.fromJson(contextData);
          }
        } catch (e) {
          // If current_context parsing fails, we'll set it from available_contexts
          _currentContext = null;
        }
      }
      
      // If no current_context but we have available contexts, use the first one
      if (_currentContext == null && _availableContexts.isNotEmpty) {
        final ctx = _availableContexts.first;
        _currentContext = CurrentContext(
          network: Network(
            id: 0, // Will be set from context if needed
            name: ctx.networkName ?? 'Unknown',
            slug: ctx.networkSlug ?? '',
          ),
          school: School(
            id: ctx.schoolId,
            name: ctx.schoolName,
            slug: ctx.schoolSlug,
            networkId: 0,
          ),
        );
      }
      
      _availableContexts = (data['available_contexts'] as List)
          .map((ctx) => UserContext.fromJson(ctx as Map<String, dynamic>))
          .toList();
      _isAuthenticated = true;
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      _error = e.toString();
      notifyListeners();
      return false;
    }
  }

  Future<bool> switchContext({
    required String network,
    required String school,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final data = await ApiService.switchContext(
        network: network,
        school: school,
      );
      _currentContext = CurrentContext.fromJson(
        data['current_context'] as Map<String, dynamic>,
      );
      _isLoading = false;
      notifyListeners();
      return true;
    } catch (e) {
      _isLoading = false;
      _error = e.toString();
      notifyListeners();
      return false;
    }
  }

  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();

    try {
      await ApiService.logout();
    } catch (e) {
      // Continue with logout even if API call fails
    } finally {
      _user = null;
      _currentContext = null;
      _availableContexts = [];
      _isAuthenticated = false;
      _isLoading = false;
      _error = null;
      notifyListeners();
    }
  }

  void clearError() {
    _error = null;
    notifyListeners();
  }
}
