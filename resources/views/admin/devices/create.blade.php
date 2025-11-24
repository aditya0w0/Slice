<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>Add New Device — Admin — Slice</title>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Devices
                    </a>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Add New Device</h1>
                    <p class="mt-1 text-gray-500">Create a new device listing for your catalog.</p>
                </div>
            </div>

            <!-- Form -->
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                <form method="POST" action="{{ route("admin.devices.store") }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Device Identity Section -->
                    <div class="px-8 py-12">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900">Device Identity</h2>
                                <p class="mt-2 text-gray-600">Define the core attributes of your device</p>
                            </div>

                            <div class="space-y-8">
                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-center text-sm font-medium text-gray-900 mb-3">Product Category</label>
                                    <div class="flex justify-center">
                                        <select
                                            name="category"
                                            id="category"
                                            class="w-full max-w-xs rounded-xl border-0 bg-gray-50 px-4 py-3 text-center text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                                        >
                                            <option value="" class="text-center">Choose Category</option>
                                            <option value="iPhone" {{ old("category") == "iPhone" ? "selected" : "" }}>📱 iPhone</option>
                                            <option value="iPad" {{ old("category") == "iPad" ? "selected" : "" }}>📱 iPad</option>
                                            <option value="Mac" {{ old("category") == "Mac" ? "selected" : "" }}>💻 Mac</option>
                                            <option value="Apple Watch" {{ old("category") == "Apple Watch" ? "selected" : "" }}>⌚ Apple Watch</option>
                                            <option value="Apple TV" {{ old("category") == "Apple TV" ? "selected" : "" }}>📺 Apple TV</option>
                                            <option value="Accessories" {{ old("category") == "Accessories" ? "selected" : "" }}>🎧 Accessories</option>
                                        </select>
                                    </div>
                                    @error("category")
                                        <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-center text-sm font-medium text-gray-900 mb-3">Device Name</label>
                                    <div class="flex justify-center">
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            value="{{ old("name") }}"
                                            required
                                            class="w-full max-w-md rounded-xl border-0 bg-gray-50 px-4 py-3 text-center text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-center placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                                            placeholder="e.g. iPhone 15 Pro Max"
                                            oninput="generateSlug(this.value); updateVariantPreview()"
                                        />
                                    </div>
                                    <p class="mt-2 text-center text-xs text-gray-500">
                                        Include variant keywords like "Pro", "Pro Max", "Max", or "Mini"
                                    </p>
                                    @error("name")
                                        <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug (Hidden) -->
                                <input type="hidden" name="slug" id="slug" value="{{ old("slug") }}" />
                            </div>
                        </div>
                    </div>

                    <!-- Classification Section -->
                    <div class="px-8 py-12 bg-gray-50">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900">Classification</h2>
                                <p class="mt-2 text-gray-600">Organize your device within the catalog</p>
                            </div>

                            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                                <!-- Family -->
                                <div>
                                    <label for="family" class="block text-center text-sm font-medium text-gray-900 mb-3">Product Family</label>
                                    <div class="flex justify-center">
                                        <input
                                            type="text"
                                            name="family"
                                            id="family"
                                            list="families"
                                            value="{{ old("family") }}"
                                            class="w-full max-w-xs rounded-xl border-0 bg-white px-4 py-3 text-center text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-center focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                                            placeholder="e.g. iPhone 15"
                                        />
                                        <datalist id="families">
                                            @foreach($families as $family)
                                                <option value="{{ $family }}"></option>
                                            @endforeach
                                        </datalist>
                                    </div>
                                    <p class="mt-2 text-center text-xs text-gray-500">
                                        Leave empty for auto-detection
                                    </p>
                                    @error("family")
                                        <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Price -->
                                <div>
                                    <label for="price_monthly" class="block text-center text-sm font-medium text-gray-900 mb-3">Monthly Price</label>
                                    <div class="flex justify-center">
                                        <div class="relative w-full max-w-xs">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center justify-center pl-4">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input
                                                type="number"
                                                name="price_monthly"
                                                id="price_monthly"
                                                value="{{ old("price_monthly") }}"
                                                required
                                                step="0.01"
                                                min="0"
                                                class="w-full rounded-xl border-0 bg-white py-3 pl-8 pr-4 text-center text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-center focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                                                placeholder="29.99"
                                            />
                                        </div>
                                    </div>
                                    @error("price_monthly")
                                        <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Variant Preview -->
                            <div class="mt-8">
                                <label class="block text-center text-sm font-medium text-gray-900 mb-4">Live Preview</label>
                                <div class="flex justify-center">
                                    <div class="rounded-xl bg-white p-6 ring-1 ring-gray-200">
                                        <div class="flex items-center gap-6">
                                            <div class="text-center">
                                                <div class="text-sm text-gray-600 mb-1">Variant</div>
                                                <span id="variant-preview" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                    base
                                                </span>
                                            </div>
                                            <div class="text-center">
                                                <div class="text-sm text-gray-600 mb-1">Family</div>
                                                <div id="family-preview" class="text-sm font-medium text-gray-900 min-w-[120px]">
                                                    Auto-detected
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="px-8 py-12">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900">Device Description</h2>
                                <p class="mt-2 text-gray-600">Provide detailed information about features and specifications</p>
                            </div>

                            <div>
                                <label for="description" class="block text-center text-sm font-medium text-gray-900 mb-3">Detailed Description</label>
                                <div class="flex justify-center">
                                    <textarea
                                        id="description"
                                        name="description"
                                        rows="8"
                                        class="w-full max-w-2xl rounded-xl border-0 bg-gray-50 px-4 py-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm"
                                        placeholder="Describe the device's features, specifications, and any special considerations..."
                                    >{{ old("description") }}</textarea>
                                </div>
                                @error("description")
                                    <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Media Section -->
                    <div class="px-8 py-12 bg-gray-50">
                        <div class="mx-auto max-w-2xl">
                            <div class="text-center mb-8">
                                <h2 class="text-2xl font-bold text-gray-900">Device Image</h2>
                                <p class="mt-2 text-gray-600">Upload a high-quality image to showcase your device</p>
                            </div>

                            <div>
                                <label class="block text-center text-sm font-medium text-gray-900 mb-4">Cover Image</label>
                                <div class="flex justify-center">
                                    <div class="w-full max-w-md">
                                        <div class="flex justify-center rounded-xl border-2 border-dashed border-gray-300 px-6 py-10 bg-white hover:border-gray-400 transition-colors" id="drop-zone">
                                            <div class="text-center">
                                                <!-- Preview Image -->
                                                <img id="image-preview" class="mx-auto mb-4 hidden max-h-48 rounded-lg object-contain" alt="Preview" />

                                                <!-- Upload Icon -->
                                                <div id="upload-icon">
                                                    <svg class="mx-auto h-12 w-12 text-gray-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </div>

                                                <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                                                    <label
                                                        for="image"
                                                        class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500"
                                                    >
                                                        <span>Upload a file</span>
                                                        <input id="image" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(this)" />
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs leading-5 text-gray-600 mt-1">PNG, JPG, GIF up to 2MB</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error("image")
                                    <p class="mt-2 text-center text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions Section -->
                    <div class="px-8 py-8 bg-white">
                        <div class="mx-auto max-w-2xl">
                            <div class="flex items-center justify-center gap-x-6">
                                <a href="{{ route("admin.devices.index") }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">Cancel</a>
                                <button
                                    type="submit"
                                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    Save Device
                                </button>
                            </div>
                        </div>
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
                switch(variant) {
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
                            'pro': ' Pro',
                            'max': ' Max',
                            'mini': ' Mini'
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
            document.addEventListener('DOMContentLoaded', function() {
                updateVariantPreview();
            });

            function previewImage(input) {
                const preview = document.getElementById('image-preview');
                const uploadIcon = document.getElementById('upload-icon');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        uploadIcon.classList.add('hidden');
                    }

                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '';
                    preview.classList.add('hidden');
                    uploadIcon.classList.remove('hidden');
                }
            }

            // Drag and drop support
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('image');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
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
