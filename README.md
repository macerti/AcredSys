# PHP Authentication CRUD Demo

A ready-to-run PHP application for testing your MySQL authentication schema (`users`, `sessions`, `password_resets`, `user_profiles`, `roles`, `user_roles`).

## Features
- Registration/login/logout
- Forgot/reset password with token simulation
- Session persistence in DB (`sessions` table)
- User CRUD with active/verified flags
- Role listing and assignment
- Profile update (display name, avatar, bio, public ID)
- CSRF protection on every form
- PDO prepared statements + password hashing

## Run
1. Ensure your MySQL database/tables are created as shared.
2. Start PHP server from repository root:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```
3. Open:
   `http://127.0.0.1:8000/index.php?page=login`

## Notes
- Database credentials are in `config/database.php`.
- Password reset token is displayed as a flash message to simulate email delivery.
