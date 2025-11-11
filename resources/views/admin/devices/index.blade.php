<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Manage Devices — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        @include("partials.header")

        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <a
                        href="{{ route("admin.dashboard") }}"
                        class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                    >
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Admin Dashboard
                    </a>
                    <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Device Management</h1>
                    <p class="mt-2 text-gray-500">Manage your product catalog</p>
                </div>
                <a
                    href="{{ route("admin.devices.create") }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add New Device
                </a>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-500/20">
                    <p class="text-sm font-medium text-green-800">{{ session("success") }}</p>
                </div>
            @endif

            <!-- Devices Table -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-100 bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Device
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    SKU
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Family
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Slug
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Price
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($devices as $device)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @if ($device->image)
                                                <img
                                                    src="{{ $device->image }}"
                                                    alt="{{ $device->name }}"
                                                    class="h-12 w-12 rounded-lg object-cover"
                                                />
                                            @else
                                                <div
                                                    class="flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100"
                                                >
                                                    <svg
                                                        class="h-6 w-6 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                                        />
                                                    </svg>
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900">{{ $device->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 font-mono text-xs font-medium text-gray-700"
                                        >
                                            {{ $device->sku ?? "—" }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $device->family ?? "-" }}</td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-500">{{ $device->slug }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                        ${{ number_format($device->price, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a
                                                href="{{ route("admin.devices.edit", $device) }}"
                                                class="inline-flex items-center gap-1 rounded-lg bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 transition hover:bg-blue-100"
                                            >
                                                <svg
                                                    class="h-4 w-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                    />
                                                </svg>
                                                Edit
                                            </a>
                                            <form
                                                method="POST"
                                                action="{{ route("admin.devices.destroy", $device) }}"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this device?');"
                                            >
                                                @csrf
                                                @method("DELETE")
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-red-50 px-3 py-1.5 text-sm font-medium text-red-700 transition hover:bg-red-100"
                                                >
                                                    <svg
                                                        class="h-4 w-4"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                        />
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No devices found.
                                        <a
                                            href="{{ route("admin.devices.create") }}"
                                            class="font-medium text-blue-600 hover:text-blue-700"
                                        >
                                            Add your first device
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($devices->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $devices->links() }}
                    </div>
                @endif
            </div>
        </main>
    </body>
</html>
