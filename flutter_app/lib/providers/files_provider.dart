import 'package:flutter/foundation.dart';
import '../services/api_service.dart';
import '../models/file_submission.dart';

class FilesProvider with ChangeNotifier {
  List<FileSubmission> _files = [];
  bool _isLoading = false;
  String? _error;
  String _searchQuery = '';
  String? _filterType;
  int? _filterSubjectId;
  int? _filterGradeId;

  List<FileSubmission> get files => _files;
  bool get isLoading => _isLoading;
  String? get error => _error;

  Future<void> loadFiles() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final queryParams = <String, String>{};
      if (_filterType != null) {
        queryParams['type'] = _filterType!;
      }
      if (_filterSubjectId != null) {
        queryParams['subject_id'] = _filterSubjectId.toString();
      }
      if (_filterGradeId != null) {
        queryParams['grade_id'] = _filterGradeId.toString();
      }
      if (_searchQuery.isNotEmpty) {
        queryParams['search'] = _searchQuery;
      }

      final data = await ApiService.get('/files', queryParameters: queryParams);

      _files = (data['files'] as List)
          .map((file) => FileSubmission.fromJson(file as Map<String, dynamic>))
          .toList();
    } catch (e) {
      _error = e.toString();
      _files = [];
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  void search(String query) {
    _searchQuery = query;
    loadFiles();
  }

  void filter({
    String? type,
    int? subjectId,
    int? gradeId,
  }) {
    _filterType = type;
    _filterSubjectId = subjectId;
    _filterGradeId = gradeId;
    loadFiles();
  }

  Future<bool> uploadFile({
    required String title,
    String? description,
    required String submissionType,
    required List<int> fileBytes,
    required String fileName,
    int? subjectId,
    int? gradeId,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final fields = <String, String>{
        'title': title,
        'submission_type': submissionType,
        if (description != null) 'description': description,
        if (subjectId != null) 'subject_id': subjectId.toString(),
        if (gradeId != null) 'grade_id': gradeId.toString(),
      };

      await ApiService.postMultipart(
        '/files',
        fields,
        'file',
        fileBytes,
        fileName,
      );

      await loadFiles();
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> deleteFile(int fileId) async {
    try {
      await ApiService.delete('/files/$fileId');
      await loadFiles();
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    }
  }

  Future<String?> getDownloadUrl(int fileId) async {
    try {
      final data = await ApiService.get('/files/$fileId/download');
      return data['download_url'] as String?;
    } catch (e) {
      _error = e.toString();
      return null;
    }
  }
}
