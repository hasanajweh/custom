import 'school.dart';

class UserContext {
  final int schoolId;
  final String schoolSlug;
  final String schoolName;
  final String? networkSlug;
  final String? networkName;
  final String role;

  UserContext({
    required this.schoolId,
    required this.schoolSlug,
    required this.schoolName,
    this.networkSlug,
    this.networkName,
    required this.role,
  });

  factory UserContext.fromJson(Map<String, dynamic> json) {
    return UserContext(
      schoolId: json['school_id'] != null ? (json['school_id'] as num).toInt() : 0,
      schoolSlug: (json['school_slug'] ?? '') as String,
      schoolName: (json['school_name'] ?? '') as String,
      networkSlug: json['network_slug'] as String?,
      networkName: json['network_name'] as String?,
      role: (json['role'] ?? '') as String,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'school_id': schoolId,
      'school_slug': schoolSlug,
      'school_name': schoolName,
      'network_slug': networkSlug,
      'network_name': networkName,
      'role': role,
    };
  }
}

class CurrentContext {
  final Network network;
  final School school;

  CurrentContext({
    required this.network,
    required this.school,
  });

  factory CurrentContext.fromJson(Map<String, dynamic> json) {
    if (json['network'] == null || json['school'] == null) {
      throw Exception('Network and school are required for CurrentContext');
    }
    return CurrentContext(
      network: Network.fromJson(json['network'] as Map<String, dynamic>),
      school: School.fromJson(json['school'] as Map<String, dynamic>),
    );
  }
}
