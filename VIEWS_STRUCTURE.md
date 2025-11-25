# Views Directory Structure

This document explains the organized structure of the `resources/views` directory.

## Directory Organization

```
resources/views/
├── admin/              # Admin panel views
│   ├── chat/
│   ├── devices/
│   ├── kyc/
│   ├── layouts/
│   ├── notifications/
│   ├── orders/
│   ├── users/
│   ├── chat-react.blade.php
│   ├── dashboard.blade.php
│   └── profile.blade.php
│
├── auth/               # Authentication views
│   ├── login.blade.php
│   └── register.blade.php
│
├── balance/            # Balance & wallet views
│   ├── index.blade.php
│   ├── payment-instruction.blade.php
│   ├── payment-processing.blade.php
│   ├── payment-success.blade.php
│   ├── referral-earnings.blade.php
│   └── transaction-history.blade.php
│
├── cart/               # Shopping cart views
│   └── index.blade.php
│
├── chat/               # User chat views
│   ├── index.blade.php
│   ├── legacy.blade.php
│   └── react.blade.php
│
├── dashboard/          # User dashboard views
│   └── react.blade.php
│
├── devices/            # Device catalog views
│   ├── family.blade.php
│   ├── find.blade.php
│   ├── index.blade.php
│   ├── model.blade.php
│   └── show.blade.php
│
├── home/               # Home page views
│   └── index.blade.php
│
├── kyc/                # KYC verification views
│   └── index.blade.php
│
├── layouts/            # Layout templates
│   └── app.blade.php
│
├── notifications/      # Notification views
│   ├── index.blade.php
│   └── show.blade.php
│
├── orders/             # Order management views
│   ├── receipt.blade.php
│   ├── show.blade.php
│   └── tracking.blade.php
│
├── pages/              # Static/info pages
│   ├── pricing.blade.php
│   ├── recipe.blade.php
│   ├── support.blade.php
│   └── welcome.blade.php
│
├── partials/           # Reusable components
│   ├── footer.blade.php
│   └── header.blade.php
│
├── payments/           # Payment result views
│   ├── failed.blade.php
│   ├── pending.blade.php
│   └── success.blade.php
│
└── settings/           # User settings views
    ├── index.blade.php
    ├── activity-log.blade.php
    ├── notifications.blade.php
    ├── payment.blade.php
    ├── payment-history.blade.php
    ├── privacy.blade.php
    ├── profile.blade.php
    ├── security.blade.php
    └── subscription.blade.php
```

## File Migrations

The following files were moved to improve organization:

### Root → Folder Migrations

- `balance.blade.php` → `balance/index.blade.php`
- `settings.blade.php` → `settings/index.blade.php`
- `chat.blade.php` → `chat/legacy.blade.php`
- `chat-react.blade.php` → `chat/react.blade.php`
- `dashboard-react.blade.php` → `dashboard/react.blade.php`
- `find-device.blade.php` → `devices/find.blade.php`
- `Home.blade.php` → `home/index.blade.php`
- `delivery-tracking.blade.php` → `orders/tracking.blade.php`

### Payment Files

- `payment-failed.blade.php` → `payments/failed.blade.php`
- `payment-pending.blade.php` → `payments/pending.blade.php`
- `payment-success.blade.php` → `payments/success.blade.php`

### Static Pages

- `pricing.blade.php` → `pages/pricing.blade.php`
- `recipe.blade.php` → `pages/recipe.blade.php`
- `support.blade.php` → `pages/support.blade.php`
- `welcome.blade.php` → `pages/welcome.blade.php`

## Route Updates Required

After this reorganization, you need to update the following in your controllers/routes:

### Example Updates:

```php
// Old
return view('balance');
// New
return view('balance.index');

// Old
return view('chat-react');
// New
return view('chat.react');

// Old
return view('payment-success');
// New
return view('payments.success');

// Old
return view('Home');
// New
return view('home.index');

// Old
return view('delivery-tracking');
// New
return view('orders.tracking');
```

## Benefits

1. **Better Organization**: Related views grouped together
2. **Easier Navigation**: Clear folder structure
3. **Scalability**: Easy to add new views in appropriate folders
4. **Maintainability**: Logical grouping reduces confusion
5. **Clean Root**: No scattered files in root directory

## Deprecated Files

Old/backup files are now in `resources/views/Old/` and ignored by git.
