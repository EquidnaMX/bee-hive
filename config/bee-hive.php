<?php

use Equidna\BeeHive\Tenancy\Resolvers\CaronteTenantResolver;
use Equidna\BeeHive\Tenancy\Resolvers\StaticTenantResolver;

return [
    'tenant_key'       => env('BEE_HIVE_TENANT_KEY', 'id_tenant'),
    'static_tenant_id' => env('BEE_HIVE_STATIC_TENANT_ID', null),
    'strict'     => env('BEE_HIVE_STRICT', false),
    'resolver'   => env('BEE_HIVE_RESOLVER', StaticTenantResolver::class),
    'errors' => [
        'contract' => env('BEE_HIVE_ERROR_CONTRACT', 'enterprise'),
        'code'     => env('BEE_HIVE_ERROR_CODE', 'tenant_not_resolved'),
        'include_decorative_payload' => env('BEE_HIVE_ERROR_DECORATIVE_PAYLOAD', false),
    ],
];
