<p align="center">
  <img src="docs/screenshots/dashboard.svg" alt="Pharmacy Chain Management dashboard preview" width="100%"/>
</p>

<h1 align="center">Pharmacy Chain Management</h1>

<p align="center"><strong>Multi-branch stock, expiry alerts and transfers</strong></p>

<p align="center">
  <a href="https://github.com/imranbru99/pharmacy-chain-management-stock-expiry"><img alt="GitHub" src="https://img.shields.io/badge/GitHub-imranbru99%2Fpharmacy-chain-management-stock-expiry-0F172A?style=for-the-badge&logo=github"/></a>
  <img alt="Laravel" src="https://img.shields.io/badge/Laravel-PHP-FF2D20?style=for-the-badge&logo=laravel&logoColor=white"/>
  <img alt="Author" src="https://img.shields.io/badge/Author-Imran%20Ahmed-14B8A6?style=for-the-badge"/>
</p>

---

## Overview

**Pharmacy Chain Management** is a complete business application focused on real operational workflows. This README lists the full feature surface so GitHub visitors and search engines can understand every capability of the product.

## Product preview

| Dashboard | Operations |
| :---: | :---: |
| ![Dashboard](docs/screenshots/dashboard.svg) | ![Operations](docs/screenshots/operations.svg) |

## Complete feature list

### Application modules

- Ai
- Billing
- Catalog
- Cms
- Compliance
- Core
- Customers
- Delivery
- Expiry
- Finance
- Forecasting
- Hr
- Loyalty
- Notifications
- Online Orders
- Pos
- Prescriptions
- Purchasing
- Reports
- Sales
- Stock
- Stock Counts
- Suppliers
- Transfers
- Users

### Admin / Filament resources

- Account
- Cms Page
- Cms Post
- Cold Chain Log
- Controlled Drug Log
- Customer
- Delivery
- Drug License
- Employee
- Expense
- Goods Receipt
- Journal Entry
- Leave Request
- Online Order
- Payroll Run
- Platform Invoice
- Pos Shift
- Prescription
- Product Recall
- Product
- Purchase Order
- Purchase Requisition
- Sale
- Stock Batch
- Stock Count
- Stock Transfer
- Subscription
- Supplier

### Domain models

- Branch
- Plan
- Tenant
- Tenant Module

### HTTP / API controllers

- Contact APIs & screens
- Customer Portal APIs & screens
- Demo APIs & screens
- Locale APIs & screens
- Product APIs & screens
- Public Page APIs & screens
- Rider Portal APIs & screens
- Sale APIs & screens
- Sitemap APIs & screens
- Stock APIs & screens
- Supplier Portal APIs & screens
- Tenant Registration APIs & screens

### Detailed feature requirements delivered

- AI Code Agent Prompt: Pharmacy Chain Management
- ROLE AND MISSION
- MANDATORY PACKAGES (read each README first, then integrate properly)
- Apply across the app: caching, query and response optimization, asset speed. Enable it globally and benchmark before and after.
- Base for the admin and staff dashboards: layouts, widgets, stats, navigation.
- All AI features (see Module 20). Provider and model must be configurable per tenant, never hardcoded.
- Staff attendance from any biometric or RFID device (see HR module).
- Every PDF: invoices, receipts, labels, reports, prescriptions, purchase orders. Must render Bangla, Arabic, Hindi, Chinese, etc. correctly.
- All slugs: products, categories, branches, public pages, blog posts.
- TECH STACK AND ARCHITECTURE RULES
- Database: MySQL or MariaDB (primary), Redis for cache, queues and sessions.
- Dependencies are enforced (for example, Prescription Management requires Customers; the UI warns before disabling a module others depend on).
- USER TYPES AND ROLES (multi-type users)
- Super Admin (platform owner): manages tenants, plans, modules, billing, platform settings, impersonation, system health.
- Tenant Owner / Chain Admin: full control of their business, all branches, module toggles, billing.
- Regional / Area Manager: controls a group of branches.
- Branch Manager: controls one branch: stock, staff, sales, approvals.
- Pharmacist (licensed): dispensing, prescription verification, controlled-drug register, counseling notes.
- Cashier / Sales Staff: POS, returns, customer lookup.
- Inventory / Store Keeper: receiving, transfers, stock counts, expiry handling.
- Purchase Officer: suppliers, purchase orders, price comparison.
- Accountant / Finance: ledgers, expenses, tax, reports, reconciliation.
- HR Officer: staff, attendance, shifts, payroll.
- Warehouse Manager: central warehouse, inter-branch distribution.
- Delivery Rider / Driver: assigned deliveries, proof of delivery, cash collection.
- Supplier / Distributor (portal): sees their purchase orders, confirms, uploads invoices, sees payments.
- Customer / Patient (portal): orders, prescriptions, refill reminders, loyalty, invoices, family profiles.
- Doctor / Prescriber (optional portal): e-prescriptions, referrals.
- Auditor / Regulator (read-only): controlled-drug and compliance reports.
- MODULES (every one switchable ON/OFF per tenant)
- Stock Count and Audit: cycle counts, full counts, variance approval, blind counts via mobile or barcode scanner.
- Supplier Management: profiles, contacts, payment terms, price lists, performance scoring.
- Prescription Management: upload or e-prescription capture, pharmacist verification, partial fills, refills, controlled-drug register, doctor directory, prescription history.
- Notifications and Communication: email, SMS, WhatsApp-ready, push, in-app; templates per event; scheduled digests (daily expiry and low-stock summary to managers).
- Public Website and CMS: see section 6.
- PUBLIC PAGES (marketing and customer site)
- Home: hero, value proposition, key feature highlights, live demo CTA, pricing teaser, testimonials, trust badges.
- Module Explorer: interactive page where visitors toggle modules ON/OFF and see which menus, screens, and benefits appear (a live demonstration of the modular concept).
- Solutions by business size: single pharmacy, regional chain, hospital pharmacy, wholesale distributor.
- Multi-Branch Stock and Expiry (flagship page): FEFO, expiry buckets, recall handling, transfer suggestions.
- Pricing: plans and add-on modules, monthly/yearly toggle, comparison table, FAQ.
- Demo: instant demo login with role picker (see section 8) and a guided product tour.
- Online Pharmacy Storefront (when Online Orders is on): product search, categories, product pages, cart, prescription upload, checkout, order tracking.
- Compliance and Security: data protection, audit trail, role controls.
- Branch Locator: map of branches, hours, services, "open now" filter.
- Register your pharmacy: self-serve tenant signup with trial.
- DEMO DATA AND DEMO USERS (must work out of the box)
- Create seeders (php artisan migrate:fresh --seed) that produce a realistic demo tenant ("Demo Pharma Chain") with:
- SECURITY, PRIVACY AND QUALITY REQUIREMENTS
- Patient data privacy: role-based visibility, access logging, data export and erasure tools.
- Queues and scheduler for: expiry alerts, low-stock alerts, report emails, backups, demo reset, attendance sync, AI jobs.
- Observability: error logging, health-check endpoint, activity feed.
- CI config (GitHub Actions): Pint, Larastan, Pest.
- BUILD PLAN (follow this order, commit after each phase)
- Core: tenants, branches, users, roles, settings, audit log, language/RTL, currency.
- Compliance, notifications, AI module (ai-hub), catalog enrichment (ai-catalog), reports.
- DELIVERABLES

### Platform capabilities

- Multi-role ready structure for operators, managers and admins
- Database migrations and seeders for demo-ready local setup
- Laravel architecture with services/controllers/models separation
- Responsive UI foundation (Blade / Filament where enabled)
- Environment-based config for local and production databases
- GitHub-ready documentation with visual product previews

## Tech stack

- PHP 8.2+ / Laravel
- Filament admin resources (where enabled)
- Vite asset pipeline
- SQLite/MySQL-ready database layer
- Migrations, seeders, and test-ready structure

## Quick start

```bash
git clone https://github.com/imranbru99/pharmacy-chain-management-stock-expiry.git
cd pharmacy-chain-management-stock-expiry
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

## Repository

- GitHub: https://github.com/imranbru99/pharmacy-chain-management-stock-expiry
- Developer: **Imran Ahmed** · https://imrandev.bd

---

## 🤝 Let's Connect & Collaborate

I am open to **Senior Remote Full-Stack Roles**, AI Platform Architecture Contracts, and Enterprise Technical Advisory.

**Timezone:** UTC+6 (Dhaka / Rangpur, Bangladesh) — flexible overlap with US, UK & EU hours.  
**Delivery:** Async-ready · Slack · Discord · Jira · GitHub · production-first accountability.

| Channel | Handle | Link |
| :--- | :--- | :--- |
| Portfolio | imrandev.bd | https://imrandev.bd |
| Packagist | imrandevbd | https://packagist.org/packages/imrandevbd/ |
| LinkedIn | imranbru99 | https://linkedin.com/in/imranbru99 |
| GitHub | imranbru99 | https://github.com/imranbru99 |
| WhatsApp | +880 1576-918420 | https://wa.me/8801576918420 |
| Email | me@imrandev.bd | mailto:me@imrandev.bd |
| X | @imrandev_bd | https://x.com/imrandev_bd |
| YouTube | @ImranDevBD | https://youtube.com/@ImranDevBD |
