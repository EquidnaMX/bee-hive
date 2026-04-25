<?php

namespace Equidna\BeeHive\Tenancy;

class TenantContext
{
    private string|null $tenantId = null;

    public function set(string|null $tenantId): void
    {
        $this->tenantId = $tenantId;
    }

    public function get(): string|null
    {
        return $this->tenantId;
    }

    public function has(): bool
    {
        return $this->tenantId !== null;
    }

    public function clear(): void
    {
        $this->tenantId = null;
    }
}
