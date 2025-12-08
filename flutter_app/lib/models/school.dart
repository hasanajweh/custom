class School {
  final int id;
  final String name;
  final String slug;
  final int networkId;
  final String? city;
  final bool isActive;
  final Network? network;

  School({
    required this.id,
    required this.name,
    required this.slug,
    required this.networkId,
    this.city,
    this.isActive = true,
    this.network,
  });

  factory School.fromJson(Map<String, dynamic> json) {
    return School(
      id: (json['id'] ?? 0) as int,
      name: (json['name'] ?? '') as String,
      slug: (json['slug'] ?? '') as String,
      networkId: json['network_id'] != null ? (json['network_id'] as num).toInt() : 0,
      city: json['city'] as String?,
      isActive: (json['is_active'] as bool?) ?? true,
      network: json['network'] != null 
          ? Network.fromJson(json['network'] as Map<String, dynamic>)
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
      'network_id': networkId,
      'city': city,
      'is_active': isActive,
      'network': network?.toJson(),
    };
  }
}

class Network {
  final int id;
  final String name;
  final String slug;

  Network({
    required this.id,
    required this.name,
    required this.slug,
  });

  factory Network.fromJson(Map<String, dynamic> json) {
    return Network(
      id: (json['id'] ?? 0) as int,
      name: (json['name'] ?? '') as String,
      slug: (json['slug'] ?? '') as String,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'slug': slug,
    };
  }
}
