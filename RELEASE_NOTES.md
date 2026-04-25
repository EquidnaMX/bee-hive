# Release v2.0.0 "Iron Hive"

BeeHive v2.0.0 hardens the tenancy runtime with fail-closed behavior, stronger anti-spoofing guarantees, and stricter quality gates. This release focuses on production resilience, observability, and safer upgrade behavior from v1.0.0.

## Highlights

- Mandatory tenant enforcement for reads and writes with explicit exception flow
- Tenant spoofing neutralization on model creation with event-coded logging
- Scoped tenant runtime context to reduce cross-runtime state leakage
- Expanded quality gates: lint, static analysis, tests, dependency audit, and CI lanes

## Added

- Extended test suite for tenant isolation, resolver validation, exception contracts, and logger sampling behavior
- Configurable package logger controls with event code tagging and sampling
- Configurable tenant error HTTP status support with guarded fallback
- CI quality execution for latest and lowest dependency lanes

## Changed

- Tenant context lifecycle moved to scoped binding for better runtime isolation
- Tenant key fallback behavior unified to `id_tenant` across scope and trait paths
- Lint scope expanded to include tests in local and CI workflows
- Tenant contract normalized to `string|null` through resolver and context

## Fixed

- Missing tenant operations now log contextual warning data before raising `BeeHiveException`
- Error payload status now consistently respects configured values within safe HTTP error ranges

## Security

- Incoming tenant spoofing attempts are detected, logged, and overwritten with resolved context tenant
- Mandatory tenant resolution on query and create paths reduces accidental cross-tenant data exposure

## Breaking Changes

- `strict` mode is removed; tenant is always required for tenant-scoped operations.
- Client-provided tenant values on create are no longer honored when they differ from resolved context.
- `TenantContext` now uses scoped lifecycle semantics instead of singleton behavior.

Migration details are documented in [BREAKING_CHANGES.md](BREAKING_CHANGES.md).

## Full History and Migration

- Full project history: [CHANGELOG.md](CHANGELOG.md)
- Migration and compatibility notes: [BREAKING_CHANGES.md](BREAKING_CHANGES.md)
