Polished behavior (Nov 05, 2025)

This short note documents the small, low-risk UX and behavioral changes applied to the product page and cart flow.

What changed

- Modal / login flow
  - Guests can interact with variant, capacity and months, but clicking "Tambah keranjang" opens a modal prompting sign-in.
  - The Sign in button preserves a return URL and uses `openModal=1` so after signing in the product page re-opens the modal and preserves the user's selections.

- Price & capacity
  - Capacity buttons now affect the displayed monthly price via a small client-side multiplier (256GB = base, 512GB ≈ +10%, 1TB ≈ +20%). This is a temporary client-side rule; for production we should store capacity price deltas in the DB or add capacity variants.

- Add-to-cart & badge
  - Confirm button is disabled while the AJAX request is in-flight and shows a temporary "Processing..." label.
  - On success the cart badge updates, animates briefly, and the count is stored in localStorage as a fallback.
  - Failures show a short toast error and fall back to server form submit.

- Auto-open after login
  - If a returning login redirects back to the product page with `openModal=1` in the URL, the modal will automatically open and show the auth flow.

Notes & next steps

- These are client-side focused, low-risk improvements. Server-side durability (capacity price deltas, payments, persistent cart merging across sessions) should be implemented as next steps.
- I also updated `resources/views/devices/model.blade.php` to include these behaviors.

How to verify

- As guest: open a product page, change options, click "Tambah keranjang" -> modal appears with sign-in CTA that will return you and re-open the modal after sign-in.
- As authenticated user: open product page, pick capacity/variant/duration -> Confirm -> see badge increment and a small toast.

If you want these notes merged into `DEV_NOTES.md` instead of a separate file, I can integrate them there (I attempted but the editor tool hit an issue; leaving this as a separate file for now).