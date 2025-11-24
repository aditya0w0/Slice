<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Informasi Profil - Slice</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-50">
        <div class="min-h-screen p-4 md:p-8">
            <div class="mx-auto max-w-3xl">
                <!-- Header -->
                <div class="mb-8">
                    <a href="/settings" class="inline-flex items-center text-gray-600 transition hover:text-gray-900">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            ></path>
                        </svg>
                        Pengaturan
                    </a>
                    <h1 class="mt-4 text-3xl font-bold text-gray-900">Informasi Profil</h1>
                </div>

                <!-- Success Message -->
                @if (session("success"))
                    <div class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 p-4">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            ></path>
                        </svg>
                        <p class="font-medium text-green-800">{{ session("success") }}</p>
                    </div>
                @endif

                <!-- Profile Form Card -->
                <div class="mb-6 overflow-hidden rounded-3xl bg-white shadow-sm">
                    <!-- Profile Photo Section -->
                    <div class="border-b border-gray-100 p-6">
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                @if (Auth::user()->profile_photo)
                                    <img
                                        id="profile-photo-preview"
                                        src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                        alt="Profile"
                                        class="h-24 w-24 rounded-full object-cover"
                                    />
                                @else
                                    <img
                                        id="profile-photo-preview"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b82f6&color=fff&size=120"
                                        alt="Profile"
                                        class="h-24 w-24 rounded-full"
                                    />
                                @endif
                                <label
                                    for="profile-photo-input"
                                    class="absolute right-0 bottom-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-blue-500 text-white transition hover:bg-blue-600"
                                >
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"
                                        ></path>
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"
                                        ></path>
                                    </svg>
                                </label>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Foto Profil</h3>
                                <p class="mt-1 text-sm text-gray-500">JPG, PNG maksimal 2MB</p>
                                <p id="selected-file-name" class="mt-1 hidden text-xs text-blue-600"></p>
                            </div>
                        </div>
                    </div>

                    <form
                        method="POST"
                        action="{{ route("settings.profile.update") }}"
                        enctype="multipart/form-data"
                        id="profile-form"
                    >
                        @csrf
                        @method("PUT")

                        <!-- Hidden File Input -->
                        <input
                            type="file"
                            id="profile-photo-input"
                            name="profile_photo"
                            accept="image/jpeg,image/png,image/jpg,image/gif"
                            class="hidden"
                        />

                        <!-- Name Field -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ Auth::user()->name }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Nama lengkap Anda"
                            />
                        </div>

                        <!-- Email Field -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Email</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ Auth::user()->email }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="email@example.com"
                            />
                            <p class="mt-2 text-xs text-gray-500">Email digunakan untuk login dan notifikasi</p>
                        </div>

                        <!-- Phone Field -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon</label>
                            <input
                                type="tel"
                                name="phone"
                                value="{{ Auth::user()->phone ?? "" }}"
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="+62 812 3456 7890"
                            />
                        </div>

                        <!-- Address Field -->
                        <div class="border-b border-gray-100 p-6">
                            <label class="mb-2 block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea
                                name="address"
                                rows="3"
                                class="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                placeholder="Alamat lengkap Anda"
                            >
{{ Auth::user()->address ?? "" }}</textarea
                            >
                        </div>

                        <!-- Save Button -->
                        <div class="p-6">
                            <button
                                type="submit"
                                class="w-full rounded-xl bg-blue-500 py-3 font-semibold text-white transition hover:bg-blue-600 active:scale-[0.98]"
                            >
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            // Profile photo preview and file selection
            const photoInput = document.getElementById('profile-photo-input');
            const photoPreview = document.getElementById('profile-photo-preview');
            const fileName = document.getElementById('selected-file-name');

            photoInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                console.log('File selected:', file);

                if (file) {
                    // Validate file size (2MB max)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 2MB.');
                        photoInput.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak valid. Gunakan JPG, PNG, atau GIF.');
                        photoInput.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        photoPreview.src = event.target.result;
                        console.log('Preview loaded');
                    };
                    reader.readAsDataURL(file);

                    // Show file name
                    fileName.textContent = 'File dipilih: ' + file.name;
                    fileName.classList.remove('hidden');
                    console.log('File ready to upload:', file.name, file.size, file.type);
                }
            });

            // Debug form submission
            document.getElementById('profile-form').addEventListener('submit', function (e) {
                const formData = new FormData(this);
                console.log('Form submitting...');
                console.log('Has profile_photo:', formData.has('profile_photo'));
                console.log('Profile photo value:', formData.get('profile_photo'));
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
            });
        </script>
    </body>
</html>
