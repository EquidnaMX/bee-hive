<?php

namespace Equidna\BeeHive\Tenancy\Resolvers;

use Equidna\BeeHive\Contracts\TenantResolverInterface;
use Equidna\BeeHive\Exceptions\BeeHiveException;
use Exception;

class CaronteTenantResolver implements TenantResolverInterface
{
    public function resolveTenantId(): string|null
    {
        if (!class_exists(\Ometra\Caronte\Facades\Caronte::class)) {
            throw new BeeHiveException('Caronte is not installed');
        }

        $tenant = \Ometra\Caronte\Facades\Caronte::getTenantId();

        return $tenant;
    }
}
