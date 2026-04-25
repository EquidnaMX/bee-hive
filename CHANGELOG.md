# Changelog

All notable changes to this project will be documented in this file.

This changelog is the canonical history for BeeHive releases and should be read before preparing future releases.

## [Unreleased]

No unreleased changes.

## [v2.0.0] - 2026-04-25

### Added

- Test suite with Orchestra Testbench and PHPUnit for tenant filtering, missing-tenant failures, spoofing protection, custom tenant key support, resolver validation, and scoped context refresh.
- GitHub Actions CI workflow for composer validation, PHPCS checks, and PHPUnit execution.
- Composer scripts: `test`, `lint`, and `qa`.
- Exception contract tests (`enterprise`, `flat`, `problem_details`) and plain-text response coverage.
- Package logger strategy with configurable level/enable controls, emitted through Laravel logging channels.
- Configurable tenant error HTTP status support with validation fallback.
- Logger event codes and sampling control for operational observability.
- Static analysis checks with PHPStan integrated into local quality scripts and CI.
- CI quality lanes for both latest and lowest dependency resolution strategies.

### Changed

- `TenantContext` binding changed from singleton to scoped to better isolate runtime tenant state.
- Model creation now always enforces the resolved tenant context and neutralizes conflicting incoming tenant IDs.
- Tenant key fallback defaults are unified to `id_tenant` across scope and trait behavior.
- PHPCS ruleset metadata updated to project-specific values.
- Lint scope now includes `tests` in local scripts and CI.
- Tenant context contract is explicitly normalized to `string|null`.
- Quality command examples now align with CI lint scope (`src`, `config`, `tests`).
- Resolver and tenancy behavior now fail closed by default when tenant context is unavailable.

### Fixed

- Missing tenant resolution now logs warning context before throwing `BeeHiveException` in query and model creation paths.
- Error rendering now consistently applies configured HTTP status with a guarded fallback for invalid values.

### Security

- Tenant spoofing attempts on model creation are now detected, logged, and overwritten with the resolved tenant context.
- Mandatory tenant enforcement reduces cross-tenant leakage risk in both query and write paths.

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
- Support for custom resolver implementations through `TenantResolverInterface`.
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

- Tenant behavior now throws `BeeHiveException` whenever tenant resolution is missing.
- Configuration expanded with `errors` contract settings:
- `BEE_HIVE_ERROR_CONTRACT`
- `BEE_HIVE_ERROR_CODE`
- `BEE_HIVE_ERROR_DECORATIVE_PAYLOAD`
- README expanded to include architecture flow and error contract options.

### Fixed

- Resolver behavior now enforces explicit contract validation and returns a package-level exception when the configured resolver is unavailable or invalid.

### Security

- Tenant isolation enforcement is centralized via a global scope and mandatory tenant-resolution exception handling, reducing accidental cross-tenant query exposure.

### History Reconstruction Notes

This release section was reconstructed from the complete commit history prior to first stable tagging:

- `e2afa06` Initial commit
- `5c2fcce` Add BeeHive multi-tenant package skeleton
- `2a03a6d` Add BeeHiveException and error contract support
