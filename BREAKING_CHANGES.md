# Breaking Changes

## v3.0.0 - 2026-04-28

### Error Response Format

- The JSON error response is now always `problem_details` (RFC 7807). The `enterprise` and `flat` contracts are removed.
- Consumers parsing `error.code`, `error.message`, or `error.status` from the `enterprise` format must migrate to `code`, `detail`, and `status` at the root level.
- Consumers parsing `error` and `message` from the `flat` format must migrate to `code` and `detail` at the root level.

### Removed Configuration Keys

- `errors.contract` / `BEE_HIVE_ERROR_CONTRACT` — no longer exists; format is fixed.
- `errors.code` / `BEE_HIVE_ERROR_CODE` — no longer configurable; value is always `tenant_not_resolved`.
- `errors.include_decorative_payload` / `BEE_HIVE_ERROR_DECORATIVE_PAYLOAD` — removed entirely.

### Migration Checklist

- Remove `BEE_HIVE_ERROR_CONTRACT`, `BEE_HIVE_ERROR_CODE`, and `BEE_HIVE_ERROR_DECORATIVE_PAYLOAD` from all environment configurations.
- Update API error handling to read `code`, `detail`, and `status` from the root of the JSON response.
- Re-publish the package config: `php artisan vendor:publish --tag=bee-hive-config --force`.

## Unreleased

No unreleased breaking changes.

## v2.0.0 - 2026-04-25

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
