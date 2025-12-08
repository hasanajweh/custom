import 'package:flutter/material.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';

class SupervisorDashboardScreen extends StatelessWidget {
  const SupervisorDashboardScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);

    return MainScaffold(
      currentIndex: 0,
      title: localizations.dashboard,
      body: const Center(
        child: Text('Supervisor Dashboard'),
      ),
    );
  }
}
