<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Admin Dashboard — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 text-gray-900">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 pb-20">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Admin Dashboard</h2>
                <p class="text-sm text-gray-600">Manage your Slice platform</p>
            </div>

            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Admin controls</h3>
                <p class="mt-4 text-sm text-gray-600">
                    This is a simple admin dashboard placeholder. Add admin tools here (manage devices, orders, users).
                </p>
            </div>
        </main>
    </body>
</html>
