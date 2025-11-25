<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>{{ $device->name }} — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <a
                        href="{{ route('admin.devices.index') }}"
                        class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                    >
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Device Management
                    </a>
                    <h1 class="text-4xl font-semibold tracking-tight text-gray-900">{{ $device->name }}</h1>
                    <p class="mt-2 text-gray-500">Device details and management</p>
                </div>

                <div class="flex gap-3">
                    <a
                        href="{{ route('admin.devices.edit', $device) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Device
                    </a>
                </div>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-500/20">
                    <p class="text-sm font-medium text-green-800">{{ session("success") }}</p>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Device Information -->
                    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-6 text-2xl font-semibold text-gray-900">Device Information</h2>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Slug</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->slug }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Family</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->family ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Model</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->model ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Brand</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->brand ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Category</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $device->category ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Monthly Price</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($device->price_monthly ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Daily Price</label>
                                <p class="mt-1 text-lg font-semibold text-gray-900">Rp {{ number_format($device->price_daily ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        @if($device->description)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700">Description</label>
                                <p class="mt-1 text-gray-900">{{ $device->description }}</p>
                            </div>
                        @endif

                        @if($device->specifications)
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700">Specifications</label>
                                <div class="mt-1 text-gray-900 bg-gray-50 p-4 rounded-lg">
                                    <pre class="whitespace-pre-wrap">{{ $device->specifications }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Device Images -->
                    @if($device->images && $device->images->count() > 0)
                        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5">
                            <h2 class="mb-6 text-2xl font-semibold text-gray-900">Device Images</h2>

                            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                @foreach($device->images as $image)
                                    <div class="relative">
                                        <img
                                            src="{{ Storage::url($image->path) }}"
                                            alt="{{ $device->name }}"
                                            class="w-full h-48 object-cover rounded-lg border"
                                        />
                                        @if($image->is_primary)
                                            <span class="absolute top-2 left-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Primary
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Variants -->
                    @if($device->variants && $device->variants->count() > 0)
                        <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5">
                            <h2 class="mb-6 text-2xl font-semibold text-gray-900">Device Variants</h2>

                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="border-b border-gray-100 bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Variant</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Storage</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Price/Month</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($device->variants as $variant)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $variant->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->storage ?? 'N/A' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($variant->price_monthly ?? 0, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $variant->stock ?? 0 }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Quick Stats</h3>

                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Variants</span>
                                <span class="font-semibold text-gray-900">{{ $device->variants ? $device->variants->count() : 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Images</span>
                                <span class="font-semibold text-gray-900">{{ $device->images ? $device->images->count() : 0 }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Created</span>
                                <span class="font-semibold text-gray-900">{{ $device->created_at->format('M j, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Last Updated</span>
                                <span class="font-semibold text-gray-900">{{ $device->updated_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Status</h3>

                        <div class="flex items-center gap-3">
                            @if($device->is_active)
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
                                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <span class="text-green-700 font-medium">Active</span>
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <span class="text-red-700 font-medium">Inactive</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900">Actions</h3>

                        <div class="space-y-3">
                            <form action="{{ route('admin.devices.destroy', $device) }}" method="POST" class="inline-block w-full">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    onclick="return confirm('Are you sure you want to delete this device?')"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3 font-medium text-white shadow-lg shadow-red-500/30 transition hover:bg-red-700"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete Device
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>