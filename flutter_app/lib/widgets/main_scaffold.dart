import 'package:flutter/material.dart';
import '../utils/app_localizations.dart';
import '../routes/app_router.dart';

class MainScaffold extends StatelessWidget {
  final Widget body;
  final String title;
  final int currentIndex;

  const MainScaffold({
    super.key,
    required this.body,
    required this.title,
    this.currentIndex = 0,
  });

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final isRTL = Localizations.localeOf(context).languageCode == 'ar';

    return Scaffold(
      appBar: AppBar(
        title: Text(title),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_outlined),
            onPressed: () {
              // Navigate to notifications
            },
          ),
          IconButton(
            icon: const Icon(Icons.person),
            onPressed: () {
              Navigator.of(context).pushNamed(AppRouter.profile);
            },
          ),
        ],
      ),
      body: body,
      bottomNavigationBar: NavigationBar(
        selectedIndex: currentIndex,
        onDestinationSelected: (index) {
          // Handle navigation
        },
        destinations: [
          NavigationDestination(
            icon: const Icon(Icons.dashboard_outlined),
            selectedIcon: const Icon(Icons.dashboard),
            label: localizations.dashboard,
          ),
          NavigationDestination(
            icon: const Icon(Icons.folder_outlined),
            selectedIcon: const Icon(Icons.folder),
            label: localizations.myFiles,
          ),
          NavigationDestination(
            icon: const Icon(Icons.upload_outlined),
            selectedIcon: const Icon(Icons.upload),
            label: localizations.uploadFile,
          ),
        ],
      ),
    );
  }
}
