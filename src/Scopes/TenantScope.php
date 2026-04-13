<?php

namespace Equidna\BeeHive\Scopes;

use Equidna\BeeHive\Exceptions\BeeHiveException;
use Equidna\BeeHive\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Config;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var Model $tenantModel */
        $tenantModel = $model;

        $tenantKey = method_exists($tenantModel, 'getTenantKeyName')
            ? $tenantModel->getTenantKeyName()
            : (string) Config::get('bee-hive.tenant_key', 'tenant_id');

        /** @var TenantContext $context */
        $context = app(TenantContext::class);
        $tenantId = $context->get();

        if ($tenantId === null) {
            if ((bool) Config::get('bee-hive.strict', false)) {
                throw new BeeHiveException();
            }

            return;
        }

        $builder->where($tenantModel->getTable() . '.' . $tenantKey, $tenantId);
    }
}
