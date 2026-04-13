<?php

namespace Equidna\BeeHive\Traits;

use Equidna\BeeHive\Scopes\TenantScope;
use Equidna\BeeHive\Tenancy\TenantContext;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model): void {
            $tenantKey = $model->getTenantKeyName();

            if (!empty($model->{$tenantKey})) {
                return;
            }

            /** @var TenantContext $context */
            $context = app(TenantContext::class);
            $tenantId = $context->get();

            if ($tenantId !== null) {
                $model->{$tenantKey} = $tenantId;
            }
        });
    }

    public function getTenantKeyName(): string
    {
        return property_exists($this, 'tenantKey')
            ? (string) $this->tenantKey
            : (string) config('bee-hive.tenant_key', 'tenant_id');
    }
}
