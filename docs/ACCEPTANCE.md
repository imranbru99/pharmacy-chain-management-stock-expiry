# Acceptance checklist

Verified locally on **2026-11-07** (PHP 8.5, Laravel 12.71, Filament 4.16, sqlite).

## Verified (ran and confirmed)

- [x] `composer install` / packages discover for all seven `imrandevbd/*` packages
- [x] `php artisan migrate:fresh --seed --force` completes successfully
- [x] Demo counts: **1500** products, **40** suppliers, **5200** batches, **500** customers, **2000** sales, **16** demo users, **2** tenants, **3** plans, 1 warehouse + 6 branches
- [x] `php artisan test` — **19 passed** (FEFO, stock ledger, tenant isolation, module toggle, POS, GRN, journal, loyalty, forecast, online order)
- [x] `vendor/bin/pint` — clean after run
- [x] Public HTTP 200: `/`, `/features`, `/pricing`, `/demo`, `/modules/explorer`, `/stock-and-expiry`, `/about`, `/contact`, `/branches`, `/register-pharmacy`, solutions, FAQ, legal, blog, store, integrations, compliance, `/sitemap.xml`
- [x] Auth pages 200: `/admin/login`, `/staff/login`, `/super-admin/login`
- [x] Bangla+English invoice PDF via `laravel-unicode-pdf` → `storage/app/demo-bangla-invoice.pdf` (~256 KB)
- [x] Module registry loads **25** module manifests; toggle middleware + `module_enabled()` tested
- [x] Tenant global scope isolation tested
- [x] FEFO picker + stock ledger concurrency guards (row lock) tested
- [x] Purchasing GRN creates batch and increases stock (tested)
- [x] `npm run build` succeeds (Vite)
- [x] Docker files present: `Dockerfile`, `docker-compose.yml`
- [x] GitHub Actions disabled (no `.github/workflows`; not required for this project)
- [x] Docs: README, PACKAGES, DECISIONS, MODULES, ROLES, API, DEPLOYMENT, ACCEPTANCE
- [x] Adapters under `app/Support/Adapters/` for all seven packages
- [x] `demo:reset` command registered; `config/demo.php` present

## Partially complete / honest gaps

- [ ] **Coverage ≥80%** on all core domain — Pest suite covers critical paths (19 tests) but not full coverage report ≥80%; add more tests before claiming coverage target
- [ ] **Larastan level 6+** — `phpstan.neon` + baseline exist; full level-6 clean run not asserted as zero baseline debt
- [ ] **12 months of purchases/transfers/returns/payroll/attendance** — sales seeded across ~12 months; full HR attendance logs, payroll history, purchase history, and transfer history are thinner than sales (extend seeders for denser report charts)
- [ ] **Payment gateway live adapters** (Stripe/bKash/Nagad/SSLCommerz) — interfaces/config keys present; live charge flows not production-wired
- [ ] **SMS/WhatsApp providers** — notification templates + dispatcher stubs; real gateway credentials not exercised
- [ ] **Offline-tolerant POS** — keyboard/barcode POS page exists; full offline sync/PWA queue not implemented
- [ ] **OpenAPI published artifact** — API v1 routes + docs outline; generated OpenAPI file not published as CI artifact
- [ ] **Lighthouse 95+** — not measured in this environment (no Chrome Lighthouse run)
- [ ] **Impersonation UI + IP allowlist screens** — roles/audit foundations present; polished Super Admin impersonation UX incomplete
- [ ] **Doctor portal e-prescribe UI** — doctor demo user + role exist; full portal UX is stub-level
- [ ] **Redis** — configured in `.env.example` / Docker; local verification used sqlite + database cache/queue drivers
- [ ] **MySQL** — Docker Compose provides MySQL; local acceptance used sqlite

## Module presence (25)

| Module | Manifest | Domain services / resources |
| --- | --- | --- |
| Core, Users | yes | yes |
| Catalog, Stock, Expiry, Transfers | yes | yes + Filament |
| Stock Counts, Forecasting | yes | yes + Filament |
| Suppliers, Purchasing | yes | yes + Filament |
| POS, Sales, Prescriptions, Customers, Loyalty | yes | yes + Filament |
| Online Orders, Delivery | yes | yes + Filament |
| Finance, HR, Compliance | yes | yes + Filament |
| Notifications, AI, Reports, CMS, Billing | yes | services + pages/resources |

## Demo accounts (password: `password`)

See README / `/demo` one-click login for all 16 roles.
