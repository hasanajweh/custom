import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../models/user_context.dart';
import '../utils/app_localizations.dart';
import '../routes/app_router.dart';
import 'navigation_handler.dart';

class AppDrawer extends StatelessWidget {
  final int currentIndex;
  final Function(int)? onItemSelected;

  const AppDrawer({
    super.key,
    this.currentIndex = 0,
    this.onItemSelected,
  });

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;
    final localizations = AppLocalizations.of(context);
    final currentContext = authProvider.currentContext;
    final availableContexts = authProvider.availableContexts;

    return Drawer(
      child: Column(
        children: [
          // User Header
          Container(
            width: double.infinity,
            padding: const EdgeInsets.only(
              top: 50,
              bottom: 20,
              left: 20,
              right: 20,
            ),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  Theme.of(context).primaryColor,
                  Theme.of(context).colorScheme.secondary,
                ],
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CircleAvatar(
                  radius: 35,
                  backgroundColor: Colors.white,
                  child: Text(
                    user?.name[0].toUpperCase() ?? 'U',
                    style: TextStyle(
                      fontSize: 28,
                      fontWeight: FontWeight.bold,
                      color: Theme.of(context).primaryColor,
                    ),
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  user?.name ?? 'User',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 20,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  user?.email ?? '',
                  style: TextStyle(
                    color: Colors.white.withOpacity(0.9),
                    fontSize: 14,
                  ),
                ),
                if (currentContext != null) ...[
                  const SizedBox(height: 12),
                  Container(
                    padding: const EdgeInsets.symmetric(
                      horizontal: 12,
                      vertical: 6,
                    ),
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.2),
                      borderRadius: BorderRadius.circular(20),
                    ),
                    child: Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        Icon(
                          Icons.school,
                          size: 16,
                          color: Colors.white,
                        ),
                        const SizedBox(width: 6),
                        Flexible(
                          child: Text(
                            '${currentContext.school.name}',
                            style: const TextStyle(
                              color: Colors.white,
                              fontSize: 12,
                              fontWeight: FontWeight.w500,
                            ),
                            overflow: TextOverflow.ellipsis,
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ],
            ),
          ),

          // Context Switch (if multiple contexts)
          if (availableContexts.length > 1) ...[
            Container(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Switch Context',
                    style: TextStyle(
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                      color: Colors.grey[600],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Container(
                    decoration: BoxDecoration(
                      border: Border.all(color: Colors.grey[300]!),
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: DropdownButtonHideUnderline(
                      child: Builder(
                      builder: (context) {
                        String? selectedValue;
                        if (availableContexts.isNotEmpty && currentContext != null) {
                          try {
                            final matchingCtx = availableContexts.firstWhere(
                              (ctx) => ctx.schoolId == currentContext!.school.id,
                            );
                            selectedValue = '${matchingCtx.networkSlug ?? ''}/${matchingCtx.schoolSlug}';
                          } catch (e) {
                            final firstCtx = availableContexts.first;
                            selectedValue = '${firstCtx.networkSlug ?? ''}/${firstCtx.schoolSlug}';
                          }
                        } else if (availableContexts.isNotEmpty) {
                          final firstCtx = availableContexts.first;
                          selectedValue = '${firstCtx.networkSlug ?? ''}/${firstCtx.schoolSlug}';
                        }
                        
                        // Verify the value exists in items
                        final valueExists = availableContexts.any((ctx) {
                          final valueKey = '${ctx.networkSlug ?? ''}/${ctx.schoolSlug}';
                          return valueKey == selectedValue;
                        });
                        
                        return DropdownButton<String>(
                          isExpanded: true,
                          value: valueExists ? selectedValue : null,
                          padding: const EdgeInsets.symmetric(horizontal: 16),
                          items: availableContexts.map((ctx) {
                            final valueKey = '${ctx.networkSlug ?? ''}/${ctx.schoolSlug}';
                            return DropdownMenuItem<String>(
                              value: valueKey,
                              child: Row(
                                children: [
                                  Icon(
                                    _getRoleIcon(ctx.role),
                                    size: 18,
                                    color: Theme.of(context).primaryColor,
                                  ),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      mainAxisSize: MainAxisSize.min,
                                      children: [
                                        Text(
                                          ctx.schoolName,
                                          style: const TextStyle(
                                            fontWeight: FontWeight.w600,
                                          ),
                                        ),
                                        Text(
                                          ctx.role.toUpperCase(),
                                          style: TextStyle(
                                            fontSize: 11,
                                            color: Colors.grey[600],
                                          ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            );
                          }).toList(),
                          onChanged: (value) async {
                            if (value != null) {
                              final parts = value.split('/');
                              if (parts.length == 2) {
                                final success = await authProvider.switchContext(
                                  network: parts[0],
                                  school: parts[1],
                                );
                                if (success && context.mounted) {
                                  Navigator.of(context).pop();
                                  Navigator.of(context).pushReplacementNamed(AppRouter.home);
                                }
                              }
                            }
                          },
                        );
                      },
                      ),
                    ),
                  ),
                ],
              ),
            ),
            const Divider(),
          ],

          // Navigation Items
          Expanded(
            child: ListView(
              padding: EdgeInsets.zero,
              children: [
                _DrawerTile(
                  icon: Icons.dashboard_outlined,
                  selectedIcon: Icons.dashboard,
                  title: localizations.dashboard,
                  isSelected: currentIndex == 0,
                  onTap: () {
                    Navigator.of(context).pop();
                    NavigationHandler.handleDrawerNavigation(
                      context,
                      0,
                      authProvider,
                    );
                  },
                ),
                if (user?.isTeacher ?? false) ...[
                  _DrawerTile(
                    icon: Icons.folder_outlined,
                    selectedIcon: Icons.folder,
                    title: localizations.myFiles,
                    isSelected: currentIndex == 1,
                    onTap: () {
                      Navigator.of(context).pop();
                      NavigationHandler.handleDrawerNavigation(
                        context,
                        1,
                        authProvider,
                      );
                    },
                  ),
                  _DrawerTile(
                    icon: Icons.upload_file_outlined,
                    selectedIcon: Icons.upload_file,
                    title: localizations.uploadFile,
                    isSelected: currentIndex == 2,
                    onTap: () {
                      Navigator.of(context).pop();
                      NavigationHandler.handleDrawerNavigation(
                        context,
                        2,
                        authProvider,
                      );
                    },
                  ),
                ],
                if (user?.isSupervisor ?? false) ...[
                  _DrawerTile(
                    icon: Icons.check_circle_outline,
                    selectedIcon: Icons.check_circle,
                    title: 'Review Files',
                    isSelected: currentIndex == 1,
                    onTap: () {
                      Navigator.of(context).pop();
                      NavigationHandler.handleDrawerNavigation(
                        context,
                        1,
                        authProvider,
                      );
                    },
                  ),
                ],
                if (user?.isAdmin ?? false) ...[
                  _DrawerTile(
                    icon: Icons.people_outlined,
                    selectedIcon: Icons.people,
                    title: 'Manage Users',
                    isSelected: currentIndex == 1,
                    onTap: () {
                      Navigator.of(context).pop();
                      NavigationHandler.handleDrawerNavigation(
                        context,
                        1,
                        authProvider,
                      );
                    },
                  ),
                ],
                const Divider(),
                _DrawerTile(
                  icon: Icons.person_outline,
                  selectedIcon: Icons.person,
                  title: localizations.profile,
                  isSelected: false,
                  onTap: () {
                    Navigator.of(context).pop();
                    Navigator.of(context).pushNamed(AppRouter.profile);
                  },
                ),
                _DrawerTile(
                  icon: Icons.logout,
                  title: localizations.logout,
                  isSelected: false,
                  textColor: Colors.red,
                  onTap: () async {
                    Navigator.of(context).pop();
                    await authProvider.logout();
                    if (context.mounted) {
                      Navigator.of(context).pushNamedAndRemoveUntil(
                        AppRouter.login,
                        (route) => false,
                      );
                    }
                  },
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  IconData _getRoleIcon(String role) {
    switch (role.toLowerCase()) {
      case 'teacher':
        return Icons.school;
      case 'supervisor':
        return Icons.supervisor_account;
      case 'admin':
        return Icons.admin_panel_settings;
      default:
        return Icons.person;
    }
  }
}

class _DrawerTile extends StatelessWidget {
  final IconData icon;
  final IconData? selectedIcon;
  final String title;
  final bool isSelected;
  final VoidCallback onTap;
  final Color? textColor;
  final int? badge;

  const _DrawerTile({
    required this.icon,
    this.selectedIcon,
    required this.title,
    this.isSelected = false,
    required this.onTap,
    this.textColor,
    this.badge,
  });

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: Icon(
        isSelected ? (selectedIcon ?? icon) : icon,
        color: isSelected
            ? Theme.of(context).primaryColor
            : (textColor ?? Colors.grey[700]),
      ),
      title: Row(
        children: [
          Expanded(
            child: Text(
              title,
              style: TextStyle(
                fontWeight: isSelected ? FontWeight.w600 : FontWeight.normal,
                color: textColor ??
                    (isSelected
                        ? Theme.of(context).primaryColor
                        : Colors.black87),
              ),
            ),
          ),
          if (badge != null && badge! > 0)
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
              decoration: BoxDecoration(
                color: Colors.red,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Text(
                badge.toString(),
                style: const TextStyle(
                  color: Colors.white,
                  fontSize: 12,
                  fontWeight: FontWeight.bold,
                ),
              ),
            ),
        ],
      ),
      selected: isSelected,
      selectedTileColor: Theme.of(context).primaryColor.withOpacity(0.1),
      onTap: onTap,
    );
  }
}
