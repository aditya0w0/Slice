<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Edit Device — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50 font-sans text-gray-900 antialiased">
        <main class="mx-auto max-w-4xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <a
                        href="{{ route("admin.devices.index") }}"
                        class="mb-2 inline-flex items-center text-sm font-medium text-gray-500 transition hover:text-gray-900"
                    >
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Back to Devices
                    </a>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Edit Device</h1>
                    <p class="mt-1 text-gray-500">Update device information and settings.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <form
                    method="POST"
                    action="{{ route("admin.devices.update", $device) }}"
                    enctype="multipart/form-data"
                    class="divide-y divide-gray-100"
                >
                    @csrf
                    @method("PUT")

                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 p-8 md:grid-cols-3">
                        <!-- Basic Info Section -->
                        <div class="md:col-span-1">
                            <h2 class="text-base leading-7 font-semibold text-gray-900">Device Details</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">Basic information about the device.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-6 md:col-span-2 md:grid-cols-2">
                            <!-- Name -->
                            <div class="col-span-2">
                                <label for="name" class="block text-sm leading-6 font-medium text-gray-900">
                                    Device Name
                                </label>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        name="name"
                                        id="name"
                                        value="{{ old("name", $device->name) }}"
                                        required
                                        class="block w-full rounded-lg border-0 py-2.5 text-gray-900 ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm sm:leading-6"
                                        placeholder="e.g. iPhone 15 Pro Max, iPad Pro 12.9-inch"
                                        oninput="generateSlug(this.value); updateVariantPreview()"
                                    />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Include variant keywords like "Pro", "Pro Max", "Max", or "Mini" in the name for
                                    automatic variant detection.
                                </p>
                                @error("name")
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Slug (Hidden) -->
                            <input type="hidden" name="slug" id="slug" value="{{ old("slug", $device->slug) }}" />

                            <!-- Category -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="category" class="block text-sm leading-6 font-medium text-gray-900">
                                    Category
                                </label>
                                <div class="mt-2">
                                    <select
                                        name="category"
                                        id="category"
                                        class="block w-full rounded-lg border-0 py-2.5 text-gray-900 ring-1 ring-gray-300 ring-inset focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm sm:leading-6"
                                    >
                                        <option value="">Select Category</option>
                                        <option
                                            value="iPhone"
                                            {{ old("category", $device->category) == "iPhone" ? "selected" : "" }}
                                        >
                                            iPhone
                                        </option>
                                        <option
                                            value="iPad"
                                            {{ old("category", $device->category) == "iPad" ? "selected" : "" }}
                                        >
                                            iPad
                                        </option>
                                        <option
                                            value="Mac"
                                            {{ old("category", $device->category) == "Mac" ? "selected" : "" }}
                                        >
                                            Mac
                                        </option>
                                        <option
                                            value="Accessories"
                                            {{ old("category", $device->category) == "Accessories" ? "selected" : "" }}
                                        >
                                            Accessories
                                        </option>
                                        <option
                                            value="Apple Watch"
                                            {{ old("category", $device->category) == "Apple Watch" ? "selected" : "" }}
                                        >
                                            Apple Watch
                                        </option>
                                        <option
                                            value="Apple TV"
                                            {{ old("category", $device->category) == "Apple TV" ? "selected" : "" }}
                                        >
                                            Apple TV
                                        </option>
                                    </select>
                                </div>
                                @error("category")
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Family -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="family" class="block text-sm leading-6 font-medium text-gray-900">
                                    Family
                                </label>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        name="family"
                                        id="family"
                                        list="families"
                                        value="{{ old("family", $device->family) }}"
                                        class="block w-full rounded-lg border-0 py-2.5 text-gray-900 ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm sm:leading-6"
                                        placeholder="e.g. iPhone 15, iPad Pro"
                                    />
                                    <datalist id="families">
                                        @foreach ($families as $family)
                                            <option value="{{ $family }}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Base model name (e.g., "iPhone 15" for iPhone 15 Pro Max). Leave empty for
                                    auto-detection.
                                </p>
                                @error("family")
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Variant Preview -->
                            <div class="col-span-2">
                                <label class="block text-sm leading-6 font-medium text-gray-900">Variant Preview</label>
                                <div class="mt-2 rounded-lg bg-gray-50 p-3">
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-600">Detected Variant:</span>
                                            <span
                                                id="variant-preview"
                                                class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800"
                                            >
                                                {{ $device->variant_type ?? "base" }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm text-gray-600">Family:</span>
                                            <span id="family-preview" class="text-sm font-medium text-gray-900">
                                                {{ $device->family ?? "Auto-detected" }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="col-span-2 sm:col-span-1">
                                <label for="price_monthly" class="block text-sm leading-6 font-medium text-gray-900">
                                    Monthly Price
                                </label>
                                <div class="relative mt-2 rounded-md shadow-sm">
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input
                                        type="number"
                                        name="price_monthly"
                                        id="price_monthly"
                                        value="{{ old("price_monthly", $device->price_monthly) }}"
                                        required
                                        step="0.01"
                                        min="0"
                                        class="block w-full rounded-lg border-0 py-2.5 pl-10 text-gray-900 ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm sm:leading-6"
                                        placeholder="0.00"
                                    />
                                </div>
                                @error("price_monthly")
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 p-8 md:grid-cols-3">
                        <div class="md:col-span-1">
                            <h2 class="text-base leading-7 font-semibold text-gray-900">Description</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">
                                Detailed information about the device features and specifications.
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm leading-6 font-medium text-gray-900">
                                Description
                            </label>
                            <div class="mt-2">
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="6"
                                    class="block w-full rounded-lg border-0 py-2.5 text-gray-900 ring-1 ring-gray-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-blue-600 focus:ring-inset sm:text-sm sm:leading-6"
                                    placeholder="Write a detailed description..."
                                >
{{ old("description", $device->description) }}</textarea
                                >
                            </div>
                            @error("description")
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="grid grid-cols-1 gap-x-8 gap-y-8 p-8 md:grid-cols-3">
                        <div class="md:col-span-1">
                            <h2 class="text-base leading-7 font-semibold text-gray-900">Device Image</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-500">
                                Upload a high-quality image of the device.
                            </p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="image" class="block text-sm leading-6 font-medium text-gray-900">
                                Cover Image
                            </label>
                            <div
                                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
                                id="drop-zone"
                            >
                                <div class="text-center">
                                    <!-- Preview Image -->
                                    <img
                                        id="image-preview"
                                        class="{{ $device->image ? "" : "hidden" }} mx-auto mb-4 max-h-48 rounded-lg object-contain"
                                        src="{{ $device->image }}"
                                        alt="Preview"
                                    />

                                    <!-- Upload Icon -->
                                    <div id="upload-icon" class="{{ $device->image ? "hidden" : "" }}">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-300"
                                            viewBox="0 0 24 24"
                                            fill="currentColor"
                                            aria-hidden="true"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </div>

                                    <div class="mt-4 flex justify-center text-sm leading-6 text-gray-600">
                                        <label
                                            for="image"
                                            class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 focus-within:outline-none hover:text-blue-500"
                                        >
                                            <span>Upload a file</span>
                                            <input
                                                id="image"
                                                name="image"
                                                type="file"
                                                class="sr-only"
                                                accept="image/*"
                                                onchange="previewImage(this)"
                                            />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs leading-5 text-gray-600">PNG, JPG, GIF up to 2MB</p>
                                </div>
                            </div>
                            @error("image")
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-x-6 border-t border-gray-100 bg-gray-50 px-8 py-4">
                        <a
                            href="{{ route("admin.devices.index") }}"
                            class="text-sm leading-6 font-semibold text-gray-900 hover:text-gray-700"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                        >
                            Update Device
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <script>
            function generateSlug(value) {
                const slug = value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/(^-|-$)+/g, '');
                document.getElementById('slug').value = slug;
            }

            function updateVariantPreview() {
                const name = document.getElementById('name').value;
                const family = document.getElementById('family').value;

                // Simple variant detection logic (mirroring PHP logic)
                let variant = 'base';
                const lname = name.toLowerCase();

                if (lname.includes('pro max')) {
                    variant = 'pro max';
                } else if (lname.includes(' max') || lname.endsWith(' max')) {
                    variant = 'max';
                } else if (lname.includes('pro')) {
                    variant = 'pro';
                } else if (lname.includes('mini')) {
                    variant = 'mini';
                }

                // Update variant preview
                const variantElement = document.getElementById('variant-preview');
                variantElement.textContent = variant;

                // Update variant badge color
                variantElement.className = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium';
                switch (variant) {
                    case 'pro max':
                        variantElement.classList.add('bg-purple-100', 'text-purple-800');
                        break;
                    case 'pro':
                        variantElement.classList.add('bg-blue-100', 'text-blue-800');
                        break;
                    case 'max':
                        variantElement.classList.add('bg-green-100', 'text-green-800');
                        break;
                    case 'mini':
                        variantElement.classList.add('bg-yellow-100', 'text-yellow-800');
                        break;
                    default:
                        variantElement.classList.add('bg-gray-100', 'text-gray-800');
                }

                // Update family preview
                const familyElement = document.getElementById('family-preview');
                if (family.trim()) {
                    familyElement.textContent = family;
                } else {
                    // Simple family detection
                    let detectedFamily = name;
                    if (variant !== 'base') {
                        const variantMap = {
                            'pro max': ' Pro Max',
                            pro: ' Pro',
                            max: ' Max',
                            mini: ' Mini',
                        };
                        const suffix = variantMap[variant];
                        if (detectedFamily.endsWith(suffix)) {
                            detectedFamily = detectedFamily.slice(0, -suffix.length);
                        }
                    }
                    familyElement.textContent = detectedFamily || 'Auto-detected from name';
                }
            }

            // Initialize variant preview on page load
            document.addEventListener('DOMContentLoaded', function () {
                updateVariantPreview();
            });

            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const uploadIcon = document.getElementById('upload-icon');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        uploadIcon.classList.add('hidden');
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Drag and drop support
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('image');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach((eventName) => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            function highlight(e) {
                dropZone.classList.add('border-blue-500', 'bg-blue-50');
            }

            function unhighlight(e) {
                dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            }

            dropZone.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                fileInput.files = files;
                previewImage(fileInput);
            }
        </script>
    </body>
</html>
