# Changelog

All notable changes to this project are documented here.

## [Unreleased] - 2025-11-10

### Refactoring (Code Quality)

- **Deleted dead/duplicate files:**
    - `app/Http/Controllers/navBar.php` - unused controller reading logo on every request
    - `resources/views/auth/signup.blade.php` - 95% duplicate of register.blade.php
- **DevicesController improvements:**
    - Created `getCartCount()` helper in base Controller class
    - Extracted `resolveFamily($slug)` method (eliminates ~50 lines of duplicate slug resolution logic)
    - Extracted `buildVariantsFromDevices($devices)` method (eliminates ~60 lines of duplicate variant parsing)
    - Replaced 4 duplicate cart count blocks across `index()`, `family()`, and `model()` actions
    - **Total lines reduced: ~140 lines**
- **View cleanup:**
    - `dashboard.blade.php` and `admin/dashboard.blade.php` now use `@include('partials.header')` instead of inline HTML
    - `devices/index.blade.php` - removed dead JS, fixed hardcoded images, removed fake pagination
- **Route simplification:**
    - Home route (`/`) now returns view directly instead of calling navBar controller

### How to verify

```bash
php artisan route:clear
php artisan view:clear
php artisan serve
# Visit http://127.0.0.1:8000/devices and test family pages
```

---

## [2025-11-08] - Security & Dashboard

### Added

- **Security middleware** (`app/Http/Middleware/SecurityHeaders.php`):
    - CSP, X-Frame-Options, HSTS (conditional), Referrer-Policy, Permissions-Policy
    - Dev mode: allows Vite HMR origins (localhost:5173)
    - Production: strict CSP (see SECURITY.md for hardening)
- **Vite manifest placeholder** (`public/build/manifest.json`):
    - Temporary minimal manifest to prevent test failures
    - **Action required**: Run `npm run build` before production deployment

### Changed

- **Device model** - added `display_title` and `family_param` accessors
- **Dashboard UI** - redesigned to match modern layout
- **Auth forms** - added `@csrf` tokens to fix 419 errors
- **Seeders** - added Mac and Apple Watch entries with generation data

### Configuration

- Local dev: `SESSION_DRIVER=file` (temporary)
- Production recommendation: use `database` or `redis` with `SESSION_SECURE_COOKIE=true`

### Testing

- All tests passing (12 passed, 0 failed)

---

## [2025-11-06] - Session Cart & Variants

### Added

- **Session-based cart for guests:**
    - `MergeSessionCart` listener migrates guest cart items to DB on login
    - Registered in `EventServiceProvider` (listens to `Login` event)
    - Merges duplicates by `variant_slug + capacity + months`

- **Inline login modal** (`resources/views/devices/model.blade.php`):
    - AJAX login without leaving product page
    - After login, page reloads with `openModal=1` to reopen rent modal
    - Badge reflects merged cart count

### Fixed

- **Max/Pro Max variant detection:**
    - Controller now recognizes plain "Max" variants (e.g., iPhone XS Max, iPhone XR Max)
    - Seeder updated with missing Max/Pro Max models
    - Variant ordering fixed: `['base', 'mini', 'pro', 'max', 'pro max']`

### How to test

```bash
# Re-seed devices
php artisan db:seed --class=DeviceSeeder

# As guest: add items to cart, then login
# Badge should update with merged cart count
```

---

## [2025-11-05] - Dynamic Devices & Routing

### Added

- **Database-driven device listing:**
    - Migration: `2025_11_05_000001_create_devices_table.php`
    - Model: `Device` with price formatting accessor
    - Seeder: idempotent `DeviceSeeder` (uses upsert)

- **Family/variant pages:**
    - `/devices` - index showing all device families
    - `/devices/{slug}` - family page with variant picker (Base/Pro/Max), capacity selector, months selector
    - `/devices/item/{slug}` - individual device detail page
- **Variant selection UI:**
    - Horizontal pill buttons for variants
    - Capacity buttons (256GB/512GB/1TB) with dynamic pricing
    - Duration selector (1-24 months)
    - Real-time price calculation

### Changed

- **Seeding is now idempotent:**
    - User seeder guards against duplicate emails
    - Device seeder uses `upsert()` by slug
    - Safe to run `php artisan db:seed` multiple times

### Files created/modified

- Controllers: `DevicesController` (index, family, model, show actions)
- Routes: `/devices`, `/devices/{slug}`, `/devices/item/{slug}`
- Views: `devices/index.blade.php`, `devices/model.blade.php`, `devices/show.blade.php`
- Seeders: `DeviceSeeder`, `DatabaseSeeder` (idempotent)

### How to verify

```bash
php artisan migrate:fresh --seed
php artisan serve
# Visit http://127.0.0.1:8000/devices
# Click a family -> should see variant picker
```

---

## Notes

- **Documentation policy:** Manual documentation only (no automated hooks)
- **Production checklist:** See `SECURITY.md` for hardening steps
- **Next steps:** GDPR flows (consent banner, data export/delete)
