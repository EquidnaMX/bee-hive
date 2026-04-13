<?php

namespace Equidna\BeeHive\Tenancy\Resolvers;

use Equidna\BeeHive\Contracts\TenantResolverInterface;
use Exception;

class CaronteTenantResolver implements TenantResolverInterface
{
    public function resolveTenantId(): string | null
    {
        if (!class_exists(\Ometra\Caronte\Facades\Caronte::class)) {
            throw new Exception('Caronte is not installed');
        }

        $tenant = \Ometra\Caronte\Facades\Caronte::getTenantId();

        return $tenant;
    }
}
