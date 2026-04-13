<?php

namespace Equidna\BeeHive\Scopes;

use Equidna\BeeHive\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use RuntimeException;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var Model $tenantModel */
        $tenantModel = $model;

        $tenantKey = method_exists($tenantModel, 'getTenantKeyName')
            ? $tenantModel->getTenantKeyName()
            : (string) config('bee-hive.tenant_key', 'tenant_id');

        /** @var TenantContext $context */
        $context = app(TenantContext::class);
        $tenantId = $context->get();

        if ($tenantId === null) {
            if ((bool) config('bee-hive.strict', false)) {
                throw new RuntimeException('BeeHive tenant was not resolved.');
            }

            return;
        }

        $builder->where($tenantModel->getTable() . '.' . $tenantKey, $tenantId);
    }
}
