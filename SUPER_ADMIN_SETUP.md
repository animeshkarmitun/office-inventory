# Super Admin Setup Guide

## Security Best Practices

The super admin user should be created securely using one of the following methods:

## Method 1: Interactive Command (Recommended)

Run the interactive command to create a super admin:

```bash
php artisan admin:create-super
```

This will prompt you for:
- Email address
- Password (hidden input)
- Name

## Method 2: Command with Options

Create super admin with predefined credentials:

```bash
php artisan admin:create-super --email=admin@yourcompany.com --password=SecurePassword123 --name="Your Name"
```

## Method 3: Environment Variables

Add these to your `.env` file:

```env
SUPER_ADMIN_NAME="Your Name"
SUPER_ADMIN_EMAIL=admin@yourcompany.com
SUPER_ADMIN_PASSWORD=SecurePassword123
```

Then run:
```bash
php artisan db:seed --class=SuperAdminSeeder
```

## Important Security Notes

1. **Never commit credentials to version control**
2. **Use strong passwords** (minimum 8 characters)
3. **Store credentials securely** (password manager, secure notes)
4. **Change default passwords** immediately after setup
5. **Limit super admin access** to trusted personnel only

## Default Credentials (Development Only)

If no credentials are provided, the system will use these defaults:
- Email: `superadmin@office-inventory.com`
- Password: `superadmin123`
- Name: `Super Admin`

**⚠️ WARNING: Change these immediately in production!**

## Super Admin Capabilities

- Create, edit, and delete all users
- Assign roles (admin, asset_manager, employee)
- Full system access
- Cannot be deleted by other users

## Role Hierarchy

1. **Super Admin** - Full system control
2. **Admin** - System administration
3. **Asset Manager** - Asset management
4. **Employee** - Basic access 