# Release v1.0.0 "Honeycomb"

BeeHive reaches its first stable release with a focused, reusable multi-tenant core for Laravel. This version establishes the package baseline for tenant context resolution, query isolation, tenant assignment ergonomics, and configurable error contracts.

## Highlights

- First stable BeeHive package release for Laravel 12 and PHP 8.2+
- Pluggable tenant resolver contract with static and custom resolver support
- Automatic tenant query scoping and model tenant-key assignment
- Configurable tenant resolution error contracts for consistent API responses

## Added

- `TenantResolverInterface`, `TenantContext`, `TenantScope`, and `BelongsToTenant`
- `BeeHiveServiceProvider` with publishable `config/bee-hive.php`
- `StaticTenantResolver` and contract-based custom resolver implementations
- `BeeHiveException` with multi-contract JSON payload support
- Coding standard ruleset and package metadata baseline

## Changed

- Unresolved tenant state now always surfaces through `BeeHiveException`
- Error contract configurability added through `bee-hive.errors.*` settings
- Documentation expanded with architecture and error contract details

## Fixed

- Resolver binding now validates configuration and emits package-level exception when unavailable or invalid

## Security

- Tenant filtering and mandatory tenant-resolution flow help prevent accidental cross-tenant data access

## Full History and Migration

- Full project history: [CHANGELOG.md](CHANGELOG.md)
- Migration and compatibility notes: [BREAKING_CHANGES.md](BREAKING_CHANGES.md)
