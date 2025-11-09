Session cart merge & inline login (Nov 06, 2025)

- Merge on login
    - Implemented an event listener `App\Listeners\MergeSessionCart` that listens for the `Illuminate\Auth\Events\Login` event.
    - When a guest with session cart items signs in, the listener migrates items from `session('cart.items')` into the `cart_items` table for the authenticated user. It merges duplicates by `variant_slug + capacity + months` (increments quantity and adjusts total_price).
    - After merging the listener clears the session cart.

- Inline login modal
    - Added an inline login modal to `resources/views/devices/model.blade.php` which allows signing in without leaving the product page.
    - The login form is submitted via AJAX to `/login`. On success the page reloads with `openModal=1` so the rent confirmation modal re-opens and the merged cart is visible in the badge.

Files added/edited for this change

- app/Listeners/MergeSessionCart.php — listener that migrates session cart on Login.
- app/Providers/EventServiceProvider.php — registers the Login -> MergeSessionCart listener mapping.
- resources/views/devices/model.blade.php — inline login modal markup and JS wiring (open/close, AJAX submit, error handling); guest rent flow updated to open inline modal.

How to test

1. As guest: add an item to cart (Confirm in product modal). It should increment the cart badge and persist into session.
2. Click Sign in (inline modal) and log in using an existing account.
3. On successful sign-in the page reloads and the rent modal will reopen (because `openModal=1` is appended). The badge should now reflect the merged DB cart count.

If you prefer the inline login to instead post to an API and return JSON (rather than following standard Laravel login), I can adapt the endpoint to return JSON responses and handle them more precisely in the client.

## Recent fixes: Max / Pro Max variants (Nov 06, 2025)

- Problem: Some families (XR, XS, 11) were missing a "Max" or "Pro Max" button in the product variant picker. Guests using the session cart couldn't pick those variants because they didn't appear in the UI.

````markdown
Session cart merge & inline login (Nov 06, 2025)

- Merge on login
    - Implemented an event listener `App\Listeners\MergeSessionCart` that listens for the `Illuminate\Auth\Events\Login` event.
    - When a guest with session cart items signs in, the listener migrates items from `session('cart.items')` into the `cart_items` table for the authenticated user. It merges duplicates by `variant_slug + capacity + months` (increments quantity and adjusts total_price).
    - After merging the listener clears the session cart.

- Inline login modal
    - Added an inline login modal to `resources/views/devices/model.blade.php` which allows signing in without leaving the product page.
    - The login form is submitted via AJAX to `/login`. On success the page reloads with `openModal=1` so the rent confirmation modal re-opens and the merged cart is visible in the badge.

Files added/edited for this change

- app/Listeners/MergeSessionCart.php — listener that migrates session cart on Login.
- app/Providers/EventServiceProvider.php — registers the Login -> MergeSessionCart listener mapping.
- resources/views/devices/model.blade.php — inline login modal markup and JS wiring (open/close, AJAX submit, error handling); guest rent flow updated to open inline modal.

How to test

1. As guest: add an item to cart (Confirm in product modal). It should increment the cart badge and persist into session.
2. Click Sign in (inline modal) and log in using an existing account.
3. On successful sign-in the page reloads and the rent modal will reopen (because `openModal=1` is appended). The badge should now reflect the merged DB cart count.

If you prefer the inline login to instead post to an API and return JSON (rather than following standard Laravel login), I can adapt the endpoint to return JSON responses and handle them more precisely in the client.

## Recent fixes: Max / Pro Max variants (Nov 06, 2025)

- Problem: Some families (XR, XS, 11) were missing a "Max" or "Pro Max" button in the product variant picker. Guests using the session cart couldn't pick those variants because they didn't appear in the UI.

- What I changed:
    - `app/Http/Controllers/DevicesController.php`: improved variant detection to recognize plain `Max` (e.g. `XS Max`, `XR Max`) in addition to `Pro Max`, and updated variant ordering so `Max`/`Pro Max` appear with other high-end variants.
    - `resources/views/devices/model.blade.php`: added label mapping for the `'max'` variant_type so the UI shows "Max" appropriately and the JS mappingName includes it.
    - `database/seeders/DeviceSeeder.php`: added seeded rows for `iPhone XS Max`, `iPhone 11 Pro Max`, and `iPhone XR Max` so those variants exist in the database (upserted by slug).

- Why this matters for session flow:
    - Guests who build a session-based cart expect to see and select the exact variant names they remember (e.g., "XS Max"). Ensuring the UI lists these variants keeps session carts accurate and makes merge-on-login deterministic.

- How to verify:
    1. Re-run the seeder to upsert new rows:
        ```bash
        php artisan db:seed --class=DeviceSeeder
        ```
    2. Open a family page in the browser (e.g. `/devices/iphone-xr`, `/devices/iphone-xs`, `/devices/iphone-11`) and confirm a "Max" or "Pro Max" button appears in the model picker.
    3. As guest: pick the Max variant and confirm -> it should add to the session cart and show in the badge; after login the listener will merge it into the DB cart.

If you'd like I can remove the duplicate entry in `DEV_NOTES.md` (I appended the same note there earlier) and keep the session-specific notes together in this file.

## Documentation policy (Nov 06, 2025)

- Manual documentation active: the automatic post-commit hook was removed and the composer `doc` script has been cleared per your request. I will append manual notes to `DEV_NOTES_SESSION.md` or `DEV_NOTES.md` after each change I make so the project's history stays clear and under your control.

## Session summary (Nov 08, 2025)

- Security middleware
    - Added `app/Http/Middleware/SecurityHeaders.php` to set baseline security headers (CSP, X-Frame-Options, Referrer-Policy, Permissions-Policy, X-Content-Type-Options, X-XSS-Protection). CSP is relaxed in non-production to allow Vite dev origins for HMR; HSTS is applied conditionally for secure requests.

- Frontend / Vite manifest
    - Created a temporary `public/build/manifest.json` with minimal entries for `resources/css/app.css` and `resources/js/app.js` to prevent Laravel's Vite helper from throwing during tests and server-side rendering when no production build exists. This is a stopgap; run `npm run build` to generate a real manifest before deploying.

- Devices & UX
    - Updated `app/Models/Device.php` with accessors (`display_title`, `family_param`) and modified device index and family views to behave consistently (iPad family clicks open family+generation pages like iPhone flow). Variants are chosen on model pages rather than the index.

- Authentication & sessions
    - Server-rendered auth forms updated to include `@csrf`, fixing 419 errors during register/login. For local dev, `SESSION_DRIVER` was temporarily set to `file`. Production should use DB/Redis sessions and secure cookies.

- Dashboard & UI
    - Rewrote `resources/views/dashboard.blade.php` to match the supplied design (hero card, explore cards, right-column summary cards).

- Seeders & tests
    - Seeders updated to include Mac and Apple Watch entries and populate `generation` fields where applicable.
    - Ran the test suite after adding a minimal Vite manifest; tests passed (12 passed, 0 failed).

Files changed (high level)

- `app/Http/Middleware/SecurityHeaders.php` — new middleware
- `bootstrap/app.php` — middleware registered at bootstrap time
- `app/Models/Device.php` — accessors added
- `resources/views/devices/index.blade.php` — simplified to use model accessors
- `resources/views/auth/*.blade.php` — register/login forms updated with CSRF
- `resources/views/dashboard.blade.php` — redesigned to match mock
- `database/seeders/DeviceSeeder.php` — Mac/Watch entries added
- `public/build/manifest.json` — temporary manifest added to unblock tests

Notes & next steps

- Replace the temporary `public/build/manifest.json` by running the frontend build (`npm run build` or `vite build`) before deploying.
- Harden CSP for production (remove dev origins), enforce secure session cookies, and enable HSTS in production.
- Implement GDPR flows (consent/exports/deletes) after security baseline is finalized.
````
