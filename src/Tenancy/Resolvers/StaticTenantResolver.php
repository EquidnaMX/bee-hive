<?php

namespace Equidna\BeeHive\Tenancy\Resolvers;

use Equidna\BeeHive\Contracts\TenantResolverInterface;
use Illuminate\Support\Facades\Config;

class StaticTenantResolver implements TenantResolverInterface
{
    public function resolveTenantId(): string|null
    {
        $tenantId = Config::get('bee-hive.static_tenant_id', null);

        return $tenantId === null ? null : (string) $tenantId;
    }
}
