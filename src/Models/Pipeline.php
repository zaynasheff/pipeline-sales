<?php

namespace Zaynasheff\PipelineSales\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Zaynasheff\PipelineSales\Scopes\TenantScope;

class Pipeline extends Model
{
    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    /**
     * Booted method to attach global scopes.
     * Here we attach the TenantScope to automatically filter
     * records by the tenant (company) if multitenancy is enabled.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * Boot method to handle model events.
     * Automatically assigns tenant foreign key when creating a new record.
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
     * Get the fillable attributes for the model.
     * Dynamically includes the tenant foreign key from config if multitenancy is enabled.
     */
    public function getFillable(): array
    {
        $fillable = [
            'uuid',
            'name',
            'description',
            'position',
        ];

        if (config('pipeline-sales.enable_multitenancy')) {
            $tenantKey = config('pipeline-sales.tenant.foreign_key');
            $fillable[] = $tenantKey;
        }

        return $fillable;
    }

    /**
     * Relationship: Pipeline has many Stages.
     */
    public function stages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Stage::class)->orderBy('position');
    }
}
