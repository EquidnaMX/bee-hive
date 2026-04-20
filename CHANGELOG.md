# Changelog

All notable changes to this project will be documented in this file.

This changelog is the canonical history for BeeHive releases and should be read before preparing future releases.

## [v1.0.0] - 2026-04-20

First stable release of `equidna/bee-hive`, delivering a reusable multi-tenant foundation for Laravel applications.

### Added

- Core tenancy architecture:
- `TenantResolverInterface` contract for resolver implementations.
- `TenantContext` container for storing the current tenant ID during request lifecycle.
- `TenantScope` global Eloquent scope for tenant-aware query filtering.
- `BelongsToTenant` trait to automatically apply tenancy scope and tenant key population on model creation.
- Resolver implementations:
- `StaticTenantResolver` for fixed tenant resolution from configuration.
- `CaronteTenantResolver` for Caronte-based tenant resolution.
- Framework integration:
- `BeeHiveServiceProvider` with resolver binding and publishable configuration.
- Publishable package configuration at `config/bee-hive.php`.
- Error handling foundation:
- `BeeHiveException` with configurable API error contracts (`enterprise`, `flat`, `problem_details`).
- Configurable error code and optional decorative payload support.
- Project metadata and governance files:
- Initial repository setup (`LICENSE`, `.gitattributes`, `.gitignore`).
- `ruleset.xml` for coding standards checks.

### Changed

- Tenant strict-mode behavior now throws `BeeHiveException` when tenant resolution is missing (instead of silently continuing when strict mode is enabled).
- Configuration expanded with `errors` contract settings:
- `BEE_HIVE_ERROR_CONTRACT`
- `BEE_HIVE_ERROR_CODE`
- `BEE_HIVE_ERROR_DECORATIVE_PAYLOAD`
- README expanded to include architecture flow and error contract options.

### Fixed

- Resolver behavior now enforces explicit Caronte dependency checks and returns a package-level exception when Caronte is unavailable.

### Security

- Tenant isolation enforcement is centralized via a global scope and configurable strict-mode exception handling, reducing accidental cross-tenant query exposure.

### History Reconstruction Notes

This release section was reconstructed from the complete commit history prior to first stable tagging:

- `e2afa06` Initial commit
- `5c2fcce` Add BeeHive multi-tenant package skeleton
- `2a03a6d` Add BeeHiveException and error contract support
