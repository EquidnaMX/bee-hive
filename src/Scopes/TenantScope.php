<?php

namespace Equidna\BeeHive\Scopes;

use Equidna\BeeHive\Exceptions\BeeHiveException;
use Equidna\BeeHive\Support\BeeHiveLogger;
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
            : (string) Config::get('bee-hive.tenant_key', 'id_tenant');

        /** @var TenantContext $context */
        $context = app(TenantContext::class);
        $tenantId = $context->get();

        if ($tenantId === null) {
            BeeHiveLogger::log('BeeHive tenant was not resolved for a tenant-scoped query.', [
                'model' => get_class($tenantModel),
                'tenant_key' => $tenantKey,
                'resolver' => (string) Config::get('bee-hive.resolver'),
            ], 'BEEHIVE_TENANT_UNRESOLVED_QUERY');

            throw new BeeHiveException();
        }

        $builder->where($tenantModel->getTable() . '.' . $tenantKey, $tenantId);
    }
}
