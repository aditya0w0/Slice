<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Add New Device — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        @include("partials.header")

        <main class="mx-auto max-w-3xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('admin.devices.index') }}" class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Devices
                </a>
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900">Add New Device</h1>
                <p class="mt-2 text-gray-500">Add a new device to your catalog</p>
            </div>

            <!-- Form -->
            <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-gray-900/5">
                <form method="POST" action="{{ route('admin.devices.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-6">
                        <label for="name" class="mb-2 block text-sm font-semibold text-gray-900">Device Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="e.g., iPhone 15 Pro Max" />
                        @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div class="mb-6">
                        <label for="slug" class="mb-2 block text-sm font-semibold text-gray-900">Slug (URL-friendly) *</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 font-mono text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="e.g., iphone-15-pro-max" />
                        <p class="mt-1 text-xs text-gray-500">Use lowercase letters, numbers, and hyphens only</p>
                        @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Family -->
                    <div class="mb-6">
                        <label for="family" class="mb-2 block text-sm font-semibold text-gray-900">Family</label>
                        <input type="text" name="family" id="family" value="{{ old('family') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="e.g., iPhone" />
                        <p class="mt-1 text-xs text-gray-500">Group devices by family (e.g., iPhone, iPad, Mac)</p>
                        @error('family')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
                    <div class="mb-6">
                        <label for="price" class="mb-2 block text-sm font-semibold text-gray-900">Monthly Price ($) *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                            <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01" min="0" class="w-full rounded-xl border border-gray-200 px-4 py-3 pl-8 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="49.99" />
                        </div>
                        @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image URL -->
                    <div class="mb-6">
                        <label for="image" class="mb-2 block text-sm font-semibold text-gray-900">Image URL</label>
                        <input type="text" name="image" id="image" value="{{ old('image') }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="https://example.com/image.jpg" />
                        @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <label for="description" class="mb-2 block text-sm font-semibold text-gray-900">Description</label>
                        <textarea name="description" id="description" rows="4" class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20" placeholder="Device description...">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-6 py-3 font-medium text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Device
                        </button>
                        <a href="{{ route('admin.devices.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-6 py-3 font-medium text-gray-700 transition hover:bg-gray-50">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>
