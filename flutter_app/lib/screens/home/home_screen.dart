import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../routes/app_router.dart';
import '../../utils/app_localizations.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;
    final localizations = AppLocalizations.of(context);

    if (user == null) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    // Route to appropriate dashboard based on role
    String route = AppRouter.teacherDashboard;
    if (user.isAdmin) {
      route = AppRouter.adminDashboard;
    } else if (user.isSupervisor) {
      route = AppRouter.supervisorDashboard;
    } else if (user.isTeacher) {
      route = AppRouter.teacherDashboard;
    }

    // Navigate to appropriate dashboard
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Navigator.of(context).pushReplacementNamed(route);
    });

    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const CircularProgressIndicator(),
            const SizedBox(height: 16),
            Text('Loading ${localizations.dashboard}...'),
          ],
        ),
      ),
    );
  }
}
