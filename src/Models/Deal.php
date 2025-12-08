<?php

namespace Zaynasheff\PipelineSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Zaynasheff\PipelineSales\Scopes\TenantScope;

class Deal extends Model
{
    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';
    /**
     * Apply tenant scope if multitenancy is enabled.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * Auto-assign tenant_id when creating a deal.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {

            if (! $model->uuid) {
                $model->uuid = Str::uuid();
            }
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
            'uuid',
            'stage_uuid',
            'name',
            'description',
            'amount',
            'priority',
            'tags',
            'due_date',
            'position',
        ];

        if (config('pipeline-sales.enable_multitenancy')) {
            $tenantKey = config('pipeline-sales.tenant.foreign_key');
            $fillable[] = $tenantKey;
        }

        return $fillable;
    }

    /**
     * Relationship: Deal belongs to a Stage.
     */
    public function stage(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
