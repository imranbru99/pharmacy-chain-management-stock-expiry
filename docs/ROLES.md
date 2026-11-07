# Roles & demo accounts

All demo users use password `password` (see `DEMO_PASSWORD` in `.env`).

| Email | Role | Panel |
| --- | --- | --- |
| superadmin@demo.test | super_admin | `/super-admin` |
| owner@demo.test | owner | `/staff` |
| regional@demo.test | regional | `/staff` |
| branchmanager@demo.test | branch_manager | `/staff` |
| pharmacist@demo.test | pharmacist | `/staff` |
| cashier@demo.test | cashier | `/staff` |
| storekeeper@demo.test | storekeeper | `/staff` |
| purchase@demo.test | purchase | `/staff` |
| accountant@demo.test | accountant | `/staff` |
| hr@demo.test | hr | `/staff` |
| warehouse@demo.test | warehouse | `/staff` |
| rider@demo.test | rider | `/staff` |
| supplier@demo.test | supplier | `/staff` |
| customer@demo.test | customer | `/staff` |
| doctor@demo.test | doctor | `/staff` |
| auditor@demo.test | auditor | `/staff` |

Permissions are generated from each module manifest (`database/seeders/RolePermissionSeeder.php`).
