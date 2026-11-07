# Modules
## AI Assistant (`ai`)
- Version: 1.0.0
- Tenant-aware AI helpers with masked prompts.
- Dependencies: catalog
- Permissions: `ai.view, ai.use, ai.manage`

## SaaS Billing (`billing`)
- Version: 1.0.0
- Subscription plans, usage limits, and invoices.
- Permissions: `billing.view, billing.manage, billing.subscribe`

## Medicine and Product Catalog (`catalog`)
- Version: 1.0.0
- Medicines, generics, brands, barcodes, and pricing.
- Permissions: `catalog.view, catalog.create, catalog.update, catalog.delete, catalog.import`

## CMS & Pages (`cms`)
- Version: 1.0.0
- Landing pages, FAQs, and pharmacy microsite.
- Permissions: `cms.view, cms.create, cms.update, cms.publish`

## Compliance & Licensing (`compliance`)
- Version: 1.0.0
- Drug license tracking and regulatory reports.
- Permissions: `compliance.view, compliance.manage, compliance.report`

## Core — Tenant, Branch & Settings (`core`)
- Version: 1.0.0
- Foundation tenancy, branches, and global settings.
- Permissions: `core.view, core.manage`

## Customers & Patients (`customers`)
- Version: 1.0.0
- Customer profiles, allergies, and purchase history.
- Permissions: `customers.view, customers.create, customers.update, customers.delete`

## Delivery & Dispatch (`delivery`)
- Version: 1.0.0
- Riders, zones, and delivery tracking.
- Dependencies: online_orders
- Permissions: `delivery.view, delivery.dispatch, delivery.track`

## Expiry Tracking (`expiry`)
- Version: 1.0.0
- Near-expiry alerts, FEFO, and destruction logs.
- Dependencies: stock, catalog
- Permissions: `expiry.view, expiry.manage, expiry.dispose`

## Finance & Accounting (`finance`)
- Version: 1.0.0
- Ledgers, expenses, and payment reconciliation.
- Permissions: `finance.view, finance.manage, finance.export`

## Demand Forecasting (`forecasting`)
- Version: 1.0.0
- Reorder suggestions based on sales and stock trends.
- Dependencies: stock, sales
- Permissions: `forecasting.view, forecasting.manage`

## HR & Payroll (`hr`)
- Version: 1.0.0
- Employees, shifts, leave, and payroll hooks.
- Permissions: `hr.view, hr.manage, hr.payroll`

## Loyalty & Rewards (`loyalty`)
- Version: 1.0.0
- Points, tiers, and redemption rules.
- Dependencies: customers
- Permissions: `loyalty.view, loyalty.manage, loyalty.redeem`

## Notifications (`notifications`)
- Version: 1.0.0
- SMS, email, and in-app notification templates.
- Permissions: `notifications.view, notifications.send, notifications.manage`

## Online Orders (`online_orders`)
- Version: 1.0.0
- Web and mobile order intake for pickup or delivery.
- Dependencies: catalog, customers
- Permissions: `online_orders.view, online_orders.manage, online_orders.fulfill`

## Point of Sale (`pos`)
- Version: 1.0.0
- Counter sales, holds, and receipts.
- Dependencies: stock, catalog
- Permissions: `pos.view, pos.sell, pos.refund, pos.close_shift`

## Prescriptions (`prescriptions`)
- Version: 1.0.0
- Rx capture, validation, and dispensing links.
- Dependencies: customers
- Permissions: `prescriptions.view, prescriptions.create, prescriptions.dispense`

## Purchasing & GRN (`purchasing`)
- Version: 1.0.0
- Purchase orders, goods receipt, and supplier invoices.
- Dependencies: suppliers, catalog
- Permissions: `purchasing.view, purchasing.create, purchasing.approve, purchasing.receive`

## Reports & Analytics (`reports`)
- Version: 1.0.0
- Operational and financial report builder.
- Permissions: `reports.view, reports.export, reports.schedule`

## Sales & Invoicing (`sales`)
- Version: 1.0.0
- Sales history, returns, and wholesale invoicing.
- Dependencies: catalog
- Permissions: `sales.view, sales.create, sales.refund, sales.export`

## Stock & Inventory (`stock`)
- Version: 1.0.0
- Branch stock levels, batches, and adjustments.
- Permissions: `stock.view, stock.create, stock.update, stock.delete, stock.adjust`

## Stock Counts & Audits (`stock_counts`)
- Version: 1.0.0
- Physical stock counts and variance reconciliation.
- Dependencies: stock
- Permissions: `stock_counts.view, stock_counts.create, stock_counts.approve`

## Suppliers (`suppliers`)
- Version: 1.0.0
- Supplier directory, contacts, and lead times.
- Permissions: `suppliers.view, suppliers.create, suppliers.update, suppliers.delete`

## Inter-Branch Transfers (`transfers`)
- Version: 1.0.0
- Stock transfers between branches and warehouses.
- Dependencies: stock
- Permissions: `transfers.view, transfers.create, transfers.approve, transfers.receive`

## Users & Access Control (`users`)
- Version: 1.0.0
- Staff accounts, roles, and permissions.
- Permissions: `users.view, users.create, users.update, users.delete`

