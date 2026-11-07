# Integrated packages

Seven ImranDev BD packages are installed for pharmacy-chain features. Each is wrapped by an adapter under `app/Support/Adapters/` so tenant settings and testing fakes stay in one place.

## 1. `imrandevbd/laravel-universal-slug`

| Item | Detail |
|------|--------|
| Install | Composer require; `vendor:publish --tag=universal-slug-config` (config present). |
| Used for | `Tenant`, `Plan`, and `Branch` slugs via `HasUniversalSlug` + `#[Slug]`. |
| Adapter | `App\Support\Adapters\UniversalSlugAdapter` |

## 2. `imrandevbd/laravel-unicode-pdf`

| Item | Detail |
|------|--------|
| Install | Composer require; `config/unicode-pdf.php` published. |
| Used for | Bengali/Unicode invoices, labels, and compliance PDFs. |
| Adapter | `App\Support\Adapters\UnicodePdfAdapter` |

## 3. `imrandevbd/laravel-ai-hub`

| Item | Detail |
|------|--------|
| Install | Composer require; `config/ai-hub.php` published. |
| Used for | Tenant-scoped LLM calls (forecasting, AI module, support bots). |
| Adapter | `App\Support\Adapters\AiHubAdapter` (tenant API keys + prompt masking) |

## 4. `imrandevbd/laravel-ai-catalog`

| Item | Detail |
|------|--------|
| Install | Composer require; `config/ai-catalog.php` published. |
| Used for | AI-generated catalog copy, SEO feeds, approval workflow. |
| Adapter | `App\Support\Adapters\AiCatalogAdapter` |

## 5. `imrandevbd/laravel-attendance-hub`

| Item | Detail |
|------|--------|
| Install | Composer require; `config/attendance-hub.php` published. |
| Used for | Biometric devices, punch sync, HR attendance (HR module). |
| Adapter | `App\Support\Adapters\AttendanceHubAdapter` |

## 6. `imrandevbd/drutogoti`

| Item | Detail |
|------|--------|
| Install | Composer require; migrations published under `database/migrations/*drutogoti*`. |
| Used for | Request/query performance capture in non-production diagnostics. |
| Adapter | `App\Support\Adapters\DrutogotiAdapter` (no facade; uses `CaptureContext` + models) |

## 7. `imrandevbd/laravel-admin-panel`

| Item | Detail |
|------|--------|
| Install | Composer require; auto-discovered service provider. |
| Used for | Dashboard panels, themes, and render hooks beside Filament. |
| Adapter | `App\Support\Adapters\AdminPanelAdapter` |

## Other stack packages (context)

- **filament/filament** ^4 — admin UI
- **spatie/laravel-permission** — roles on `User`
- **laravel/sanctum** — API tokens on `User`
- **spatie/laravel-activitylog** — audit trail (modules pending)
