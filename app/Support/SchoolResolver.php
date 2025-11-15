<?php

namespace App\Support;

use App\Models\Network;
use App\Models\School;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SchoolResolver
{
    /**
     * Resolve the given route parameter into a School model instance.
     */
    public static function resolve(mixed $value, bool $failIfMissing = false): ?School
    {
        if ($value instanceof School) {
            return $value;
        }

        if ($value === null || $value === '') {
            return null;
        }

        $query = School::query();

        if (is_numeric($value)) {
            $query->whereKey($value);
        } else {
            $query->where('slug', $value);
        }

        $network = request()->route('network');

        if ($network instanceof Network) {
            $query->where('network_id', $network->id);
        } elseif (is_string($network)) {
            $networkModel = Network::where('slug', $network)->first();

            if ($networkModel) {
                $query->where('network_id', $networkModel->id);
            }
        }

        $school = $query->first();

        if ($school || ! $failIfMissing) {
            return $school;
        }

        throw (new ModelNotFoundException())->setModel(School::class, [$value]);
    }
}