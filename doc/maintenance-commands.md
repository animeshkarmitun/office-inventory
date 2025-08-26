## Maintenance and Admin Commands

### Purge all data and keep only Super Admin
Destructive: removes all data except a single superadmin account.

```bash
php artisan admin:purge-to-super
```

Run non-interactively (CI/remote):
```bash
php artisan admin:purge-to-super --force
```

Override credentials (otherwise reads from env: SUPER_ADMIN_EMAIL, SUPER_ADMIN_PASSWORD, SUPER_ADMIN_NAME):
```bash
php artisan admin:purge-to-super \
  --email=admin@yourcompany.com \
  --password="StrongPass123!" \
  --name="Super Admin"
```

Notes:
- Truncates all tables except `users` and `migrations`.
- Deletes all users except role `super_admin`.
- Ensures exactly one superadmin exists with the provided or env credentials.

### Create Super Admin
Interactive:
```bash
php artisan admin:create-super
```

With options:
```bash
php artisan admin:create-super \
  --email=admin@yourcompany.com \
  --password="StrongPass123!" \
  --name="Your Name"
```

### Reset Super Admin
Interactive prompts (or use options):
```bash
php artisan admin:reset-super
```

With options:
```bash
php artisan admin:reset-super \
  --email=admin@yourcompany.com \
  --password="NewStrongPass456!" \
  --name="Super Admin"
```

### Safety
- These commands affect production data. Use `--force` to skip confirmations only when necessary.
- Store credentials securely and rotate regularly.


