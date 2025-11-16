<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Network;
use App\Models\Plan;
use App\Models\School;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $plan = Plan::firstOrCreate(
            ['slug' => 'branches-plan'],
            [
                'name' => 'Branches Plan',
                'price_monthly' => 0,
                'price_annually' => 0,
                'storage_limit_in_gb' => 100,
                'is_active' => true,
            ]
        );

        $network = Network::firstOrCreate(
            ['slug' => 'latin'],
            [
                'name' => 'Latin Schools',
                'plan_name' => 'branches',
            ]
        );

        $mainAdmin = User::firstOrCreate(
            ['email' => 'mainadmin@example.com'],
            [
                'name' => 'Latin Main Admin',
                'password' => Hash::make('12345678'),
                'role' => 'main_admin',
                'network_id' => $network->id,
                'is_main_admin' => true,
                'is_active' => true,
            ]
        );

        $network->mainAdmin()->save($mainAdmin);

        $branches = [
            ['name' => 'Latin School 1', 'slug' => 'latin1', 'city' => 'Bethlehem'],
            ['name' => 'Latin School 2', 'slug' => 'latin2', 'city' => 'Jerusalem'],
        ];

        foreach ($branches as $branchData) {
            $school = School::firstOrCreate(
                ['slug' => $branchData['slug']],
                [
                    'name' => $branchData['name'],
                    'city' => $branchData['city'],
                    'network_id' => $network->id,
                    'is_active' => true,
                ]
            );

            Subscription::firstOrCreate(
                ['school_id' => $school->id],
                [
                    'plan_id' => $plan->id,
                    'status' => 'active',
                    'expires_at' => now()->addYear(),
                ]
            );

            // Branch admin
            User::firstOrCreate(
                ['email' => $branchData['slug'] . '@example.com'],
                [
                    'name' => $branchData['name'] . ' Admin',
                    'password' => Hash::make('12345678'),
                    'role' => 'admin',
                    'school_id' => $school->id,
                    'network_id' => $network->id,
                    'is_active' => true,
                ]
            );

            // Sample teacher
            User::factory()->create([
                'email' => $branchData['slug'] . '.teacher@example.com',
                'role' => 'teacher',
                'school_id' => $school->id,
                'network_id' => $network->id,
                'is_active' => true,
            ]);

            // Sample supervisor
            User::factory()->create([
                'email' => $branchData['slug'] . '.supervisor@example.com',
                'role' => 'supervisor',
                'school_id' => $school->id,
                'network_id' => $network->id,
                'is_active' => true,
            ]);
        }
    }
}
