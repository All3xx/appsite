<?php

namespace App\Models\Concerns;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantId = app(TenantContext::class)->tenantId();
            if ($tenantId) {
                $builder->where($builder->getModel()->getTable() . '.tenant_id', $tenantId);
            } else {
                // No tenant in context. Block data by default.
                $builder->whereRaw('1=0');
            }
        });

        static::creating(function (Model $model) {
            $tenantId = app(TenantContext::class)->tenantId();
            if (!$tenantId) {
                throw new \RuntimeException('TenantContext is missing for tenant model creation.');
            }
            if (empty($model->getAttribute('tenant_id'))) {
                $model->setAttribute('tenant_id', $tenantId);
            }
        });
    }
}
