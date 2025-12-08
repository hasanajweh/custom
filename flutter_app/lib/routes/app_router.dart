import 'package:flutter/material.dart';
import '../screens/auth/login_screen.dart';
import '../screens/home/home_screen.dart';
import '../screens/teacher/teacher_dashboard_screen.dart';
import '../screens/admin/admin_dashboard_screen.dart';
import '../screens/supervisor/supervisor_dashboard_screen.dart';
import '../screens/profile/profile_screen.dart';

class AppRouter {
  static const String login = '/login';
  static const String home = '/home';
  static const String teacherDashboard = '/teacher/dashboard';
  static const String adminDashboard = '/admin/dashboard';
  static const String supervisorDashboard = '/supervisor/dashboard';
  static const String profile = '/profile';

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
