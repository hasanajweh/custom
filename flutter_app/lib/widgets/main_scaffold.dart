import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'app_drawer.dart';
import '../providers/auth_provider.dart';
import '../utils/app_localizations.dart';

class MainScaffold extends StatelessWidget {
  final Widget body;
  final String title;
  final int currentIndex;
  final List<Widget>? actions;
  final bool showDrawer;
  final FloatingActionButton? floatingActionButton;

  const MainScaffold({
    super.key,
    required this.body,
    required this.title,
    this.currentIndex = 0,
    this.actions,
    this.showDrawer = true,
    this.floatingActionButton,
  });

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;

    return Scaffold(
      drawer: showDrawer ? AppDrawer(
        currentIndex: currentIndex,
        onItemSelected: (index) {
          // Handle navigation based on index
          // This will be handled by parent widget
        },
      ) : null,
      appBar: AppBar(
        title: Text(title),
        elevation: 0,
        actions: actions,
      ),
      body: body,
      floatingActionButton: floatingActionButton,
    );
  }
}
