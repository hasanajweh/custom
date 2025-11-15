<?php
namespace App\Traits;
use App\Models\School;
use App\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
trait BelongsToTenant {
    protected static function bootBelongsToTenant(): void {
        static::addGlobalScope(new TenantScope);
        static::creating(function ($model) {
            if (app()->has(School::class)) {
                $model->school_id = app(School::class)->id;
            }
        });
    }
    public function school(): BelongsTo {
        return $this->belongsTo(School::class);
    }
}
