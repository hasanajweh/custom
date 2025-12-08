import 'package:flutter/material.dart';
import '../screens/auth/login_screen.dart';
import '../screens/home/home_screen.dart';
import '../screens/teacher/teacher_dashboard_screen.dart';
import '../screens/admin/admin_dashboard_screen.dart';
import '../screens/supervisor/supervisor_dashboard_screen.dart';
import '../screens/profile/profile_screen.dart';
import '../screens/profile/change_password_screen.dart';
import '../screens/files/my_files_screen.dart';
import '../screens/files/upload_file_screen.dart';
import '../screens/files/file_detail_screen.dart';
import '../screens/files/upload_type_selector_screen.dart';
import '../screens/files/upload_general_file_screen.dart';
import '../screens/files/upload_plan_file_screen.dart';
import '../models/file_submission.dart';

class AppRouter {
  static const String login = '/login';
  static const String home = '/home';
  static const String teacherDashboard = '/teacher/dashboard';
  static const String adminDashboard = '/admin/dashboard';
  static const String supervisorDashboard = '/supervisor/dashboard';
  static const String profile = '/profile';
  static const String changePassword = '/change-password';
  static const String myFiles = '/files';
  static const String uploadFile = '/upload';
  static const String uploadTypeSelector = '/upload-type-selector';
  static const String uploadGeneralFile = '/upload-general';
  static const String uploadPlanFile = '/upload-plan';
  static const String fileDetail = '/file-detail';

  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case login:
        return MaterialPageRoute(builder: (_) => const LoginScreen());
      
      case home:
        return MaterialPageRoute(builder: (_) => const HomeScreen());
      
      case teacherDashboard:
        return MaterialPageRoute(
          builder: (_) => const TeacherDashboardScreen(),
        );
      
      case adminDashboard:
        return MaterialPageRoute(
          builder: (_) => const AdminDashboardScreen(),
        );
      
      case supervisorDashboard:
        return MaterialPageRoute(
          builder: (_) => const SupervisorDashboardScreen(),
        );
      
      case profile:
        return MaterialPageRoute(builder: (_) => const ProfileScreen());
      
      case changePassword:
        return MaterialPageRoute(builder: (_) => const ChangePasswordScreen());
      
      case myFiles:
        return MaterialPageRoute(builder: (_) => const MyFilesScreen());
      
      case uploadFile:
        return MaterialPageRoute(builder: (_) => const UploadTypeSelectorScreen());
      
      case uploadTypeSelector:
        return MaterialPageRoute(builder: (_) => const UploadTypeSelectorScreen());
      
      case uploadGeneralFile:
        return MaterialPageRoute(builder: (_) => const UploadGeneralFileScreen());
      
      case uploadPlanFile:
        return MaterialPageRoute(builder: (_) => const UploadPlanFileScreen());
      
      case fileDetail:
        final file = settings.arguments as FileSubmission;
        return MaterialPageRoute(builder: (_) => FileDetailScreen(file: file));
      
      default:
        return MaterialPageRoute(
          builder: (_) => Scaffold(
            body: Center(
              child: Text('No route defined for ${settings.name}'),
            ),
          ),
        );
    }
  }
}
