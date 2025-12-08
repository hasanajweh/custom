class AppConfig {
  // ============================================
  // API CONFIGURATION
  // ============================================
  // IMPORTANT: Update this to your deployed API URL
  
  // Option 1: Set via environment variable (recommended for production)
  // Run: flutter run --dart-define=API_BASE_URL=https://enterprise.scholders.com/api
  static const String apiBaseUrl = String.fromEnvironment(
    'API_BASE_URL',
    defaultValue: 'https://enterprise.scholders.com/api', // ✅ Your deployed API URL
  );
  
  // Option 2: Direct configuration (easier for quick testing)
  // Uncomment and set your deployed URL:
  // static const String apiBaseUrl = 'https://your-domain.com/api';
  
  // ============================================
  // ENVIRONMENT CONFIGURATIONS
  // ============================================
  // Quick switch between environments:
  
  // For LOCAL development:
  // static const String apiBaseUrl = 'http://localhost:8000/api';
  // For Android Emulator:
  // static const String apiBaseUrl = 'http://10.0.2.2:8000/api';
  // For iOS Simulator:
  // static const String apiBaseUrl = 'http://localhost:8000/api';
  // For Physical Device (local network):
  // static const String apiBaseUrl = 'http://YOUR_IP:8000/api';
  
  // For PRODUCTION (deployed Laravel):
  // static const String apiBaseUrl = 'https://your-domain.com/api';
  
  // App Configuration
  static const String appName = 'Scholder';
  static const String appVersion = '1.0.0';
  
  // Timeouts
  static const Duration connectTimeout = Duration(seconds: 30);
  static const Duration receiveTimeout = Duration(seconds: 30);
  
  // Pagination
  static const int defaultPageSize = 20;
  
  // File Upload
  static const int maxFileSize = 100 * 1024 * 1024; // 100 MB
  static const List<String> allowedFileTypes = [
    'pdf',
    'doc',
    'docx',
    'xls',
    'xlsx',
    'ppt',
    'pptx',
    'txt',
    'jpg',
    'jpeg',
    'png',
    'zip',
    'rar',
  ];
  
  // Debug mode
  static const bool debugMode = bool.fromEnvironment('DEBUG', defaultValue: false);
  
  // Log API requests (useful for debugging)
  static void log(String message) {
    if (debugMode) {
      print('[API] $message');
    }
  }
}
