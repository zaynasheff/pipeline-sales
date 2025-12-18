<?php

namespace Zaynasheff\PipelineSales\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    /**
     * Применяет глобальный scope к модели.
     * Фильтрует записи по текущему tenant_id, если мультитенантность включена.
     */
    public function apply(Builder $builder, Model $model): void
    {

        // Если мультитенантность отключена — не фильтруем
        if (! config('pipeline-sales.enable_multitenancy')) {

            return;
        }

        // Получаем ключ tenant из конфига
        $tenantKey = config('pipeline-sales.tenant.foreign_key', 'company_id');

        // Если пользователь не авторизован — тоже не фильтруем
        if (! auth()->check()) {
            return;
        }

        // Применяем фильтр по tenant
        $builder->where($model->getTable() . '.' . $tenantKey, auth()->user()->{$tenantKey});
    }
}
