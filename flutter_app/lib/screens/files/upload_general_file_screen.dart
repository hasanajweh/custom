import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:file_picker/file_picker.dart';
import '../../providers/files_provider.dart';
import '../../widgets/main_scaffold.dart';
import '../../utils/app_localizations.dart';
import '../../services/api_service.dart';

class UploadGeneralFileScreen extends StatefulWidget {
  const UploadGeneralFileScreen({super.key});

  @override
  State<UploadGeneralFileScreen> createState() => _UploadGeneralFileScreenState();
}

class _UploadGeneralFileScreenState extends State<UploadGeneralFileScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  
  String? _selectedType; // exam, worksheet, summary
  int? _selectedSubjectId;
  int? _selectedGradeId;
  PlatformFile? _selectedFile;
  List<Map<String, dynamic>> _subjects = [];
  List<Map<String, dynamic>> _grades = [];
  bool _loadingSubjects = false;

  @override
  void initState() {
    super.initState();
    _selectedType = 'exam'; // Default
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
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error loading subjects/grades: $e')),
        );
      }
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
    if (!_formKey.currentState!.validate() || _selectedFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please fill all required fields and select a file')),
      );
      return;
    }

    if (_selectedSubjectId == null || _selectedGradeId == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select both subject and grade')),
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
    final filesProvider = Provider.of<FilesProvider>(context);

    return MainScaffold(
      title: 'Upload General Resource',
      showDrawer: false,
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              // Title
              TextFormField(
                controller: _titleController,
                decoration: const InputDecoration(
                  labelText: 'Resource Title *',
                  hintText: 'Enter resource title',
                  prefixIcon: Icon(Icons.title),
                ),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter a title';
                  }
                  return null;
                },
              ),

              const SizedBox(height: 24),

              // Content Type (Exam, Worksheet, Summary)
              Text(
                'Content Type *',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: Colors.grey[700],
                ),
              ),
              const SizedBox(height: 8),
              Row(
                children: [
                  Expanded(
                    child: _TypeChoiceChip(
                      label: 'Exam',
                      value: 'exam',
                      selected: _selectedType == 'exam',
                      onSelected: () => setState(() => _selectedType = 'exam'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: _TypeChoiceChip(
                      label: 'Worksheet',
                      value: 'worksheet',
                      selected: _selectedType == 'worksheet',
                      onSelected: () => setState(() => _selectedType = 'worksheet'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: _TypeChoiceChip(
                      label: 'Summary',
                      value: 'summary',
                      selected: _selectedType == 'summary',
                      onSelected: () => setState(() => _selectedType = 'summary'),
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 24),

              // Subject
              if (_loadingSubjects)
                const Center(child: CircularProgressIndicator())
              else if (_subjects.isEmpty)
                Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(
                    color: Colors.yellow[50],
                    border: Border.all(color: Colors.yellow[200]!),
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: const Text(
                    'You do not have any assigned subjects yet. Please contact your administrator.',
                    style: TextStyle(color: Colors.orange),
                  ),
                )
              else
                DropdownButtonFormField<int>(
                  decoration: const InputDecoration(
                    labelText: 'Subject *',
                    prefixIcon: Icon(Icons.book),
                  ),
                  value: _selectedSubjectId,
                  items: _subjects.map((subject) {
                    return DropdownMenuItem<int>(
                      value: subject['id'] as int,
                      child: Text(subject['name'] as String),
                    );
                  }).toList(),
                  onChanged: (value) => setState(() => _selectedSubjectId = value),
                  validator: (value) {
                    if (value == null) {
                      return 'Please select a subject';
                    }
                    return null;
                  },
                ),

              const SizedBox(height: 16),

              // Grade
              if (_grades.isEmpty && !_loadingSubjects)
                Container()
              else if (!_loadingSubjects)
                DropdownButtonFormField<int>(
                  decoration: const InputDecoration(
                    labelText: 'Grade *',
                    prefixIcon: Icon(Icons.grade),
                  ),
                  value: _selectedGradeId,
                  items: _grades.map((grade) {
                    return DropdownMenuItem<int>(
                      value: grade['id'] as int,
                      child: Text(grade['name'] as String),
                    );
                  }).toList(),
                  onChanged: (value) => setState(() => _selectedGradeId = value),
                  validator: (value) {
                    if (value == null) {
                      return 'Please select a grade';
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
                  hintText: 'Enter description',
                  prefixIcon: Icon(Icons.description),
                ),
                maxLines: 3,
              ),

              const SizedBox(height: 24),

              // File Picker
              Text(
                'Select File *',
                style: TextStyle(
                  fontSize: 14,
                  fontWeight: FontWeight.w600,
                  color: Colors.grey[700],
                ),
              ),
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

class _TypeChoiceChip extends StatelessWidget {
  final String label;
  final String value;
  final bool selected;
  final VoidCallback onSelected;

  const _TypeChoiceChip({
    required this.label,
    required this.value,
    required this.selected,
    required this.onSelected,
  });

  @override
  Widget build(BuildContext context) {
    return ChoiceChip(
      label: Text(label),
      selected: selected,
      onSelected: (_) => onSelected(),
      selectedColor: Theme.of(context).primaryColor.withOpacity(0.2),
      checkmarkColor: Theme.of(context).primaryColor,
    );
  }
}
