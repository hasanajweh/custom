<?php

use App\Models\Network;
use App\Models\School;
use App\Models\SchoolUserRole;
use App\Models\User;
use App\Services\ActiveContext;
use App\Services\TenantContext;
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

test('context switch works for user with multiple roles in same school', function () {
    $network = Network::create([
        'name' => 'Latin',
        'slug' => 'latin',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $school = School::factory()->create([
        'network_id' => $network->id,
        'slug' => 'latin1',
    ]);

    $user = User::factory()->create([
        'role' => 'supervisor',
        'school_id' => $school->id,
        'network_id' => $network->id,
    ]);

    // Assign both supervisor and teacher roles to the user
    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school->id,
        'role' => 'supervisor',
    ]);

    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school->id,
        'role' => 'teacher',
    ]);

    $this->actingAs($user);

    // Set initial context as supervisor
    ActiveContext::setContext($school->id, 'supervisor');

    // Switch to teacher role
    $response = $this->post("/{$network->slug}/{$school->slug}/switch-context", [
        'school_id' => $school->id,
        'role' => 'teacher',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    // Verify context was switched
    expect(session('active_school_id'))->toBe($school->id);
    expect(session('active_role'))->toBe('teacher');
});

test('context switch fails for role user does not have', function () {
    $network = Network::create([
        'name' => 'Latin',
        'slug' => 'latin',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $school = School::factory()->create([
        'network_id' => $network->id,
        'slug' => 'latin1',
    ]);

    $user = User::factory()->create([
        'role' => 'teacher',
        'school_id' => $school->id,
        'network_id' => $network->id,
    ]);

    // Only assign teacher role
    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school->id,
        'role' => 'teacher',
    ]);

    $this->actingAs($user);

    ActiveContext::setContext($school->id, 'teacher');

    // Try to switch to admin role (which user doesn't have)
    $response = $this->post("/{$network->slug}/{$school->slug}/switch-context", [
        'school_id' => $school->id,
        'role' => 'admin',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('error');
});

test('context switch works between different schools in same network', function () {
    $network = Network::create([
        'name' => 'Latin',
        'slug' => 'latin',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $school1 = School::factory()->create([
        'network_id' => $network->id,
        'slug' => 'latin1',
        'name' => 'Latin School 1',
    ]);

    $school2 = School::factory()->create([
        'network_id' => $network->id,
        'slug' => 'latin2',
        'name' => 'Latin School 2',
    ]);

    $user = User::factory()->create([
        'role' => 'teacher',
        'school_id' => $school1->id,
        'network_id' => $network->id,
    ]);

    // Teacher in school1, supervisor in school2
    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school1->id,
        'role' => 'teacher',
    ]);

    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school2->id,
        'role' => 'supervisor',
    ]);

    $this->actingAs($user);

    // Start in school1 as teacher
    ActiveContext::setContext($school1->id, 'teacher');

    // Switch to school2 as supervisor (use school1's URL for the POST)
    $response = $this->post("/{$network->slug}/{$school1->slug}/switch-context", [
        'school_id' => $school2->id,
        'role' => 'supervisor',
    ]);

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();

    // Verify context was switched to school2 as supervisor
    expect(session('active_school_id'))->toBe($school2->id);
    expect(session('active_role'))->toBe('supervisor');

    // Verify redirect URL contains correct school slug
    $redirectUrl = $response->headers->get('Location');
    expect($redirectUrl)->toContain($school2->slug);
    expect($redirectUrl)->toContain('supervisor');
});

test('GET request to switch-context redirects to dashboard', function () {
    $network = Network::create([
        'name' => 'Latin',
        'slug' => 'latin',
        'plan_name' => 'basic',
        'is_active' => true,
    ]);

    $school = School::factory()->create([
        'network_id' => $network->id,
        'slug' => 'latin1',
    ]);

    $user = User::factory()->create([
        'role' => 'teacher',
        'school_id' => $school->id,
        'network_id' => $network->id,
    ]);

    SchoolUserRole::create([
        'user_id' => $user->id,
        'school_id' => $school->id,
        'role' => 'teacher',
    ]);

    $this->actingAs($user);
    ActiveContext::setContext($school->id, 'teacher');

    // GET request should redirect, not 404
    $response = $this->get("/{$network->slug}/{$school->slug}/switch-context");

    $response->assertRedirect();
    $response->assertSessionHasNoErrors();
});
