<?php

use App\Models\Network;
use App\Models\School;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        School::whereNull('network_id')->chunkById(100, function ($schools) {
            foreach ($schools as $school) {
                $name = $school->name ?: 'Auto Network';
                $baseSlug = Str::slug($name);
                $slug = $baseSlug;
                $suffix = 1;

                while (Network::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $suffix;
                    $suffix++;
                }

                $network = Network::create([
                    'name' => $name . ' Network',
                    'slug' => $slug,
                    'plan_name' => 'branches',
                    'is_active' => true,
                ]);

                $school->network()->associate($network);
                $school->save();
            }
        });
    }

    public function down(): void
    {
        // No automatic rollback to avoid detaching schools from valid networks.
    }
};
