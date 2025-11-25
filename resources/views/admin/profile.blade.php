<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Admin Profile — Slice</title>
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#f5f5f7] antialiased">
    <!-- Admin Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-gray-900">Slice<span class="text-blue-600">.</span></span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">← Back to Dashboard</a>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-6 py-12">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="text-sm text-red-800 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Picture Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Picture</h2>
            
            <form action="{{ route('admin.profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="flex items-center gap-8">
                    <!-- Current Photo -->
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                                 alt="Profile" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-gray-200">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Upload Section -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Upload New Photo
                        </label>
                        <input type="file" 
                               name="profile_photo" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none hover:bg-gray-100"
                               required>
                        <p class="mt-2 text-xs text-gray-500">JPG, PNG or GIF (MAX. 2MB)</p>
                        
                        <div class="mt-4 flex gap-3">
                            <button type="submit" 
                                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                Upload Photo
                            </button>
                            
                            @if(auth()->user()->profile_photo)
                                <button type="button"
                                        onclick="document.getElementById('deleteForm').submit()"
                                        class="px-6 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors font-medium">
                                    Remove Photo
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <!-- Delete Form -->
            @if(auth()->user()->profile_photo)
                <form id="deleteForm" action="{{ route('admin.profile.delete-photo') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>

        <!-- Profile Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Profile Information</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Name</label>
                    <p class="mt-1 text-lg text-gray-900">{{ auth()->user()->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Email</label>
                    <p class="mt-1 text-lg text-gray-900">{{ auth()->user()->email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600">Role</label>
                    <span class="inline-flex mt-1 items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        Admin
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
