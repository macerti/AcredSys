# AcredSys PHP App

A ready-to-run PHP application for testing a MySQL authentication + organization-scoped role schema (`users`, `sessions`, `password_resets`, `user_profiles`, `system_roles`, `user_organization_roles`).

## Features
- Single front controller (`public/index.php`) with page router
- Registration/login/logout with required organization context
- Organization-scoped role assignment and role-based module visibility
- Forgot/reset password with token simulation
- Session persistence in DB (`sessions` table)
- User CRUD with `email_verified_at` support
- Module list pages with `organization_id` scoping and pagination-ready limit/offset
- CSRF protection on every form
- PDO prepared statements + password hashing

## Run
1. Ensure your MySQL database/tables are created.
2. Start PHP server from repository root:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```
3. Open:
   `http://127.0.0.1:8000/index.php?page=login`

## Notes
- Database credentials are in `config/database.php`.
- If a user belongs to multiple organizations, login requires explicit org selection.
