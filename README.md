# Slice - Apple Ecosystem Rent

<p align="center">
  <img src="public/images/logo.svg" width="200" alt="Slice Logo">
</p>

## About Slice

**Slice** is a modern web platform where customers can rent Apple devices and purchase Apple services with flexible monthly plans. Stay connected and up-to-date with the latest Apple ecosystem without the commitment of full ownership.

### Features

- 🍎 **Rent Apple Devices** - Access the latest iPhones, iPads, MacBooks, and more
- 💳 **Flexible Monthly Plans** - Pay-as-you-go with customizable rental periods
- 🔄 **Upgrade Anytime** - Switch to newer models when they're released
- 🛡️ **Apple Services** - Bundle with Apple Care, iCloud, Apple Music, and more
- 📦 **Easy Returns** - Hassle-free return process when you're done

## Tech Stack

- **Laravel 11** - PHP framework for backend
- **Vite** - Modern build tool for frontend assets
- **Tailwind CSS** - Utility-first CSS framework
- **Blade Templates** - Laravel's powerful templating engine

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Installation

1. Clone the repository

```bash
git clone https://github.com/aditya0w0/Slice.git
cd Slice
```

2. Install dependencies

```bash
composer install
npm install
```

3. Set up environment

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure database in `.env` file

5. Run migrations

```bash
php artisan migrate
```

6. Build assets and start development server

```bash
npm run dev
php artisan serve
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Recent changes (summary)

The following is a concise summary of important changes applied during the recent development session. These notes are intended to help reviewers and maintainers understand functional and security updates made on the copilot branch.

- Security
    - Added `app/Http/Middleware/SecurityHeaders.php` to set baseline security headers (CSP, X-Frame-Options, Referrer-Policy, Permissions-Policy, X-Content-Type-Options, X-XSS-Protection).
    - HSTS (`Strict-Transport-Security`) is applied conditionally for secure requests; CSP is relaxed during non-production development to allow Vite dev server origins for HMR.

- Frontend / Build
    - Temporary Vite manifest placeholder created at `public/build/manifest.json` with minimal entries for `resources/css/app.css` and `resources/js/app.js` to prevent runtime exceptions in test/CI when a production build hasn't been created.
    - Recommended: run `npm run build` (or `vite build`) to generate a real `manifest.json` and built assets for production/CI.

- Devices & UX
    - `app/Models/Device.php` updated with accessors to produce display-friendly titles and family route params (family + generation grouping).
    - Views (e.g., `resources/views/devices/index.blade.php`) updated to use these accessors; iPad/iPhone device family behavior adjusted to match the requested UX (clicking an iPad family now navigates to a family+generation model page similar to iPhone flow).
    - Variant labels were moved off the index; variants are chosen on model pages.

- Authentication & Sessions
    - Auth views (`resources/views/auth/*.blade.php`) updated to server-rendered forms including `@csrf` to fix 419 Page Expired issues.
    - For local dev, `SESSION_DRIVER` was temporarily switched to `file` to avoid DB session errors; for production prefer database/redis-backed sessions and `SESSION_SECURE_COOKIE=true`.

- Seeders & Data
    - Seed data updated to include Mac and Apple Watch entries and to populate `generation` where applicable.

- Tests
    - Fixed failing feature tests caused by missing Vite manifest by adding minimal manifest entries. Tests were run and passed after this fix.

Notes & next steps

- Remove the placeholder `public/build/manifest.json` and generate a proper build with `npm run build` before deploying to production.
- Harden CSP in production (remove dev origins), ensure secure session cookies, and enable HSTS for production environments.
- Implement GDPR flows (consent banner, data export/delete) as a follow-up after baseline security is finalized.
