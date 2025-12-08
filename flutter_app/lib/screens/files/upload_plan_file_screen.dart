import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:file_picker/file_picker.dart';
import '../../providers/files_provider.dart';
import '../../widgets/main_scaffold.dart';
import '../../services/api_service.dart';

class UploadPlanFileScreen extends StatefulWidget {
  const UploadPlanFileScreen({super.key});

  @override
  State<UploadPlanFileScreen> createState() => _UploadPlanFileScreenState();
}

class _UploadPlanFileScreenState extends State<UploadPlanFileScreen> {
  final _formKey = GlobalKey<FormState>();
  final _titleController = TextEditingController();
  final _descriptionController = TextEditingController();
  
  String? _selectedPlanType; // daily_plan, weekly_plan, monthly_plan
  PlatformFile? _selectedFile;

  @override
  void initState() {
    super.initState();
    _selectedPlanType = 'daily_plan'; // Default
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

    final filesProvider = Provider.of<FilesProvider>(context, listen: false);
    
    final success = await filesProvider.uploadFile(
      title: _titleController.text.trim(),
      description: _descriptionController.text.trim().isEmpty 
          ? null 
          : _descriptionController.text.trim(),
      submissionType: _selectedPlanType!,
      fileBytes: _selectedFile!.bytes!,
      fileName: _selectedFile!.name,
      subjectId: null, // Plans don't have subjects
      gradeId: null, // Plans don't have grades
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
      title: 'Upload Lesson Plan',
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
                  labelText: 'Plan Title *',
                  hintText: 'Enter plan title',
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

              // Plan Duration (Daily, Weekly, Monthly)
              Text(
                'Plan Duration *',
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
                    child: _PlanTypeChoiceChip(
                      label: 'Daily',
                      value: 'daily_plan',
                      selected: _selectedPlanType == 'daily_plan',
                      onSelected: () => setState(() => _selectedPlanType = 'daily_plan'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: _PlanTypeChoiceChip(
                      label: 'Weekly',
                      value: 'weekly_plan',
                      selected: _selectedPlanType == 'weekly_plan',
                      onSelected: () => setState(() => _selectedPlanType = 'weekly_plan'),
                    ),
                  ),
                  const SizedBox(width: 8),
                  Expanded(
                    child: _PlanTypeChoiceChip(
                      label: 'Monthly',
                      value: 'monthly_plan',
                      selected: _selectedPlanType == 'monthly_plan',
                      onSelected: () => setState(() => _selectedPlanType = 'monthly_plan'),
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 24),

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
                    filesProvider.isLoading ? 'Uploading...' : 'Upload Plan',
                    style: const TextStyle(
                      fontSize: 18,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.orange,
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

class _PlanTypeChoiceChip extends StatelessWidget {
  final String label;
  final String value;
  final bool selected;
  final VoidCallback onSelected;

  const _PlanTypeChoiceChip({
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
      selectedColor: Colors.orange.withOpacity(0.2),
      checkmarkColor: Colors.orange,
    );
  }
}
