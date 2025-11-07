<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout</title>
    @vite('resources/css/app.css')
</head>
<body>
<header class="mx-auto max-w-7xl px-6 py-6">
    <div class="flex items-center justify-between">
        <div class="text-2xl font-extrabold text-gray-900">SLICE</div>
        <nav class="flex gap-4">
            <a href="/" class="text-sm text-gray-600 hover:text-gray-900">Home</a>
            <a href="/devices" class="text-sm font-semibold text-gray-900">Devices</a>
        </nav>
    </div>
</header>

<main class="mx-auto max-w-4xl px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if($items->isEmpty())
        <div class="bg-white p-6 rounded">Your cart is empty.</div>
    @else
        <div class="bg-white p-6 rounded">
            <div class="space-y-4">
                @foreach($items as $it)
                    <div class="flex justify-between">
                        <div>
                            <div class="font-semibold">{{ $it->variant_slug }}</div>
                            <div class="text-sm text-gray-500">{{ $it->capacity ?? '-' }} • {{ $it->months }} months</div>
                        </div>
                        <div class="text-right">Rp {{ number_format($it->total_price,0,',','.') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div class="text-lg font-semibold">Subtotal</div>
                <div class="text-lg font-semibold">Rp {{ number_format($subtotal,0,',','.') }}</div>
            </div>

            <form id="checkout-form" method="POST" action="{{ route('checkout.complete') }}" class="mt-6">
                @csrf
                @auth
                    <button type="submit" class="rounded-full bg-blue-600 px-6 py-3 text-white">Place order</button>
                @endauth
                @guest
                    <button type="button" id="proceed-btn" class="rounded-full bg-blue-600 px-6 py-3 text-white">Place order</button>
                @endguest
            </form>
            
            {{-- Inline login modal for guests (same UX as on product pages) --}}
            <div id="inline-login-modal" class="fixed inset-0 z-60 hidden items-center justify-center p-6">
                <div class="absolute inset-0 bg-black/50"></div>
                <div class="relative z-10 w-full max-w-md rounded-lg bg-white p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Sign in</h3>
                        <button id="inline-login-close" class="text-gray-500">✕</button>
                    </div>
                    <form id="inline-login-form" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input name="email" type="email" required class="mt-1 block w-full rounded-md border px-3 py-2" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input name="password" type="password" required class="mt-1 block w-full rounded-md border px-3 py-2" />
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <button type="button" id="inline-login-cancel" class="rounded-md border px-4 py-2">Cancel</button>
                            <button type="submit" id="inline-login-submit" class="rounded-md bg-blue-600 px-4 py-2 text-white">Sign in</button>
                        </div>
                        <div id="inline-login-error" class="text-sm text-red-600 mt-2 hidden"></div>
                    </form>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
                    const proceedBtn = document.getElementById('proceed-btn');
                    const inlineLoginModal = document.getElementById('inline-login-modal');
                    const inlineLoginForm = document.getElementById('inline-login-form');
                    const inlineLoginClose = document.getElementById('inline-login-close');
                    const inlineLoginCancel = document.getElementById('inline-login-cancel');
                    const inlineLoginSubmit = document.getElementById('inline-login-submit');
                    const inlineLoginError = document.getElementById('inline-login-error');
                    const checkoutForm = document.getElementById('checkout-form');

                    function openInlineLogin() {
                        if (!inlineLoginModal) return;
                        inlineLoginModal.classList.remove('hidden');
                        inlineLoginModal.classList.add('flex');
                    }
                    function closeInlineLogin() {
                        if (!inlineLoginModal) return;
                        inlineLoginModal.classList.add('hidden');
                        inlineLoginModal.classList.remove('flex');
                        if (inlineLoginError) { inlineLoginError.textContent = ''; inlineLoginError.classList.add('hidden'); }
                    }

                    if (proceedBtn) {
                        proceedBtn.addEventListener('click', function (e) {
                            e.preventDefault();
                            if (isAuthenticated) {
                                // should not happen because server renders submit for auth, but guard anyway
                                if (checkoutForm) checkoutForm.submit();
                                return;
                            }
                            openInlineLogin();
                        });
                    }

                    if (inlineLoginClose) inlineLoginClose.addEventListener('click', closeInlineLogin);
                    if (inlineLoginCancel) inlineLoginCancel.addEventListener('click', closeInlineLogin);

                    if (inlineLoginForm) {
                        inlineLoginForm.addEventListener('submit', async function (ev) {
                            ev.preventDefault();
                            if (!inlineLoginSubmit) return;
                            inlineLoginSubmit.disabled = true;
                            inlineLoginSubmit.textContent = 'Signing in...';

                            const formData = new FormData(inlineLoginForm);
                            const body = new URLSearchParams();
                            for (const pair of formData.entries()) body.append(pair[0], pair[1]);

                            try {
                                const res = await fetch('/login', {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value,
                                        'Accept': 'application/json, text/plain, */*'
                                    },
                                    body,
                                    credentials: 'same-origin'
                                });

                                if (res.ok) {
                                    // reload page and auto-submit if possible
                                    const redirectUrl = window.location.pathname + (window.location.search ? window.location.search + '&openProceed=1' : '?openProceed=1');
                                    window.location.href = redirectUrl;
                                } else if (res.status === 422) {
                                    const data = await res.json();
                                    const msg = (data && data.message) ? data.message : 'Invalid credentials';
                                    if (inlineLoginError) { inlineLoginError.textContent = msg; inlineLoginError.classList.remove('hidden'); }
                                } else {
                                    if (inlineLoginError) { inlineLoginError.textContent = 'Login failed'; inlineLoginError.classList.remove('hidden'); }
                                }
                            } catch (err) {
                                console.error(err);
                                if (inlineLoginError) { inlineLoginError.textContent = 'Network error'; inlineLoginError.classList.remove('hidden'); }
                            } finally {
                                inlineLoginSubmit.disabled = false;
                                inlineLoginSubmit.textContent = 'Sign in';
                            }
                        });
                    }

                    // If login redirect appended openProceed=1 and user is now authenticated, submit the checkout form automatically.
                    try {
                        const params = new URLSearchParams(window.location.search);
                        if (params.has('openProceed')) {
                            params.delete('openProceed');
                            const base = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                            window.history.replaceState({}, document.title, base);
                            if (isAuthenticated && checkoutForm) {
                                checkoutForm.submit();
                            }
                        }
                    } catch (e) {
                        // ignore
                    }
                });
            </script>
        </div>
    @endif
</main>
</body>
</html>