import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';
import '../../services/api_service.dart';
import '../../routes/app_router.dart';

class ProfileScreen extends StatefulWidget {
  const ProfileScreen({super.key});

  @override
  State<ProfileScreen> createState() => _ProfileScreenState();
}

class _ProfileScreenState extends State<ProfileScreen> {
  final _nameController = TextEditingController();
  final _emailController = TextEditingController();
  bool _isEditing = false;

  @override
  void initState() {
    super.initState();
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final user = authProvider.user;
    if (user != null) {
      _nameController.text = user.name;
      _emailController.text = user.email;
    }
  }

  @override
  void dispose() {
    _nameController.dispose();
    _emailController.dispose();
    super.dispose();
  }

  Future<void> _updateProfile() async {
    try {
      await ApiService.put('/profile', body: {
        'name': _nameController.text.trim(),
        'email': _emailController.text.trim(),
      });

      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('Profile updated successfully!'),
            backgroundColor: Colors.green,
          ),
        );
        setState(() => _isEditing = false);
        // Refresh auth provider
        Provider.of<AuthProvider>(context, listen: false).checkAuth();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text(e.toString()),
            backgroundColor: Colors.red,
          ),
        );
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;

    return MainScaffold(
      title: localizations.profile,
      showDrawer: false,
      body: user == null
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  // Profile Header
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(24),
                      child: Column(
                        children: [
                          Stack(
                            children: [
                              CircleAvatar(
                                radius: 50,
                                backgroundColor: Theme.of(context).primaryColor,
                                child: Text(
                                  user.name[0].toUpperCase(),
                                  style: const TextStyle(
                                    fontSize: 40,
                                    color: Colors.white,
                                    fontWeight: FontWeight.bold,
                                  ),
                                ),
                              ),
                              Positioned(
                                bottom: 0,
                                right: 0,
                                child: CircleAvatar(
                                  radius: 18,
                                  backgroundColor: Theme.of(context).primaryColor,
                                  child: const Icon(
                                    Icons.camera_alt,
                                    size: 20,
                                    color: Colors.white,
                                  ),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 16),
                          if (!_isEditing) ...[
                            Text(
                              user.name,
                              style: const TextStyle(
                                fontSize: 24,
                                fontWeight: FontWeight.bold,
                              ),
                            ),
                            const SizedBox(height: 4),
                            Text(
                              user.email,
                              style: TextStyle(
                                color: Colors.grey[600],
                                fontSize: 16,
                              ),
                            ),
                            if (user.role != null) ...[
                              const SizedBox(height: 12),
                              Chip(
                                label: Text(user.role!.toUpperCase()),
                                backgroundColor: Theme.of(context)
                                    .primaryColor
                                    .withOpacity(0.1),
                              ),
                            ],
                          ],
                        ],
                      ),
                    ),
                  ),

                  const SizedBox(height: 24),

                  // Profile Form
                  Card(
                    child: Padding(
                      padding: const EdgeInsets.all(16),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                'Profile Information',
                                style: TextStyle(
                                  fontSize: 18,
                                  fontWeight: FontWeight.bold,
                                  color: Colors.grey[800],
                                ),
                              ),
                              if (!_isEditing)
                                TextButton.icon(
                                  onPressed: () => setState(() => _isEditing = true),
                                  icon: const Icon(Icons.edit),
                                  label: const Text('Edit'),
                                )
                              else
                                Row(
                                  children: [
                                    TextButton(
                                      onPressed: () {
                                        setState(() {
                                          _isEditing = false;
                                          _nameController.text = user.name;
                                          _emailController.text = user.email;
                                        });
                                      },
                                      child: const Text('Cancel'),
                                    ),
                                    const SizedBox(width: 8),
                                    ElevatedButton(
                                      onPressed: _updateProfile,
                                      child: const Text('Save'),
                                    ),
                                  ],
                                ),
                            ],
                          ),
                          const SizedBox(height: 16),
                          TextField(
                            controller: _nameController,
                            enabled: _isEditing,
                            decoration: const InputDecoration(
                              labelText: 'Name',
                              prefixIcon: Icon(Icons.person),
                            ),
                          ),
                          const SizedBox(height: 16),
                          TextField(
                            controller: _emailController,
                            enabled: _isEditing,
                            keyboardType: TextInputType.emailAddress,
                            decoration: const InputDecoration(
                              labelText: 'Email',
                              prefixIcon: Icon(Icons.email),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),

                  const SizedBox(height: 16),

                  // Change Password
                  Card(
                    child: ListTile(
                      leading: const Icon(Icons.lock),
                      title: const Text('Change Password'),
                      trailing: const Icon(Icons.chevron_right),
                      onTap: () {
                        Navigator.of(context).pushNamed('/change-password');
                      },
                    ),
                  ),

                  const SizedBox(height: 24),

                  // Logout Button
                  SizedBox(
                    height: 56,
                    child: ElevatedButton.icon(
                      onPressed: () async {
                        await authProvider.logout();
                        if (context.mounted) {
                          Navigator.of(context).pushNamedAndRemoveUntil(
                            AppRouter.login,
                            (route) => false,
                          );
                        }
                      },
                      icon: const Icon(Icons.logout),
                      label: Text(
                        localizations.logout,
                        style: const TextStyle(
                          fontSize: 16,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.red,
                        foregroundColor: Colors.white,
                      ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }
}
