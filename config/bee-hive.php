<?php

use Equidna\BeeHive\Tenancy\Resolvers\StaticTenantResolver;

return [
    'tenant_key'       => env('BEE_HIVE_TENANT_KEY', 'id_tenant'),
    'static_tenant_id' => env('BEE_HIVE_STATIC_TENANT_ID', null),
    'resolver'         => env('BEE_HIVE_RESOLVER', StaticTenantResolver::class),
    'errors' => [
        'status' => env('BEE_HIVE_ERROR_STATUS', 422),
    ],
    'logging' => [
        'enabled' => env('BEE_HIVE_LOGGING_ENABLED', true),
        'level' => env('BEE_HIVE_LOG_LEVEL', 'warning'),
        'sample_rate' => env('BEE_HIVE_LOG_SAMPLE_RATE', 1.0),
    ],
];
