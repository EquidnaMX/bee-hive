# Release v1.0.0 "Honeycomb"

BeeHive reaches its first stable release with a focused, reusable multi-tenant core for Laravel. This version establishes the package baseline for tenant context resolution, query isolation, tenant assignment ergonomics, and configurable error contracts.

## Highlights

- First stable BeeHive package release for Laravel 12 and PHP 8.2+
- Pluggable tenant resolver contract with static and Caronte-backed resolvers
- Automatic tenant query scoping and model tenant-key assignment
- Configurable tenant resolution error contracts for consistent API responses

## Added

- `TenantResolverInterface`, `TenantContext`, `TenantScope`, and `BelongsToTenant`
- `BeeHiveServiceProvider` with publishable `config/bee-hive.php`
- `StaticTenantResolver` and `CaronteTenantResolver`
- `BeeHiveException` with multi-contract JSON payload support
- Coding standard ruleset and package metadata baseline

## Changed

- Strict mode now surfaces unresolved tenant state through `BeeHiveException`
- Error contract configurability added through `bee-hive.errors.*` settings
- Documentation expanded with architecture and error contract details

## Fixed

- Caronte resolver now validates dependency presence and emits package-level exception when unavailable

## Security

- Tenant filtering and strict resolution flow help prevent accidental cross-tenant data access

## Full History and Migration

- Full project history: [CHANGELOG.md](CHANGELOG.md)
- Migration and compatibility notes: [BREAKING_CHANGES.md](BREAKING_CHANGES.md)
