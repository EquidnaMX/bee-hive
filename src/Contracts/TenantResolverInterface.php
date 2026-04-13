<?php

namespace Equidna\BeeHive\Contracts;

interface TenantResolverInterface
{
    public function resolveTenantId(): string|null;
}
