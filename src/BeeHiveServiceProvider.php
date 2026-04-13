<?php

namespace Equidna\BeeHive;

use Equidna\BeeHive\Contracts\TenantResolverInterface;
use Equidna\BeeHive\Tenancy\TenantContext;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class BeeHiveServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bee-hive.php', 'bee-hive');

        $this->app->bind(TenantResolverInterface::class, function ($app) {
            $resolverClass = config('bee-hive.resolver');

            if (!$resolverClass) {
                throw new InvalidArgumentException(
                    'BeeHive resolver is not configured. Set bee-hive.resolver to a class that implements TenantResolverInterface.'
                );
            }

            $resolver = $app->make($resolverClass);

            if (!$resolver instanceof TenantResolverInterface) {
                throw new InvalidArgumentException(
                    'Configured BeeHive resolver must implement TenantResolverInterface.'
                );
            }

            return $resolver;
        });

        $this->app->singleton(TenantContext::class, function ($app) {
            $context = new TenantContext();
            $tenantId = $app->make(TenantResolverInterface::class)->resolveTenantId();
            $context->set($tenantId);

            return $context;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/bee-hive.php' => config_path('bee-hive.php'),
        ], 'bee-hive:config');
    }
}
