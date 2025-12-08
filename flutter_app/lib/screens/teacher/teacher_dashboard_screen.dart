import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/auth_provider.dart';
import '../../providers/files_provider.dart';
import '../../utils/app_localizations.dart';
import '../../widgets/main_scaffold.dart';
import '../../widgets/stat_card.dart';
import '../../widgets/enhanced_stat_card.dart';
import '../../routes/app_router.dart';
import '../../models/file_submission.dart';

class TeacherDashboardScreen extends StatefulWidget {
  const TeacherDashboardScreen({super.key});

  @override
  State<TeacherDashboardScreen> createState() => _TeacherDashboardScreenState();
}

class _TeacherDashboardScreenState extends State<TeacherDashboardScreen> {
  int _totalUploads = 0;
  int _totalDownloads = 0;
  int _thisWeek = 0;
  List<FileSubmission> _recentFiles = [];

  @override
  void initState() {
    super.initState();
    _loadDashboardData();
  }

  Future<void> _loadDashboardData() async {
    final filesProvider = Provider.of<FilesProvider>(context, listen: false);
    await filesProvider.loadFiles();
    
    setState(() {
      _totalUploads = filesProvider.files.length;
      _totalDownloads = filesProvider.files.fold(
        0,
        (sum, file) => sum + (file.downloadCount),
      );
      _thisWeek = filesProvider.files.where((file) {
        final now = DateTime.now();
        final weekStart = now.subtract(Duration(days: now.weekday - 1));
        return file.createdAt.isAfter(weekStart);
      }).length;
      _recentFiles = filesProvider.files.take(5).toList();
    });
  }

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final authProvider = Provider.of<AuthProvider>(context);
    final user = authProvider.user;

    return MainScaffold(
      currentIndex: 0,
      title: localizations.dashboard,
      body: RefreshIndicator(
        onRefresh: _loadDashboardData,
        child: SingleChildScrollView(
          physics: const AlwaysScrollableScrollPhysics(),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Welcome Header
              Container(
                width: double.infinity,
                margin: const EdgeInsets.all(16),
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  gradient: const LinearGradient(
                    colors: [Color(0xFF6366F1), Color(0xFF9333EA), Color(0xFFEC4899)],
                    begin: Alignment.topLeft,
                    end: Alignment.bottomRight,
                  ),
                  borderRadius: BorderRadius.circular(20),
                  boxShadow: [
                    BoxShadow(
                      color: Colors.purple.withOpacity(0.3),
                      blurRadius: 20,
                      offset: const Offset(0, 10),
                    ),
                  ],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Welcome back,',
                                style: TextStyle(
                                  color: Colors.white.withOpacity(0.9),
                                  fontSize: 16,
                                ),
                              ),
                              const SizedBox(height: 4),
                              Text(
                                user?.name ?? 'Teacher',
                                style: const TextStyle(
                                  color: Colors.white,
                                  fontSize: 24,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                            ],
                          ),
                        ),
                        Container(
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.2),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: const Icon(
                            Icons.school_rounded,
                            color: Colors.white,
                            size: 32,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    Text(
                      'Ready to share knowledge today?',
                      style: TextStyle(
                        color: Colors.white.withOpacity(0.9),
                        fontSize: 14,
                      ),
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Expanded(
                          child: _QuickActionButton(
                            icon: Icons.upload_file_rounded,
                            label: 'Upload',
                            color: Colors.white,
                            textColor: const Color(0xFF6366F1),
                            onTap: () {
                              Navigator.of(context).pushNamed(AppRouter.uploadFile);
                            },
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: _QuickActionButton(
                            icon: Icons.folder_rounded,
                            label: 'My Files',
                            color: Colors.white.withOpacity(0.2),
                            textColor: Colors.white,
                            onTap: () {
                              Navigator.of(context).pushNamed(AppRouter.myFiles);
                            },
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              // Statistics Grid
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Column(
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: StatCard(
                            title: 'My Uploads',
                            value: '$_totalUploads',
                            icon: Icons.cloud_upload_rounded,
                            color: const Color(0xFF6366F1),
                            subtitle: 'Total files',
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: EnhancedStatCard(
                            title: 'Downloads',
                            value: '$_totalDownloads',
                            icon: Icons.download_rounded,
                            color: const Color(0xFF10B981),
                            subtitle: 'Total downloads',
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Row(
                      children: [
                        Expanded(
                          child: EnhancedStatCard(
                            title: 'This Week',
                            value: '$_thisWeek',
                            icon: Icons.calendar_today_rounded,
                            color: const Color(0xFFF59E0B),
                            subtitle: 'Uploads',
                          ),
                        ),
                        const SizedBox(width: 12),
                        Expanded(
                          child: EnhancedStatCard(
                            title: 'Recent',
                            value: '${_recentFiles.length}',
                            icon: Icons.access_time_rounded,
                            color: const Color(0xFFEC4899),
                            subtitle: 'Latest files',
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Recent Files
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 16),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'Recent Files',
                      style: Theme.of(context).textTheme.titleLarge?.copyWith(
                            fontWeight: FontWeight.bold,
                          ),
                    ),
                    TextButton(
                      onPressed: () {
                        Navigator.of(context).pushNamed(AppRouter.myFiles);
                      },
                      child: const Text('View All'),
                    ),
                  ],
                ),
              ),

              if (_recentFiles.isEmpty)
                Padding(
                  padding: const EdgeInsets.all(32),
                  child: Center(
                    child: Column(
                      children: [
                        Icon(
                          Icons.folder_open_rounded,
                          size: 64,
                          color: Colors.grey[300],
                        ),
                        const SizedBox(height: 16),
                        Text(
                          'No files yet',
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 16,
                          ),
                        ),
                        const SizedBox(height: 8),
                        TextButton.icon(
                          onPressed: () {
                            Navigator.of(context).pushNamed(AppRouter.uploadFile);
                          },
                          icon: const Icon(Icons.upload_rounded),
                          label: const Text('Upload Your First File'),
                        ),
                      ],
                    ),
                  ),
                )
              else
                ..._recentFiles.map((file) => _RecentFileCard(file: file)),
            ],
          ),
        ),
      ),
    );
  }
}

class _QuickActionButton extends StatelessWidget {
  final IconData icon;
  final String label;
  final Color color;
  final Color textColor;
  final VoidCallback onTap;

  const _QuickActionButton({
    required this.icon,
    required this.label,
    required this.color,
    required this.textColor,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return InkWell(
      onTap: onTap,
      borderRadius: BorderRadius.circular(12),
      child: Container(
        padding: const EdgeInsets.symmetric(vertical: 12, horizontal: 16),
        decoration: BoxDecoration(
          color: color,
          borderRadius: BorderRadius.circular(12),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(icon, color: textColor, size: 20),
            const SizedBox(width: 8),
            Text(
              label,
              style: TextStyle(
                color: textColor,
                fontWeight: FontWeight.w600,
                fontSize: 14,
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _RecentFileCard extends StatelessWidget {
  final FileSubmission file;

  const _RecentFileCard({required this.file});

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 6),
      child: Card(
        child: ListTile(
          leading: CircleAvatar(
            backgroundColor: _getFileTypeColor(file.submissionType).withOpacity(0.1),
            child: Icon(
              _getFileIcon(file.submissionType),
              color: _getFileTypeColor(file.submissionType),
            ),
          ),
          title: Text(
            file.title,
            style: const TextStyle(fontWeight: FontWeight.w600),
            maxLines: 1,
            overflow: TextOverflow.ellipsis,
          ),
          subtitle: Text(
            _formatFileType(file.submissionType),
            style: TextStyle(fontSize: 12, color: Colors.grey[600]),
          ),
          trailing: Icon(Icons.chevron_right, color: Colors.grey[400]),
          onTap: () {
            // Navigate to file details
          },
        ),
      ),
    );
  }

  Color _getFileTypeColor(String type) {
    switch (type) {
      case 'exam':
        return Colors.red;
      case 'worksheet':
        return Colors.blue;
      case 'summary':
        return Colors.green;
      default:
        return Colors.orange;
    }
  }

  IconData _getFileIcon(String type) {
    switch (type) {
      case 'exam':
        return Icons.quiz;
      case 'worksheet':
        return Icons.description;
      case 'summary':
        return Icons.summarize;
      default:
        return Icons.insert_drive_file;
    }
  }

  String _formatFileType(String type) {
    return type.replaceAll('_', ' ').split(' ').map((word) {
      return word[0].toUpperCase() + word.substring(1);
    }).join(' ');
  }
}
