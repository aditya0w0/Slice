<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Order #{{ $order->id }}</title>
        @vite("resources/css/app.css")
    </head>
    <body>
        @include("partials.header")
        <main class="mx-auto max-w-4xl px-6 py-12">
            <div class="rounded-lg bg-white p-6 shadow">
                <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
                <p class="text-sm text-gray-500">Status: {{ ucfirst($order->status) }}</p>

                <div class="mt-4 space-y-2">
                    <div>
                        <strong>Variant:</strong>
                        {{ $order->variant_slug }}
                    </div>
                    <div>
                        <strong>Capacity:</strong>
                        {{ $order->capacity ?? "-" }}
                    </div>
                    <div>
                        <strong>Months:</strong>
                        {{ $order->months }}
                    </div>
                    <div>
                        <strong>Monthly:</strong>
                        Rp {{ number_format($order->price_monthly, 0, ",", ".") }}
                    </div>
                    <div>
                        <strong>Total:</strong>
                        Rp {{ number_format($order->total_price, 0, ",", ".") }}
                    </div>
                </div>

                <div class="mt-6">
                    <a href="/devices" class="inline-flex items-center rounded-md border px-4 py-2">Back to devices</a>
                </div>
            </div>
        </main>
    </body>
</html>
