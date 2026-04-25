# Breaking Changes

## Unreleased

The following changes are breaking for consumers upgrading from `v1.0.0` behavior:

### Tenant Is Always Required

- The `strict` option has been removed from `config/bee-hive.php`.
- Tenant-scoped queries and model creation now always fail with `BeeHiveException` when tenant resolution is missing.

### Tenant Input Override Is No Longer Accepted

- `BelongsToTenant` now always enforces the tenant from `TenantContext` during model creation.
- Any incoming tenant value that differs from resolved context is overwritten and logged.

### Runtime Context Lifecycle

- `TenantContext` is now container-scoped instead of singleton to reduce cross-runtime state leakage.

### Migration Checklist

- Remove any use of `BEE_HIVE_STRICT` from environment configuration.
- Ensure your resolver always returns a tenant in all runtime contexts where tenant-scoped models are used.
- Remove client-controlled tenant key fields from write payload contracts.
- If administrative cross-tenant writes are required, implement explicit bypass logic outside of `BelongsToTenant` flows.

## v1.0.0

No breaking changes are identified for this first stable release.

### Why

This release establishes the initial stable API surface of BeeHive. There are no earlier stable versions to break against.

### Migration Guidance

- If you consumed pre-release snapshots before `v1.0.0`, verify your resolver class still implements `TenantResolverInterface`.
- Confirm your error response expectations match the configured contract in `config/bee-hive.php` (`enterprise`, `flat`, or `problem_details`).
- Ensure a tenant resolver is configured and can always resolve a tenant in runtime contexts where tenant-scoped models are queried.
