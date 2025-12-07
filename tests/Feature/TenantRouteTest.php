<?php

use App\Models\Network;
use App\Models\School;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('safe tenant route degrades gracefully without tenant context', function () {
    $user = User::factory()->create([
        'is_super_admin' => true,
        'role' => 'super_admin',
    ]);

    $this->actingAs($user);

    $url = safe_tenant_route('login');

    expect($url)->toBe('#');
});

test('tenant route builds tenant url when context is present', function () {
    $network = Network::create([
        'name' => 'Net',
        'slug' => 'net',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $school = School::factory()->create([
        'network_id' => $network->id,
    ]);

    $user = User::factory()->create([
        'role' => 'admin',
        'school_id' => $school->id,
        'network_id' => $network->id,
    ]);

    $this->actingAs($user);

    $url = tenant_route('dashboard', $school);

    expect($url)->toContain($network->slug)->toContain($school->slug);
});

test('main admin login page renders without tenant routing context', function () {
    $network = Network::create([
        'name' => 'Net',
        'slug' => 'net',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $response = $this->get("/{$network->slug}/main-admin/login");

    $response->assertStatus(200);
});
