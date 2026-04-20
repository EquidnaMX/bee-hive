# Breaking Changes

## v1.0.0

No breaking changes are identified for this first stable release.

### Why

This release establishes the initial stable API surface of BeeHive. There are no earlier stable versions to break against.

### Migration Guidance

- If you consumed pre-release snapshots before `v1.0.0`, verify your resolver class still implements `TenantResolverInterface`.
- Confirm your error response expectations match the configured contract in `config/bee-hive.php` (`enterprise`, `flat`, or `problem_details`).
- If you rely on strict tenant enforcement, ensure `BEE_HIVE_STRICT=true` is set where required.
