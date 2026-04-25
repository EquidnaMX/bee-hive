<?php

namespace Equidna\BeeHive\Tests;

use Equidna\BeeHive\BeeHiveServiceProvider;
use Equidna\BeeHive\Tenancy\Resolvers\StaticTenantResolver;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [BeeHiveServiceProvider::class];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('bee-hive.tenant_key', 'id_tenant');
        $app['config']->set('bee-hive.resolver', StaticTenantResolver::class);
    }
}
