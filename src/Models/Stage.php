<?php

namespace Zaynasheff\PipelineSales\Models;

use Illuminate\Database\Eloquent\Model;
use Zaynasheff\PipelineSales\Scopes\TenantScope;

class Stage extends Model
{
    /**
     * Booted method to attach global scopes.
     * Filters stages by tenant if multitenancy is enabled.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * Auto-assign tenant foreign key when creating a stage.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (config('pipeline-sales.enable_multitenancy') && auth()->check()) {
                $tenantKey = config('pipeline-sales.tenant.foreign_key');
                $model->{$tenantKey} = auth()->user()->{$tenantKey};
            }
        });
    }

    /**
     * Fillable attributes for mass assignment.
     */
    public function getFillable(): array
    {
        $fillable = [
            'pipeline_id',
            'name',
            'position',
        ];

        if (config('pipeline-sales.enable_multitenancy')) {
            $tenantKey = config('pipeline-sales.tenant.foreign_key');
            $fillable[] = $tenantKey;
        }

        return $fillable;
    }

    /**
     * Relationship: Stage belongs to a Pipeline.
     */
    public function pipeline(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pipeline::class);
    }

    /**
     * Relationship: Stage has many Deals.
     */
    public function deals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Deal::class)->orderBy('position');
    }
}
