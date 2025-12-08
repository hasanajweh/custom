import 'user.dart';
import 'school.dart';

class FileSubmission {
  final int id;
  final String title;
  final String? description;
  final String submissionType;
  final String? filePath;
  final String? fileName;
  final int? fileSize;
  final String? mimeType;
  final int downloadCount;
  final int userId;
  final int schoolId;
  final int? subjectId;
  final int? gradeId;
  final DateTime createdAt;
  final DateTime? updatedAt;
  final User? user;
  final Subject? subject;
  final Grade? grade;
  final School? school;

  FileSubmission({
    required this.id,
    required this.title,
    this.description,
    required this.submissionType,
    this.filePath,
    this.fileName,
    this.fileSize,
    this.mimeType,
    this.downloadCount = 0,
    required this.userId,
    required this.schoolId,
    this.subjectId,
    this.gradeId,
    required this.createdAt,
    this.updatedAt,
    this.user,
    this.subject,
    this.grade,
    this.school,
  });

  factory FileSubmission.fromJson(Map<String, dynamic> json) {
    return FileSubmission(
      id: (json['id'] ?? 0) as int,
      title: (json['title'] ?? '') as String,
      description: json['description'] as String?,
      submissionType: (json['submission_type'] ?? '') as String,
      filePath: json['file_path'] as String?,
      fileName: json['file_name'] as String? ?? json['original_filename'] as String?,
      fileSize: json['file_size'] != null ? (json['file_size'] as num).toInt() : null,
      mimeType: json['mime_type'] as String?,
      downloadCount: json['download_count'] != null ? (json['download_count'] as num).toInt() : 0,
      userId: json['user_id'] != null ? (json['user_id'] as num).toInt() : 0,
      schoolId: json['school_id'] != null ? (json['school_id'] as num).toInt() : 0,
      subjectId: json['subject_id'] != null ? (json['subject_id'] as num).toInt() : null,
      gradeId: json['grade_id'] != null ? (json['grade_id'] as num).toInt() : null,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : DateTime.now(),
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'] as Map<String, dynamic>)
          : null,
      subject: json['subject'] != null
          ? Subject.fromJson(json['subject'] as Map<String, dynamic>)
          : null,
      grade: json['grade'] != null
          ? Grade.fromJson(json['grade'] as Map<String, dynamic>)
          : null,
      school: json['school'] != null
          ? School.fromJson(json['school'] as Map<String, dynamic>)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'submission_type': submissionType,
      'file_path': filePath,
      'file_name': fileName,
      'file_size': fileSize,
      'mime_type': mimeType,
      'download_count': downloadCount,
      'user_id': userId,
      'school_id': schoolId,
      'subject_id': subjectId,
      'grade_id': gradeId,
      'created_at': createdAt.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}

class Subject {
  final int id;
  final String name;
  final int schoolId;
  final bool isActive;

  Subject({
    required this.id,
    required this.name,
    required this.schoolId,
    this.isActive = true,
  });

  factory Subject.fromJson(Map<String, dynamic> json) {
    return Subject(
      id: (json['id'] ?? 0) as int,
      name: (json['name'] ?? '') as String,
      schoolId: json['school_id'] != null ? (json['school_id'] as num).toInt() : 0,
      isActive: (json['is_active'] as bool?) ?? true,
    );
  }
}

class Grade {
  final int id;
  final String name;
  final int schoolId;
  final bool isActive;

  Grade({
    required this.id,
    required this.name,
    required this.schoolId,
    this.isActive = true,
  });

  factory Grade.fromJson(Map<String, dynamic> json) {
    return Grade(
      id: (json['id'] ?? 0) as int,
      name: (json['name'] ?? '') as String,
      schoolId: json['school_id'] != null ? (json['school_id'] as num).toInt() : 0,
      isActive: (json['is_active'] as bool?) ?? true,
    );
  }
}
