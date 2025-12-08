import 'package:flutter/material.dart';
import '../routes/app_router.dart';
import '../providers/auth_provider.dart';

class NavigationHandler {
  static void handleDrawerNavigation(BuildContext context, int index, AuthProvider authProvider) {
    final user = authProvider.user;
    if (user == null) return;

    String? route;
    
    switch (index) {
      case 0: // Dashboard
        if (user.isAdmin) {
          route = AppRouter.adminDashboard;
        } else if (user.isSupervisor) {
          route = AppRouter.supervisorDashboard;
        } else if (user.isTeacher) {
          route = AppRouter.teacherDashboard;
        }
        break;
      case 1: // Files or Review Files or Manage Users
        if (user.isTeacher) {
          route = AppRouter.myFiles;
        } else if (user.isSupervisor) {
          // route = AppRouter.reviewFiles; // TODO: Add route
        } else if (user.isAdmin) {
          // route = AppRouter.manageUsers; // TODO: Add route
        }
        break;
      case 2: // Upload File (Teacher only)
        if (user.isTeacher) {
          route = AppRouter.uploadFile;
        }
        break;
      case 3: // Notifications
        // route = AppRouter.notifications; // TODO: Add route
        break;
    }

    if (route != null) {
      Navigator.of(context).pushReplacementNamed(route);
    }
  }
}
