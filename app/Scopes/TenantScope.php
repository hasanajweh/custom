<?php
namespace App\Scopes;
use App\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope {
    public function apply(Builder $builder, Model $model): void {
        if (app()->has(School::class)) {
            $builder->where($model->getTable() . '.school_id', app(School::class)->id);
        }
    }
}
