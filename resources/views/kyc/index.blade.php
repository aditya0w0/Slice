<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Identity Verification - Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50">
        <main class="container mx-auto max-w-4xl px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route("dashboard") }}"
                    class="mb-4 inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                >
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>
                    Back to Dashboard
                </a>
                <h1 class="text-3xl font-bold text-gray-900">Identity Verification</h1>
                <p class="mt-2 text-gray-600">Complete your verification to unlock full access</p>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-500/20">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-green-800">{{ session("success") }}</p>
                    </div>
                </div>
            @endif

            @if ($kyc && $kyc->status === "pending")
                <!-- Pending Review Status -->
                <div class="rounded-2xl bg-yellow-50 p-6 ring-1 ring-yellow-500/20">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-yellow-900">Verification Pending</h3>
                            <p class="mt-1 text-sm text-yellow-700">
                                Your documents are under review. We'll notify you once verified.
                            </p>
                            <p class="mt-2 text-xs text-yellow-600">
                                Submitted: {{ $kyc->submitted_at->format('F j, Y \a\t g:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif ($kyc && $kyc->status === "approved")
                <!-- Approved Status -->
                <div class="rounded-2xl bg-green-50 p-6 ring-1 ring-green-500/20">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                            <svg class="h-6 w-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-green-900">Verified Account</h3>
                            <p class="mt-1 text-sm text-green-700">Your identity has been successfully verified!</p>
                            <p class="mt-2 text-xs text-green-600">
                                Verified: {{ $kyc->verified_at?->format("F j, Y") }}
                            </p>
                        </div>
                    </div>
                </div>
            @elseif ($kyc && $kyc->status === "rejected")
                <!-- Rejected Status -->
                <div class="mb-6 rounded-2xl bg-red-50 p-6 ring-1 ring-red-500/20">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-red-900">Verification Failed</h3>
                            <p class="mt-1 text-sm text-red-700">
                                {{ $kyc->rejection_reason ?? "Your documents could not be verified. Please resubmit with correct information." }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (! $kyc || $kyc->status === "rejected")
                <!-- KYC Form -->
                <form
                    method="POST"
                    action="{{ route("kyc.store") }}"
                    enctype="multipart/form-data"
                    x-data="kycForm()"
                    class="space-y-6"
                >
                    @csrf

                    <!-- Personal Information -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Personal Information</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Full Name *</label>
                                <input
                                    type="text"
                                    name="full_name"
                                    value="{{ old("full_name", $kyc->full_name ?? "") }}"
                                    required
                                    minlength="3"
                                    maxlength="255"
                                    pattern="[\p{L}\s'\-\.]+"
                                    autocomplete="name"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    placeholder="As per ID document"
                                />
                                @error("full_name")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Date of Birth *</label>
                                    <input
                                        type="date"
                                        name="date_of_birth"
                                        value="{{ old("date_of_birth", $kyc->date_of_birth ?? "") }}"
                                        required
                                        min="{{ now()->subYears(120)->format("Y-m-d") }}"
                                        max="{{ now()->subYears(18)->format("Y-m-d") }}"
                                        autocomplete="bday"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                    @error("date_of_birth")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Phone Number *</label>
                                    <input
                                        type="tel"
                                        name="phone_number"
                                        value="{{ old("phone_number", $kyc->phone_number ?? "") }}"
                                        required
                                        minlength="8"
                                        maxlength="20"
                                        pattern="[\+]?[0-9\s\-\(\)]{8,20}"
                                        autocomplete="tel"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                        placeholder="+62 812 3456 7890"
                                    />
                                    @error("phone_number")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Address</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Full Address *</label>
                                <textarea
                                    name="address"
                                    rows="3"
                                    required
                                    minlength="10"
                                    maxlength="500"
                                    autocomplete="street-address"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    placeholder="Street address, building, apartment"
                                >
{{ old("address", $kyc->address ?? "") }}</textarea
                                >
                                @error("address")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">City *</label>
                                    <input
                                        type="text"
                                        name="city"
                                        value="{{ old("city", $kyc->city ?? "") }}"
                                        required
                                        minlength="2"
                                        maxlength="100"
                                        autocomplete="address-level2"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                    @error("city")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">Postal Code *</label>
                                    <input
                                        type="text"
                                        name="postal_code"
                                        value="{{ old("postal_code", $kyc->postal_code ?? "") }}"
                                        required
                                        minlength="5"
                                        maxlength="10"
                                        pattern="[0-9]{5,10}"
                                        inputmode="numeric"
                                        autocomplete="postal-code"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                        placeholder="12345"
                                    />
                                    @error("postal_code")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ID Document -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Identity Document</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Document Type *</label>
                                <select
                                    name="id_type"
                                    required
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                >
                                    <option value="">Select document type</option>
                                    <option
                                        value="ktp"
                                        {{ old("id_type", $kyc->id_type ?? "") === "ktp" ? "selected" : "" }}
                                    >
                                        KTP (ID Card)
                                    </option>
                                    <option
                                        value="passport"
                                        {{ old("id_type", $kyc->id_type ?? "") === "passport" ? "selected" : "" }}
                                    >
                                        Passport
                                    </option>
                                    <option
                                        value="sim"
                                        {{ old("id_type", $kyc->id_type ?? "") === "sim" ? "selected" : "" }}
                                    >
                                        SIM (Driver License)
                                    </option>
                                </select>
                                @error("id_type")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-700">Document Number *</label>
                                <input
                                    type="text"
                                    name="id_number"
                                    value="{{ old("id_number", $kyc->id_number ?? "") }}"
                                    required
                                    minlength="5"
                                    maxlength="50"
                                    pattern="[A-Z0-9\-]+"
                                    style="text-transform: uppercase"
                                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    placeholder="ABC123456"
                                />
                                @error("id_number")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        ID Front Photo * (Max 5MB)
                                    </label>
                                    <input
                                        type="file"
                                        name="id_front"
                                        accept="image/jpeg,image/jpg,image/png"
                                        required
                                        x-ref="idFront"
                                        @change="previewImage($event, 'idFrontPreview')"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG only</p>
                                    <div x-show="idFrontPreview" class="mt-2 overflow-hidden rounded-xl">
                                        <img :src="idFrontPreview" class="h-40 w-full object-cover" />
                                    </div>
                                    @error("id_front")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-gray-700">
                                        ID Back Photo (Max 5MB)
                                    </label>
                                    <input
                                        type="file"
                                        name="id_back"
                                        accept="image/jpeg,image/jpg,image/png"
                                        x-ref="idBack"
                                        @change="previewImage($event, 'idBackPreview')"
                                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                    <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG only</p>
                                    <div x-show="idBackPreview" class="mt-2 overflow-hidden rounded-xl">
                                        <img :src="idBackPreview" class="h-40 w-full object-cover" />
                                    </div>
                                    @error("id_back")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Selfie -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-gray-900/5">
                        <h2 class="mb-4 text-lg font-semibold text-gray-900">Selfie Verification</h2>
                        <p class="mb-4 text-sm text-gray-600">Take a clear selfie holding your ID document</p>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-gray-700">Selfie Photo * (Max 5MB)</label>
                            <input
                                type="file"
                                name="selfie"
                                accept="image/jpeg,image/jpg,image/png"
                                required
                                x-ref="selfie"
                                @change="previewImage($event, 'selfiePreview')"
                                class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                            />
                            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, or PNG only • Min 300x300px</p>
                            <div x-show="selfiePreview" class="mt-4 flex justify-center">
                                <img :src="selfiePreview" class="max-w-sm rounded-xl shadow-lg" />
                            </div>
                            @error("selfie")
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex gap-4">
                        <button
                            type="submit"
                            class="flex-1 rounded-xl bg-blue-600 px-6 py-4 text-base font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 active:scale-95"
                        >
                            Submit Verification
                        </button>
                    </div>
                </form>
            @endif
        </main>

        <script>
            function kycForm() {
                return {
                    idFrontPreview: null,
                    idBackPreview: null,
                    selfiePreview: null,

                    previewImage(event, previewVar) {
                        const file = event.target.files[0];
                        const input = event.target;

                        if (!file) return;

                        // Validate file type (client-side security)
                        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                        if (!validTypes.includes(file.type)) {
                            alert('Invalid file type. Please upload JPG, JPEG, or PNG images only.');
                            input.value = '';
                            return;
                        }

                        // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                        const maxSize = 5 * 1024 * 1024;
                        if (file.size > maxSize) {
                            alert('File size exceeds 5MB. Please upload a smaller image.');
                            input.value = '';
                            return;
                        }

                        // Create preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            // Validate image dimensions (additional check)
                            const img = new Image();
                            img.onload = () => {
                                if (img.width < 300 || img.height < 300) {
                                    alert('Image dimensions too small. Minimum 300x300 pixels required.');
                                    input.value = '';
                                    this[previewVar] = null;
                                    return;
                                }
                                if (img.width > 8000 || img.height > 8000) {
                                    alert('Image dimensions too large. Maximum 8000x8000 pixels allowed.');
                                    input.value = '';
                                    this[previewVar] = null;
                                    return;
                                }
                                // Valid image
                                this[previewVar] = e.target.result;
                            };
                            img.onerror = () => {
                                alert('Invalid image file. Please upload a valid image.');
                                input.value = '';
                                this[previewVar] = null;
                            };
                            img.src = e.target.result;
                        };
                        reader.onerror = () => {
                            alert('Error reading file. Please try again.');
                            input.value = '';
                        };
                        reader.readAsDataURL(file);
                    },
                };
            }
        </script>
    </body>
</html>
