import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../../providers/files_provider.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';
import '../../models/file_submission.dart';
import '../../routes/app_router.dart';
import 'file_detail_screen.dart';

class MyFilesScreen extends StatefulWidget {
  const MyFilesScreen({super.key});

  @override
  State<MyFilesScreen> createState() => _MyFilesScreenState();
}

class _MyFilesScreenState extends State<MyFilesScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _selectedType;
  int? _selectedSubjectId;
  int? _selectedGradeId;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addPostFrameCallback((_) {
      Provider.of<FilesProvider>(context, listen: false).loadFiles();
    });
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final filesProvider = Provider.of<FilesProvider>(context);

    return MainScaffold(
      title: localizations.myFiles,
      currentIndex: 1,
      body: Column(
        children: [
          // Search and Filters
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.white,
            child: Column(
              children: [
                // Search Bar
                TextField(
                  controller: _searchController,
                  decoration: InputDecoration(
                    hintText: 'Search files...',
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: _searchController.text.isNotEmpty
                        ? IconButton(
                            icon: const Icon(Icons.clear),
                            onPressed: () {
                              _searchController.clear();
                              filesProvider.search('');
                            },
                          )
                        : null,
                    filled: true,
                    fillColor: Colors.grey[100],
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                  onChanged: (value) {
                    filesProvider.search(value);
                  },
                ),
                const SizedBox(height: 12),
                // Filter Chips
                SingleChildScrollView(
                  scrollDirection: Axis.horizontal,
                  child: Row(
                    children: [
                      _FilterChip(
                        label: 'All',
                        isSelected: _selectedType == null,
                        onTap: () {
                          setState(() => _selectedType = null);
                          filesProvider.filter(type: null);
                        },
                      ),
                      const SizedBox(width: 8),
                      _FilterChip(
                        label: 'Exams',
                        isSelected: _selectedType == 'exam',
                        onTap: () {
                          setState(() => _selectedType = 'exam');
                          filesProvider.filter(type: 'exam');
                        },
                      ),
                      const SizedBox(width: 8),
                      _FilterChip(
                        label: 'Worksheets',
                        isSelected: _selectedType == 'worksheet',
                        onTap: () {
                          setState(() => _selectedType = 'worksheet');
                          filesProvider.filter(type: 'worksheet');
                        },
                      ),
                      const SizedBox(width: 8),
                      _FilterChip(
                        label: 'Plans',
                        isSelected: _selectedType == 'daily_plan',
                        onTap: () {
                          setState(() => _selectedType = 'daily_plan');
                          filesProvider.filter(type: 'daily_plan');
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Files List
          Expanded(
            child: filesProvider.isLoading
                ? const Center(child: CircularProgressIndicator())
                : filesProvider.files.isEmpty
                    ? _EmptyState()
                    : RefreshIndicator(
                        onRefresh: () => filesProvider.loadFiles(),
                        child: ListView.builder(
                          padding: const EdgeInsets.all(16),
                          itemCount: filesProvider.files.length,
                          itemBuilder: (context, index) {
                            final file = filesProvider.files[index];
                            return _FileCard(file: file);
                          },
                        ),
                      ),
          ),
        ],
      ),
    );
  }
}

class _FilterChip extends StatelessWidget {
  final String label;
  final bool isSelected;
  final VoidCallback onTap;

  const _FilterChip({
    required this.label,
    required this.isSelected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return FilterChip(
      label: Text(label),
      selected: isSelected,
      onSelected: (_) => onTap(),
      selectedColor: Theme.of(context).primaryColor.withOpacity(0.2),
      checkmarkColor: Theme.of(context).primaryColor,
    );
  }
}

class _FileCard extends StatelessWidget {
  final FileSubmission file;

  const _FileCard({required this.file});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
          child: InkWell(
        onTap: () {
          Navigator.of(context).push(
            MaterialPageRoute(
              builder: (context) => FileDetailScreen(file: file),
            ),
          );
        },
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Row(
            children: [
              // File Icon
              Container(
                width: 50,
                height: 50,
                decoration: BoxDecoration(
                  color: _getFileTypeColor(file.submissionType).withOpacity(0.1),
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Icon(
                  _getFileIcon(file.submissionType),
                  color: _getFileTypeColor(file.submissionType),
                  size: 28,
                ),
              ),
              const SizedBox(width: 16),
              // File Info
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      file.title,
                      style: const TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                    ),
                    const SizedBox(height: 4),
                    Text(
                      _formatFileType(file.submissionType),
                      style: TextStyle(
                        color: Colors.grey[600],
                        fontSize: 12,
                      ),
                    ),
                    if (file.subject != null || file.grade != null) ...[
                      const SizedBox(height: 4),
                      Row(
                        children: [
                          if (file.subject != null)
                            _InfoChip(
                              icon: Icons.book,
                              label: file.subject!.name,
                            ),
                          if (file.grade != null) ...[
                            const SizedBox(width: 8),
                            _InfoChip(
                              icon: Icons.grade,
                              label: file.grade!.name,
                            ),
                          ],
                        ],
                      ),
                    ],
                  ],
                ),
              ),
              // Actions
              PopupMenuButton(
                icon: const Icon(Icons.more_vert),
                itemBuilder: (context) => [
                  const PopupMenuItem(
                    value: 'download',
                    child: Row(
                      children: [
                        Icon(Icons.download, size: 20),
                        SizedBox(width: 8),
                        Text('Download'),
                      ],
                    ),
                  ),
                  const PopupMenuItem(
                    value: 'delete',
                    child: Row(
                      children: [
                        Icon(Icons.delete, color: Colors.red, size: 20),
                        SizedBox(width: 8),
                        Text('Delete', style: TextStyle(color: Colors.red)),
                      ],
                    ),
                  ),
                ],
                onSelected: (value) async {
                  if (value == 'download') {
                    final filesProvider = Provider.of<FilesProvider>(context, listen: false);
                    final downloadUrl = await filesProvider.getDownloadUrl(file.id);
                    if (downloadUrl != null) {
                      // TODO: Implement download
                    }
                  } else if (value == 'delete') {
                    final confirmed = await showDialog<bool>(
                      context: context,
                      builder: (context) => AlertDialog(
                        title: const Text('Delete File'),
                        content: const Text('Are you sure you want to delete this file?'),
                        actions: [
                          TextButton(
                            onPressed: () => Navigator.of(context).pop(false),
                            child: const Text('Cancel'),
                          ),
                          TextButton(
                            onPressed: () => Navigator.of(context).pop(true),
                            style: TextButton.styleFrom(foregroundColor: Colors.red),
                            child: const Text('Delete'),
                          ),
                        ],
                      ),
                    );
                    if (confirmed == true) {
                      final filesProvider = Provider.of<FilesProvider>(context, listen: false);
                      await filesProvider.deleteFile(file.id);
                    }
                  }
                },
              ),
            ],
          ),
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
      case 'daily_plan':
      case 'weekly_plan':
      case 'monthly_plan':
        return Colors.orange;
      default:
        return Colors.grey;
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
      case 'daily_plan':
      case 'weekly_plan':
      case 'monthly_plan':
        return Icons.calendar_today;
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

class _InfoChip extends StatelessWidget {
  final IconData icon;
  final String label;

  const _InfoChip({required this.icon, required this.label});

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(
        color: Colors.grey[100],
        borderRadius: BorderRadius.circular(12),
      ),
      child: Row(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, size: 14, color: Colors.grey[700]),
          const SizedBox(width: 4),
          Text(
            label,
            style: TextStyle(
              fontSize: 11,
              color: Colors.grey[700],
            ),
          ),
        ],
      ),
    );
  }
}

class _EmptyState extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(
            Icons.folder_open,
            size: 80,
            color: Colors.grey[300],
          ),
          const SizedBox(height: 16),
          Text(
            'No files yet',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.w500,
              color: Colors.grey[600],
            ),
          ),
          const SizedBox(height: 8),
          Text(
            'Upload your first file to get started',
            style: TextStyle(
              color: Colors.grey[500],
            ),
          ),
        ],
      ),
    );
  }
}
