<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Cart</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        @include("partials.header")

        <main class="mx-auto max-w-4xl px-6 py-12">
            <h1 class="mb-6 text-2xl font-bold">Your Cart</h1>

            @if ($items->isEmpty())
                <div class="rounded bg-white p-6">Your cart is empty.</div>
            @else
                <div class="space-y-4">
                    @foreach ($items as $it)
                        <div class="flex items-center justify-between rounded bg-white p-4">
                            <div>
                                <div class="font-semibold">{{ $it->variant_slug }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $it->capacity ?? "-" }} • {{ $it->months }} months
                                </div>
                            </div>
                            <div class="text-right">
                                <div>Rp {{ number_format($it->total_price, 0, ",", ".") }}</div>
                                <div class="text-sm text-gray-500">
                                    ({{ $it->quantity }} × Rp {{ number_format($it->price_monthly, 0, ",", ".") }})
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-end">
                    <a href="{{ route("checkout.index") }}" class="rounded-full bg-blue-600 px-6 py-3 text-white">
                        Proceed to checkout
                    </a>
                </div>
            @endif
        </main>
    </body>
</html>
