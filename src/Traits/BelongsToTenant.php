<?php

namespace Equidna\BeeHive\Traits;

use Equidna\BeeHive\Exceptions\BeeHiveException;
use Equidna\BeeHive\Scopes\TenantScope;
use Equidna\BeeHive\Support\BeeHiveLogger;
use Equidna\BeeHive\Tenancy\TenantContext;
use Illuminate\Support\Facades\Config;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model): void {
            $tenantKey = $model->getTenantKeyName();

            /** @var TenantContext $context */
            $context = app(TenantContext::class);
            $tenantId = $context->get();

            if ($tenantId === null) {
                BeeHiveLogger::log('BeeHive tenant was not resolved while creating a tenant-scoped model.', [
                    'model' => get_class($model),
                    'tenant_key' => $tenantKey,
                ], 'BEEHIVE_TENANT_UNRESOLVED_CREATE');

                throw new BeeHiveException();
            }

            $incomingTenantId = $model->{$tenantKey} ?? null;

            if ($incomingTenantId !== null && (string) $incomingTenantId !== (string) $tenantId) {
                BeeHiveLogger::log('BeeHive detected and neutralized a tenant spoofing attempt.', [
                    'model' => get_class($model),
                    'tenant_key' => $tenantKey,
                    'incoming_tenant_id' => $incomingTenantId,
                    'resolved_tenant_id' => $tenantId,
                ], 'BEEHIVE_TENANT_SPOOF_ATTEMPT');
            }

            // Always enforce tenant from context to avoid tenant spoofing on create.
            $model->{$tenantKey} = (string) $tenantId;
        });
    }

    public function getTenantKeyName(): string
    {
        $properties = get_object_vars($this);

        return array_key_exists('tenantKey', $properties)
            ? (string) $properties['tenantKey']
            : (string) Config::get('bee-hive.tenant_key', 'id_tenant');
    }
}
