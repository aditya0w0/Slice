Project changes summary — Devices & seeding (Nov 05, 2025)

## Overview

This note records the edits I applied so you can quickly recall what changed and how to verify locally.

## High-level summary

- Made the devices listing dynamic and adjusted routing so the family/model page is a single product page where a user can pick Base/Pro/Pro Max and rental duration.
- Made seeders idempotent so running `php artisan db:seed` multiple times won't raise unique constraint errors.
- Added an Apple-like single product layout for `/devices/{slug}` including variant selection, duration selection, dynamic price calculation, and a placeholder Add-to-Cart button.

## Files added/edited

(important ones only)

- database/migrations/2025_11_05_000001_create_devices_table.php
    - Creates the `devices` table (name, slug unique, category, variant, price_monthly, image, description)

- app/Models/Device.php
    - Eloquent model (fillable fields and price formatting accessor)

- database/seeders/DeviceSeeder.php
    - Seeded many iPhone variants (XR -> 17 Pro Max).
    - Changed INSERT -> upsert(..., ['slug'], [...]) so seeding is idempotent.

- database/seeders/DatabaseSeeder.php
    - Test user creation now guarded with `if (! User::where('email', 'test@example.com')->exists())` to avoid duplicate-email errors.

- app/Http/Controllers/DevicesController.php
    - index(): computes base models (groups by family) and returns `devices.index` with `baseModels`.
    - model($slug): returns a single family page with `variants` (includes numeric `price_monthly`) so the view can compute totals.
    - show($slug): individual device detail (moved to /devices/item/{slug}).
    - Note: `Illuminate\Support\Str` is imported and used for slug handling.

- routes/web.php
    - /devices -> index
    - /devices/family/{family} -> kept (compat redirect)
    - /devices/{slug} -> DevicesController@model (single family page, named `devices.model`)
    - /devices/item/{slug} -> DevicesController@show (individual variant page, named `devices.show`)

- resources/views/devices/index.blade.php
    - Updated to consume `baseModels` and link to `route('devices.model', ['slug' => base.slug])`.

- resources/views/devices/model.blade.php
    - New Apple-style product page (gallery left, options right).
    - Variant select, capacity buttons (UI only), months select, dynamic price calculation (monthly \* months), and Add-to-Cart button (placeholder).

- resources/views/devices/show.blade.php
    - Per-variant detail page (kept available at /devices/item/{slug}).

- resources/views/Home.blade.php
    - Featured mosaic and CTAs updated previously to link to `/devices` (kept, not changed in this session except earlier edits).

- public/images/product-\*.svg
    - Placeholder SVGs used by the views.

## Why these changes

- Seeding: previously re-running `php artisan db:seed` could fail due to unique constraints for existing users or devices. Making seeders idempotent (upsert + guarded user creation) avoids that and makes dev iteration easier.
- UX: you asked for a single page where the user picks variant + duration (like the screenshot). `/devices/{slug}` is now the family page that shows all variants and the duration selector in one place.

## How to verify locally

Run these commands from the project root (bash):

1. Clear caches (helpful after view/route changes)

```bash
php artisan view:clear
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

2. (Optional) Re-run seeders. If you want to keep existing data, use db:seed. The seeders are now idempotent.

```bash
php artisan db:seed
```

If you want a fresh database (drops & re-creates all tables), use:

```bash
php artisan migrate:fresh --seed
```

3. Serve the app and browse:

```bash
php artisan serve
# open http://127.0.0.1:8000/devices
# click a base model card -> e.g. http://127.0.0.1:8000/devices/iphone-17
```

You should see the single product page. Changing variant or months updates the price displayed.

## Notes / Limitations

- Add-to-Cart is a placeholder: it does not persist an order yet. I can implement a `RentalController` and a small `orders` table to persist rentals.
- Capacity buttons are currently UI-only; capacities don't adjust price. To support capacity-based pricing we should add capacity/stock rows or a `variants` structure in DB.
- The controller currently infers family/base names by stripping suffixes (Pro/Pro Max/mini/etc.). For robust grouping, consider adding a `family` or `base` column to the `devices` table and populating it in the seeder.

## Next recommended steps

- Implement rental flow (POST endpoint): validate selected variant + months, create order record, redirect to checkout.
- Add capacity/price deltas per variant and reflect them in the UI and DB.
- (Optional) Migrate family/base into a dedicated column to avoid string parsing at runtime.

If you want any of these next steps, tell me which one and I'll implement it.

-- End of DEV_NOTES.md

## Changelog reference

I now maintain `CHANGELOG.md` at the repo root and detailed session entries under the `changelogs/` directory. For a full chronological list of edits and verification steps see `CHANGELOG.md` and `changelogs/`.

## Documentation policy (Nov 06, 2025)

- Manual documentation: per your preference I removed the automatic post-commit hook and the composer `doc` script. I will manually append a short entry to `DEV_NOTES.md` (and `DEV_NOTES_SESSION.md` when relevant) every time I change code in the repository. Each entry will include:
    - A short description of the change I made
    - Files changed (list)
    - How to verify locally (commands or pages to visit)

- The helper script `scripts/doc-change.php` remains in the repository for convenience if you ever want to generate a draft entry automatically, but it will not run automatically.

If you prefer different wording or a different target file (e.g., `CHANGELOG.md`), tell me and I'll follow that format going forward.

## Recent fixes (Nov 06, 2025)

- Max / Pro Max variants: Fixed missing "Max"/"Pro Max" variant buttons on some families (XR, XS, 11).
    - What I changed:
        - `app/Http/Controllers/DevicesController.php`: improved variant detection to recognize plain `Max` (e.g. `XS Max`, `XR Max`) in addition to `Pro Max`, and updated variant ordering so `Max`/`Pro Max` appear with other high-end variants.
        - `resources/views/devices/model.blade.php`: added label mapping for the `'max'` variant_type so the UI shows "Max" appropriately and the JS mappingName includes it.
        - `database/seeders/DeviceSeeder.php`: added seeded rows for `iPhone XS Max`, `iPhone 11 Pro Max`, and `iPhone XR Max` so those variants exist in the database (upserted by slug).
    - How to verify:
        1. Re-run the seeder to upsert new rows:
            ```bash
            php artisan db:seed --class=DeviceSeeder
            ```
        2. Open a family page in the browser (e.g. `/devices/iphone-xr`, `/devices/iphone-xs`, `/devices/iphone-11`) and confirm a "Max" or "Pro Max" button appears in the model picker.
        3. Click the "Max" button and verify the main image and price update, and that Add-to-Cart / modal behavior still works.

If you'd like, I can merge these short notes into `DEV_NOTES_SESSION.md` or create a changelog entry instead. Let me know which you prefer.

## Auth guard for renting

The product page (`/devices/{slug}`) opens a confirmation modal when the user clicks "Tambah keranjang".

- Guests can still interact with the options (model, capacity, months). Clicking Rent as a guest opens the modal which prompts sign-in (does not block the options UI).
- Authenticated users see the modal summary and can confirm to create an order.

Implemented server-side rental flow:

- POST /rent (named route `rent.start`) — protected by `auth` middleware. The controller validates inputs, looks up the variant by slug, computes the total, and creates an `orders` record.
- `orders` table migration created: `database/migrations/2025_11_05_000002_create_orders_table.php`.
- `App\Models\Order` created to persist orders.
- After creating the order the app redirects to `/orders/{id}` which shows a simple order summary page.

Client-side: the modal confirm button submits a hidden form (with `variant_slug`, `months`, `capacity`) to `POST /rent` (only present for authenticated users).

If you want changes to the flow (e.g., redirect to a full checkout/checkout provider, create a cart instead of immediate order), tell me and I'll implement it.
