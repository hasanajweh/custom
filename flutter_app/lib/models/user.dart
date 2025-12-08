class User {
  final int id;
  final String name;
  final String email;
  final String? role;
  final bool isSuperAdmin;
  final bool isMainAdmin;
  final int? schoolId;
  final int? networkId;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.role,
    this.isSuperAdmin = false,
    this.isMainAdmin = false,
    this.schoolId,
    this.networkId,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      role: json['role'] as String?,
      isSuperAdmin: json['is_super_admin'] as bool? ?? false,
      isMainAdmin: json['is_main_admin'] as bool? ?? false,
      schoolId: json['school_id'] as int?,
      networkId: json['network_id'] as int?,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'role': role,
      'is_super_admin': isSuperAdmin,
      'is_main_admin': isMainAdmin,
      'school_id': schoolId,
      'network_id': networkId,
    };
  }

  bool get isAdmin => role == 'admin';
  bool get isTeacher => role == 'teacher';
  bool get isSupervisor => role == 'supervisor';
}
