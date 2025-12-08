import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:file_picker/file_picker.dart';
import '../../providers/files_provider.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';
import '../../services/api_service.dart';

class UploadFileScreen extends StatefulWidget {
  const UploadFileScreen({super.key});

  @override
  State<UploadFileScreen> createState() => _UploadFileScreenState();
}

class _UploadFileScreenState extends State<UploadFileScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  
  String? _selectedType;
  int? _selectedSubjectId;
  int? _selectedGradeId;
  PlatformFile? _selectedFile;
  List<Map<String, dynamic>> _subjects = [];
  List<Map<String, dynamic>> _grades = [];
  bool _loadingSubjects = false;

  @override
  void initState() {
    super.initState();
    _loadSubjectsGrades();
  }

  Future<void> _loadSubjectsGrades() async {
    setState(() => _loadingSubjects = true);
    try {
      final data = await ApiService.get('/files/subjects-grades');
      setState(() {
        _subjects = List<Map<String, dynamic>>.from(data['subjects'] ?? []);
        _grades = List<Map<String, dynamic>>.from(data['grades'] ?? []);
      });
    } catch (e) {
      // Handle error
    } finally {
      setState(() => _loadingSubjects = false);
    }
  }

  Future<void> _pickFile() async {
    final result = await FilePicker.platform.pickFiles(
      type: FileType.any,
      allowMultiple: false,
    );

    if (result != null && result.files.single.bytes != null) {
      setState(() {
        _selectedFile = result.files.single;
      });
    }
  }

  Future<void> _uploadFile() async {
    if (!_formKey.currentState!.validate() || _selectedFile == null || _selectedType == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill all required fields and select a file')),
      );
      return;
    }

    final filesProvider = Provider.of<FilesProvider>(context, listen: false);
    
    final success = await filesProvider.uploadFile(
      title: _titleController.text.trim(),
      description: _descriptionController.text.trim().isEmpty 
          ? null 
          : _descriptionController.text.trim(),
      submissionType: _selectedType!,
      fileBytes: _selectedFile!.bytes!,
      fileName: _selectedFile!.name,
      subjectId: _selectedSubjectId,
      gradeId: _selectedGradeId,
    );

    if (success && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
          content: Text('File uploaded successfully!'),
          backgroundColor: Colors.green,
        ),
      );
      Navigator.of(context).pop();
    } else if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text(filesProvider.error ?? 'Upload failed'),
          backgroundColor: Colors.red,
        ),
      );
    }
  }

  @override
  void dispose() {
    _titleController.dispose();
    _descriptionController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final localizations = AppLocalizations.of(context);
    final filesProvider = Provider.of<FilesProvider>(context);

    return MainScaffold(
      title: localizations.uploadFile,
      currentIndex: 2,
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // File Type Selection
              _SectionTitle('File Type *'),
              const SizedBox(height: 8),
              Wrap(
                spacing: 8,
                runSpacing: 8,
                children: [
                  _TypeChip(
                    label: 'Exam',
                    value: 'exam',
                    selected: _selectedType == 'exam',
                    onTap: () => setState(() => _selectedType = 'exam'),
                  ),
                  _TypeChip(
                    label: 'Worksheet',
                    value: 'worksheet',
                    selected: _selectedType == 'worksheet',
                    onTap: () => setState(() => _selectedType = 'worksheet'),
                  ),
                  _TypeChip(
                    label: 'Summary',
                    value: 'summary',
                    selected: _selectedType == 'summary',
                    onTap: () => setState(() => _selectedType = 'summary'),
                  ),
                  _TypeChip(
                    label: 'Daily Plan',
                    value: 'daily_plan',
                    selected: _selectedType == 'daily_plan',
                    onTap: () => setState(() => _selectedType = 'daily_plan'),
                  ),
                  _TypeChip(
                    label: 'Weekly Plan',
                    value: 'weekly_plan',
                    selected: _selectedType == 'weekly_plan',
                    onTap: () => setState(() => _selectedType = 'weekly_plan'),
                  ),
                  _TypeChip(
                    label: 'Monthly Plan',
                    value: 'monthly_plan',
                    selected: _selectedType == 'monthly_plan',
                    onTap: () => setState(() => _selectedType = 'monthly_plan'),
                  ),
                ],
              ),

              const SizedBox(height: 24),

              // Title
              TextFormField(
                controller: _titleController,
                decoration: const InputDecoration(
                  labelText: 'Title *',
                  hintText: 'Enter file title',
                  prefixIcon: Icon(Icons.title),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a title';
                  }
                  return null;
                },
              ),

              const SizedBox(height: 16),

              // Description
              TextFormField(
                controller: _descriptionController,
                decoration: const InputDecoration(
                  labelText: 'Description (Optional)',
                  hintText: 'Enter file description',
                  prefixIcon: Icon(Icons.description),
                ),
                maxLines: 3,
              ),

              const SizedBox(height: 16),

              // Subject
              if (_subjects.isNotEmpty) ...[
                DropdownButtonFormField<int>(
                  decoration: const InputDecoration(
                    labelText: 'Subject (Optional)',
                    prefixIcon: Icon(Icons.book),
                  ),
                  items: _subjects.map((subject) {
                    return DropdownMenuItem<int>(
                      value: subject['id'] as int,
                      child: Text(subject['name'] as String),
                    );
                  }).toList(),
                  onChanged: (value) => setState(() => _selectedSubjectId = value),
                ),
                const SizedBox(height: 16),
              ],

              // Grade
              if (_grades.isNotEmpty) ...[
                DropdownButtonFormField<int>(
                  decoration: const InputDecoration(
                    labelText: 'Grade (Optional)',
                    prefixIcon: Icon(Icons.grade),
                  ),
                  items: _grades.map((grade) {
                    return DropdownMenuItem<int>(
                      value: grade['id'] as int,
                      child: Text(grade['name'] as String),
                    );
                  }).toList(),
                  onChanged: (value) => setState(() => _selectedGradeId = value),
                ),
                const SizedBox(height: 16),
              ],

              // File Picker
              _SectionTitle('Select File *'),
              const SizedBox(height: 8),
              InkWell(
                onTap: _pickFile,
                child: Container(
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    border: Border.all(
                      color: _selectedFile != null 
                          ? Colors.green 
                          : Colors.grey[300]!,
                      width: 2,
                      style: BorderStyle.solid,
                    ),
                    borderRadius: BorderRadius.circular(12),
                    color: _selectedFile != null 
                        ? Colors.green.withOpacity(0.05)
                        : Colors.grey[50],
                  ),
                  child: Column(
                    children: [
                      Icon(
                        _selectedFile != null ? Icons.check_circle : Icons.cloud_upload,
                        size: 48,
                        color: _selectedFile != null 
                            ? Colors.green 
                            : Colors.grey[400],
                      ),
                      const SizedBox(height: 12),
                      Text(
                        _selectedFile?.name ?? 'Tap to select file',
                        style: TextStyle(
                          fontWeight: FontWeight.w500,
                          color: _selectedFile != null 
                              ? Colors.green 
                              : Colors.grey[600],
                        ),
                      ),
                      if (_selectedFile != null)
                        Text(
                          _formatFileSize(_selectedFile!.size),
                          style: TextStyle(
                            color: Colors.grey[600],
                            fontSize: 12,
                          ),
                        ),
                    ],
                  ),
                ),
              ),

              const SizedBox(height: 32),

              // Upload Button
              SizedBox(
                height: 56,
                child: ElevatedButton.icon(
                  onPressed: filesProvider.isLoading ? null : _uploadFile,
                  icon: filesProvider.isLoading
                      ? const SizedBox(
                          width: 20,
                          height: 20,
                          child: CircularProgressIndicator(
                            strokeWidth: 2,
                            valueColor: AlwaysStoppedAnimation<Color>(Colors.white),
                          ),
                        )
                      : const Icon(Icons.upload),
                  label: Text(
                    filesProvider.isLoading ? 'Uploading...' : 'Upload File',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Theme.of(context).primaryColor,
                    foregroundColor: Colors.white,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _formatFileSize(int bytes) {
    if (bytes < 1024) return '$bytes B';
    if (bytes < 1024 * 1024) return '${(bytes / 1024).toStringAsFixed(1)} KB';
    return '${(bytes / (1024 * 1024)).toStringAsFixed(1)} MB';
  }
}

class _SectionTitle extends StatelessWidget {
  final String title;

  const _SectionTitle(this.title);

  @override
  Widget build(BuildContext context) {
    return Text(
      title,
      style: TextStyle(
        fontSize: 14,
        fontWeight: FontWeight.w600,
        color: Colors.grey[700],
      ),
    );
  }
}

class _TypeChip extends StatelessWidget {
  final String label;
  final String value;
  final bool selected;
  final VoidCallback onTap;

  const _TypeChip({
    required this.label,
    required this.value,
    required this.selected,
    required this.onTap,
  });

  @override
  Widget build(BuildContext context) {
    return FilterChip(
      label: Text(label),
      selected: selected,
      onSelected: (_) => onTap(),
      selectedColor: Theme.of(context).primaryColor.withOpacity(0.2),
      checkmarkColor: Theme.of(context).primaryColor,
    );
  }
}
