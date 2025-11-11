# Security guidance — Slice

This document summarizes the security-related changes introduced during recent development and provides recommendations for production hardening, verification steps, and a short GDPR checklist to follow as next steps.

Status

- Current branch: `copilot/vscode1762559761384` (development).
- The app includes a new security middleware at `app/Http/Middleware/SecurityHeaders.php` that applies baseline headers.
- A temporary Vite manifest (`public/build/manifest.json`) has been added to unblock server-side rendering during tests/dev; this is a stopgap and should be replaced by a real frontend build before production.

Goals

- Provide a clear separation between development and production security posture.
- Ensure helpful defaults in development (allowing Vite HMR) while tightening policies in production.
- List actionable items for deploy-time hardening and GDPR readiness.

Middleware

- File: `app/Http/Middleware/SecurityHeaders.php`
- Purpose: set baseline HTTP headers for all web responses, including:
    - `Content-Security-Policy` (CSP)
    - `Strict-Transport-Security` (HSTS) — applied conditionally when the request is secure
    - `X-Frame-Options: DENY`
    - `X-Content-Type-Options: nosniff`
    - `Referrer-Policy: no-referrer-when-downgrade` (or stronger as appropriate)
    - `Permissions-Policy` (formerly Feature-Policy)
    - `X-XSS-Protection` (legacy header; prefer CSP)

CSP: development vs production

- Development (non-production environments):
    - CSP is intentionally relaxed to allow Vite dev server origins used for HMR (for example: `http://localhost:5173`, `http://127.0.0.1:5173`, `http://[::1]:5173`) and the associated WebSocket endpoints.
    - Rationale: prevents the Vite client from being blocked while developing UI and CSS.
- Production (APP_ENV=production and served over HTTPS):
    - Remove development origins from CSP.
    - Adopt a strict policy that only allows assets from self and trusted CDNs, for example:
        - `default-src 'self'; script-src 'self' 'sha256-...'` (use proper nonces or hashes for inline scripts or remove inline scripts)
        - `style-src 'self' 'sha256-...'` or `style-src 'self' 'unsafe-inline'` only if unavoidable
        - `connect-src 'self' wss://your-app.example.com` (include analytics/tracking endpoints explicitly)
    - Consider using nonces generated per-request for inline scripts or moving inline scripts to separate JS files.

HSTS (Strict-Transport-Security)

- Ensure `Strict-Transport-Security` header is set in production for secure requests:
    - Example: `Strict-Transport-Security: max-age=63072000; includeSubDomains; preload`
- Only enable `preload` after verifying all subdomains are HTTPS-ready and you want to submit the domain to browsers' preload lists.

Session & Cookies

- Development: `SESSION_DRIVER=file` was used temporarily to avoid DB session problems during local development and tests.
- Production recommendations:
    - Use `SESSION_DRIVER=database` or `SESSION_DRIVER=redis` for reliability and scalability.
    - Set `SESSION_SECURE_COOKIE=true` in production so cookies are only sent over HTTPS.
    - Ensure `SESSION_DOMAIN` and `SESSION_COOKIE` are set to appropriate domain values.
    - Set `SESSION_HTTP_ONLY=true` (default) to guard against client-side access.

Temporary Vite manifest note

- A minimal `public/build/manifest.json` was added to prevent Laravel's Vite helper from throwing when a production build was not present (useful for running tests and server-side rendering during dev).
- This is temporary: before deploying to staging/production run a proper frontend build to generate the real manifest and built assets.
    - Recommended command:

```bash
# generate production assets (run in your dev/CI environment)
npm run build
# or directly
vite build
```

- After running a real build, commit the generated `public/build/manifest.json` and built files only if your deployment process expects committed assets; otherwise configure your CI to run `npm run build` and publish assets as part of the release pipeline.

Logging, error reporting & monitoring

- Ensure errors and security events are reported to your monitoring system (Sentry, Bugsnag, etc.).
- Avoid leaking sensitive data in error pages. In production set `APP_DEBUG=false` and ensure stack traces are not shown to end users.

GDPR & Data Subject Requests (brief)

- This is a short checklist to prepare the app for GDPR compliance; these are non-exhaustive and should be expanded with legal guidance.
    - Consent collection: add a visible consent banner for tracking cookies and optional analytics; store consent decisions with a timestamp.
    - Data export: implement an authenticated endpoint that produces a machine-readable export (JSON/CSV) of a user's personal data.
    - Data deletion: provide an authenticated flow (or admin-assisted) to remove or anonymize a user's personal data on request. Keep an internal audit trail of the deletion request.
    - Record retention policy: define how long transactional and analytic data is kept and include it in your privacy policy.

Deployment checklist (security-focused)

- Before deploying to production:
    - [ ] Remove dev Vite origins from CSP.
    - [ ] Ensure `APP_ENV=production` and `APP_DEBUG=false`.
    - [ ] Ensure TLS/HTTPS is correctly configured on all public endpoints.
    - [ ] Set `SESSION_SECURE_COOKIE=true` and choose a secure session driver.
    - [ ] Enable HSTS via `Strict-Transport-Security` header (apply carefully).
    - [ ] Confirm `public/build/manifest.json` is the real build manifest produced by `npm run build`.
    - [ ] Run security-sensitive tests (render pages, check policies, cookies flags, CSP header content).
    - [ ] Ensure any third-party scripts/services are explicitly allowed in CSP and have appropriate consent flows.

Verification & quick checks (developer)

- Inspect HTTP headers for a request to the app's home page and confirm expected headers are present:
    - `Content-Security-Policy`
    - `Strict-Transport-Security` (only on secure requests)
    - `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Permissions-Policy`
- In dev, when using Vite, ensure the CSP allows Vite origins so the Vite client and HMR socket can connect.

Reporting security issues

- For now, open an issue in the repo with the `security` label or contact the maintainers directly for sensitive disclosures.
- Avoid posting detailed vulnerability information publicly until a fix is available.

Further improvements (next-phase)

- Automate security checks in CI (CSP scanner, dependency vulnerability scanning).
- Add integration tests that assert the presence and shape of critical security headers.
- Implement GDPR flows (consent UI, export & deletion endpoints) and document them in `SECURITY.md` or a separate `GDPR.md`.

Contacts

- For questions about this guidance or to report a security issue, open an issue in the repository or contact the repository owner.

---

This file is intentionally concise. If you want a longer, formal `SECURITY.md` with PGP contact and triage process text and CVE handling, I can expand it and add the standard disclosure sections.
