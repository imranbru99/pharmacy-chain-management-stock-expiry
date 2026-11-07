# Architecture decisions

## Laravel 12 (not 13)

The application targets **Laravel 12** because required first-party and ImranDev packages declare `illuminate/*` compatibility for **^11|^12** only. Moving to Laravel 13 prematurely would block package upgrades and Filament 4 integration until those libraries catch up.

## Single-database tenancy with `tenant_id`

Tenancy uses a **single database** and a **`tenant_id` global scope** (via `BelongsToTenant` + `TenantContext`), not [stancl/tenancy](https://tenancyforlaravel.com/) style database-per-tenant. This keeps cross-branch reporting, chain-wide catalog, and SaaS billing queries straightforward while still isolating tenant data at the ORM layer.

Super-admin users keep `tenant_id = null` and bypass the scope when no tenant context is set.

## Filament 4 + Laravel Admin Panel

**Filament 4** is the primary admin CRUD and panel UI. **laravel-admin-panel** provides shared dashboard widgets, themes, and render hooks that Filament resources can compose with (`AdminPanelAdapter`).

## Money as integer minor units

All monetary amounts are stored as **integers in minor units** (e.g. poisha for BDT, cents for USD). Display formatting goes through `money_format()` so rounding and locale symbols stay consistent.

## Module toggles

Feature modules live under `app/Modules/*/module.json`. `ModuleManager` enforces dependency chains; `tenant_modules` persists per-tenant enablement, synced from `plans.modules_json` via `syncFromPlan()`.
