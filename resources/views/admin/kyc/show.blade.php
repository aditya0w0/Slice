<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KYC Review - {{ $kyc->user->name }} - Slice Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite("resources/css/app.css")
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-panel { background: #121217; border: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .glass-input:focus { border-color: #3b82f6; outline: none; }
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #0B0C10; }
        ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #475569; }
    </style>
</head>
<body class="h-full bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-100">

    <!-- Ambient Background -->
    <div class="fixed top-0 left-0 w-full h-96 bg-gradient-to-b from-blue-900/10 to-transparent pointer-events-none z-0"></div>

    <main class="relative z-10 mx-auto max-w-7xl px-6 py-12">

        <!-- Header -->
        <div class="mb-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <a href="{{ route('admin.kyc.index') }}" class="group inline-flex items-center text-sm font-medium text-slate-500 hover:text-white transition-colors mb-3">
                    <svg class="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Requests
                </a>
                <h1 class="text-3xl font-bold text-white tracking-tight flex items-center gap-3">
                    KYC Review
                    <span class="text-slate-600 text-2xl font-light">/</span>
                    <span class="text-blue-500">{{ $kyc->user->name }}</span>
                </h1>
                <p class="mt-2 text-slate-400">Submission ID: <span class="font-mono text-slate-500">#{{ $kyc->id }}</span></p>
            </div>

            <!-- Status Badge (Top Right) -->
            <div>
                @if($kyc->status === 'approved')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-sm font-bold">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span> Verified
                    </span>
                @elseif($kyc->status === 'rejected')
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-bold">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Rejected
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-sm font-bold">
                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span> Pending Review
                    </span>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="mb-8 rounded-xl bg-green-500/10 border border-green-500/20 p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid gap-8 lg:grid-cols-3">

            <!-- Left Column: User Info & Documents -->
            <div class="lg:col-span-2 space-y-8">

                <!-- User Information Card -->
                <div class="glass-panel rounded-2xl p-8">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Applicant Details
                    </h2>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Full Name</label>
                            <p class="text-base font-medium text-white">{{ $kyc->user->name }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Email Address</label>
                            <p class="text-base font-medium text-white">{{ $kyc->user->email }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Phone Number</label>
                            <p class="text-base font-medium text-white">{{ $kyc->user->phone ?? '—' }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Physical Address</label>
                            <p class="text-base font-medium text-white">{{ $kyc->user->address ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Document Proofs -->
                <div class="glass-panel rounded-2xl p-8">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Identity Documents
                    </h2>

                    <div class="space-y-8">
                        <!-- ID Front -->
                        @if($kyc->document_front_image)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Government ID (Front)</label>
                                    <a href="{{ Storage::url($kyc->document_front_image) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                        View Original <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-black/50 overflow-hidden">
                                    <img src="{{ Storage::url($kyc->document_front_image) }}" alt="ID Front" class="w-full h-auto object-contain max-h-[400px]" />
                                </div>
                            </div>
                        @endif

                        <!-- ID Back -->
                        @if($kyc->document_back_image)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Government ID (Back)</label>
                                    <a href="{{ Storage::url($kyc->document_back_image) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                        View Original <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-black/50 overflow-hidden">
                                    <img src="{{ Storage::url($kyc->document_back_image) }}" alt="ID Back" class="w-full h-auto object-contain max-h-[400px]" />
                                </div>
                            </div>
                        @endif

                        <!-- Selfie -->
                        @if($kyc->selfie_image)
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Face Verification (Selfie)</label>
                                    <a href="{{ Storage::url($kyc->selfie_image) }}" target="_blank" class="text-xs text-blue-400 hover:text-blue-300 flex items-center gap-1">
                                        View Original <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </div>
                                <div class="rounded-xl border border-white/10 bg-black/50 overflow-hidden">
                                    <img src="{{ Storage::url($kyc->selfie_image) }}" alt="Selfie" class="w-full h-auto object-contain max-h-[400px]" />
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Metadata & Actions -->
            <div class="space-y-6" x-data="{ rejectModal: false }">

                <!-- Metadata Card -->
                <div class="glass-panel rounded-2xl p-6">
                    <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider border-b border-white/5 pb-2">Submission Metadata</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Type</span>
                            <span class="text-white font-medium">{{ ucfirst($kyc->document_type ?? 'ID Card') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Submitted</span>
                            <span class="text-white font-medium">{{ $kyc->created_at->format('M j, Y H:i') }}</span>
                        </div>
                        @if($kyc->reviewed_at)
                        <div class="flex justify-between">
                            <span class="text-slate-500">Reviewed</span>
                            <span class="text-white font-medium">{{ $kyc->reviewed_at->format('M j, Y H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    @if($kyc->rejection_reason)
                    <div class="mt-6 pt-4 border-t border-white/5">
                        <label class="text-xs font-bold text-red-400 uppercase tracking-wider mb-2 block">Rejection Reason</label>
                        <p class="text-sm text-red-300 bg-red-500/10 p-3 rounded-lg border border-red-500/20 leading-relaxed">
                            {{ $kyc->rejection_reason }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Action Card -->
                @if($kyc->status === 'pending')
                <div class="glass-panel rounded-2xl p-6 sticky top-6">
                    <h3 class="text-sm font-bold text-white mb-4 uppercase tracking-wider">Decision</h3>

                    <div class="space-y-3">
                        <form action="{{ route('admin.kyc.approve', $kyc) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full group relative flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/20 hover:shadow-green-500/30 transition-all hover:scale-[1.02]">
                                <div class="absolute inset-0 rounded-xl bg-white/20 group-hover:opacity-0 transition-opacity"></div>
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Approve Verification
                            </button>
                        </form>

                        <button @click="rejectModal = true" type="button" class="w-full flex items-center justify-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-400 hover:bg-red-500/20 hover:border-red-500/50 transition-all">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject Application
                        </button>
                    </div>
                </div>
                @endif

                <!-- Reject Modal -->
                <div
                    x-show="rejectModal"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    style="display: none;"
                >
                    <!-- Backdrop -->
                    <div x-show="rejectModal" x-transition.opacity class="fixed inset-0 bg-black/80 backdrop-blur-sm"></div>

                    <div class="flex min-h-screen items-center justify-center p-4">
                        <div
                            x-show="rejectModal"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                            @click.away="rejectModal = false"
                            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-[#1A1B21] border border-white/10 p-6 text-left shadow-2xl transition-all"
                        >
                            <div class="mb-5 flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-500/10 border border-red-500/20">
                                    <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-white">Reject Verification</h3>
                                    <p class="text-sm text-slate-400">This action cannot be undone.</p>
                                </div>
                            </div>

                            <form action="{{ route('admin.kyc.reject', $kyc) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Reason for Rejection <span class="text-red-400">*</span></label>
                                    <textarea
                                        name="rejection_reason"
                                        rows="3"
                                        required
                                        class="glass-input w-full rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 focus:ring-1 focus:ring-red-500 focus:border-red-500 transition-all"
                                        placeholder="e.g. Document is blurry, Name does not match..."
                                    ></textarea>
                                </div>

                                <div class="flex gap-3 justify-end">
                                    <button
                                        type="button"
                                        @click="rejectModal = false"
                                        class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 transition-colors"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-500 shadow-lg shadow-red-900/20 transition-all"
                                    >
                                        Confirm Rejection
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>
