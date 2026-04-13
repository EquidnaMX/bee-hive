<?php

namespace Equidna\BeeHive\Tenancy\Resolvers;

use Equidna\BeeHive\Contracts\TenantResolverInterface;

class StaticTenantResolver implements TenantResolverInterface
{
    public function resolveTenantId(): string|null
    {
        return config('bee-hive.static_tenant_id');
    }
}
