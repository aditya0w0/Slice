<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>KYC Review - {{ $kyc->user->name }} - Slice Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
            rel="stylesheet"
        />

        @vite("resources/css/app.css")
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .glass-panel {
                background: #121217;
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
            .glass-input {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
            }
            .glass-input:focus {
                border-color: #3b82f6;
                outline: none;
            }
            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
                height: 8px;
            }
            ::-webkit-scrollbar-track {
                background: #0b0c10;
            }
            ::-webkit-scrollbar-thumb {
                background: #334155;
                border-radius: 4px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: #475569;
            }
        </style>
    </head>
    <body class="h-full bg-[#0B0C10] text-slate-300 antialiased selection:bg-blue-500/30 selection:text-blue-100">
        <!-- Ambient Background -->
        <div
            class="pointer-events-none fixed top-0 left-0 z-0 h-96 w-full bg-gradient-to-b from-blue-900/10 to-transparent"
        ></div>

        <main class="relative z-10 mx-auto max-w-7xl px-6 py-12">
            <!-- Header -->
            <div class="mb-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <a
                        href="{{ route("admin.kyc.index") }}"
                        class="group mb-3 inline-flex items-center text-sm font-medium text-slate-500 transition-colors hover:text-white"
                    >
                        <svg
                            class="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Back to Requests
                    </a>
                    <h1 class="flex items-center gap-3 text-3xl font-bold tracking-tight text-white">
                        KYC Review
                        <span class="text-2xl font-light text-slate-600">/</span>
                        <span class="text-blue-500">{{ $kyc->user->name }}</span>
                    </h1>
                    <p class="mt-2 text-slate-400">
                        Submission ID:
                        <span class="font-mono text-slate-500">#{{ $kyc->id }}</span>
                    </p>
                </div>

                <!-- Status Badge (Top Right) -->
                <div>
                    @if ($kyc->status === "approved")
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-green-500/20 bg-green-500/10 px-4 py-2 text-sm font-bold text-green-400"
                        >
                            <span class="h-2 w-2 rounded-full bg-green-500"></span>
                            Verified
                        </span>
                    @elseif ($kyc->status === "rejected")
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-bold text-red-400"
                        >
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            Rejected
                        </span>
                    @else
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-yellow-500/20 bg-yellow-500/10 px-4 py-2 text-sm font-bold text-yellow-400"
                        >
                            <span class="h-2 w-2 animate-pulse rounded-full bg-yellow-500"></span>
                            Pending Review
                        </span>
                    @endif
                </div>
            </div>

            @if (session("success"))
                <div class="mb-8 flex items-center gap-3 rounded-xl border border-green-500/20 bg-green-500/10 p-4">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                    <p class="text-sm font-medium text-green-400">{{ session("success") }}</p>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                <!-- Left Column: User Info & Documents -->
                <div class="space-y-8 lg:col-span-2">
                    <!-- User Information Card -->
                    <div class="glass-panel rounded-2xl p-8">
                        <h2 class="mb-6 flex items-center gap-2 text-lg font-bold text-white">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                />
                            </svg>
                            Applicant Details
                        </h2>

                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-1">
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Full Name
                                </label>
                                <p class="text-base font-medium text-white">{{ $kyc->user->name }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Email Address
                                </label>
                                <p class="text-base font-medium text-white">{{ $kyc->user->email }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Phone Number
                                </label>
                                <p class="text-base font-medium text-white">{{ $kyc->user->phone ?? "—" }}</p>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Physical Address
                                </label>
                                <p class="text-base font-medium text-white">{{ $kyc->user->address ?? "—" }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Document Proofs -->
                    <div class="glass-panel rounded-2xl p-8">
                        <h2 class="mb-6 flex items-center gap-2 text-lg font-bold text-white">
                            <svg class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Identity Documents
                        </h2>

                        <div class="space-y-8">
                            <!-- ID Front -->
                            @if ($kyc->document_front_image)
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                            Government ID (Front)
                                        </label>
                                        <a
                                            href="{{ Storage::url($kyc->document_front_image) }}"
                                            target="_blank"
                                            class="flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300"
                                        >
                                            View Original
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="overflow-hidden rounded-xl border border-white/10 bg-black/50">
                                        <img
                                            src="{{ Storage::url($kyc->document_front_image) }}"
                                            alt="ID Front"
                                            class="h-auto max-h-[400px] w-full object-contain"
                                        />
                                    </div>
                                </div>
                            @endif

                            <!-- ID Back -->
                            @if ($kyc->document_back_image)
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                            Government ID (Back)
                                        </label>
                                        <a
                                            href="{{ Storage::url($kyc->document_back_image) }}"
                                            target="_blank"
                                            class="flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300"
                                        >
                                            View Original
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="overflow-hidden rounded-xl border border-white/10 bg-black/50">
                                        <img
                                            src="{{ Storage::url($kyc->document_back_image) }}"
                                            alt="ID Back"
                                            class="h-auto max-h-[400px] w-full object-contain"
                                        />
                                    </div>
                                </div>
                            @endif

                            <!-- Selfie -->
                            @if ($kyc->selfie_image)
                                <div>
                                    <div class="mb-3 flex items-center justify-between">
                                        <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">
                                            Face Verification (Selfie)
                                        </label>
                                        <a
                                            href="{{ Storage::url($kyc->selfie_image) }}"
                                            target="_blank"
                                            class="flex items-center gap-1 text-xs text-blue-400 hover:text-blue-300"
                                        >
                                            View Original
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                                />
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="overflow-hidden rounded-xl border border-white/10 bg-black/50">
                                        <img
                                            src="{{ Storage::url($kyc->selfie_image) }}"
                                            alt="Selfie"
                                            class="h-auto max-h-[400px] w-full object-contain"
                                        />
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
                        <h3
                            class="mb-4 border-b border-white/5 pb-2 text-sm font-bold tracking-wider text-white uppercase"
                        >
                            Submission Metadata
                        </h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Type</span>
                                <span class="font-medium text-white">
                                    {{ ucfirst($kyc->document_type ?? "ID Card") }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Submitted</span>
                                <span class="font-medium text-white">
                                    {{ $kyc->created_at->format("M j, Y H:i") }}
                                </span>
                            </div>
                            @if ($kyc->reviewed_at)
                                <div class="flex justify-between">
                                    <span class="text-slate-500">Reviewed</span>
                                    <span class="font-medium text-white">
                                        {{ $kyc->reviewed_at->format("M j, Y H:i") }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        @if ($kyc->rejection_reason)
                            <div class="mt-6 border-t border-white/5 pt-4">
                                <label class="mb-2 block text-xs font-bold tracking-wider text-red-400 uppercase">
                                    Rejection Reason
                                </label>
                                <p
                                    class="rounded-lg border border-red-500/20 bg-red-500/10 p-3 text-sm leading-relaxed text-red-300"
                                >
                                    {{ $kyc->rejection_reason }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Action Card -->
                    @if ($kyc->status === "pending")
                        <div class="glass-panel sticky top-6 rounded-2xl p-6">
                            <h3 class="mb-4 text-sm font-bold tracking-wider text-white uppercase">Decision</h3>

                            <div class="space-y-3">
                                <form action="{{ route("admin.kyc.approve", $kyc) }}" method="POST">
                                    @csrf
                                    @method("PATCH")
                                    <button
                                        type="submit"
                                        class="group relative flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-green-600 to-green-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-green-900/20 transition-all hover:scale-[1.02] hover:shadow-green-500/30"
                                    >
                                        <div
                                            class="absolute inset-0 rounded-xl bg-white/20 transition-opacity group-hover:opacity-0"
                                        ></div>
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                        Approve Verification
                                    </button>
                                </form>

                                <button
                                    @click="rejectModal = true"
                                    type="button"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm font-bold text-red-400 transition-all hover:border-red-500/50 hover:bg-red-500/20"
                                >
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                    Reject Application
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Reject Modal -->
                    <div x-show="rejectModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none">
                        <!-- Backdrop -->
                        <div
                            x-show="rejectModal"
                            x-transition.opacity
                            class="fixed inset-0 bg-black/80 backdrop-blur-sm"
                        ></div>

                        <div class="flex min-h-screen items-center justify-center p-4">
                            <div
                                x-show="rejectModal"
                                x-transition:enter="transition duration-300 ease-out"
                                x-transition:enter-start="translate-y-4 scale-95 opacity-0"
                                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                                x-transition:leave="transition duration-200 ease-in"
                                x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                                x-transition:leave-end="translate-y-4 scale-95 opacity-0"
                                @click.away="rejectModal = false"
                                class="relative w-full max-w-md transform overflow-hidden rounded-2xl border border-white/10 bg-[#1A1B21] p-6 text-left shadow-2xl transition-all"
                            >
                                <div class="mb-5 flex items-center gap-4">
                                    <div
                                        class="flex h-12 w-12 items-center justify-center rounded-full border border-red-500/20 bg-red-500/10"
                                    >
                                        <svg
                                            class="h-6 w-6 text-red-500"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-white">Reject Verification</h3>
                                        <p class="text-sm text-slate-400">This action cannot be undone.</p>
                                    </div>
                                </div>

                                <form action="{{ route("admin.kyc.reject", $kyc) }}" method="POST">
                                    @csrf
                                    @method("PATCH")

                                    <div class="mb-6">
                                        <label
                                            class="mb-2 block text-xs font-bold tracking-wider text-slate-500 uppercase"
                                        >
                                            Reason for Rejection
                                            <span class="text-red-400">*</span>
                                        </label>
                                        <textarea
                                            name="rejection_reason"
                                            rows="3"
                                            required
                                            class="glass-input w-full rounded-xl px-4 py-3 text-sm text-white placeholder-slate-600 transition-all focus:border-red-500 focus:ring-1 focus:ring-red-500"
                                            placeholder="e.g. Document is blurry, Name does not match..."
                                        ></textarea>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button
                                            type="button"
                                            @click="rejectModal = false"
                                            class="rounded-xl px-4 py-2.5 text-sm font-medium text-slate-400 transition-colors hover:bg-white/5 hover:text-white"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            type="submit"
                                            class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-red-900/20 transition-all hover:bg-red-500"
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
