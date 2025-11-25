<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>KYC Status - Slice</title>
        @vite("resources/css/app.css")
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="bg-gray-50">
        <main class="container mx-auto max-w-4xl px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route('dashboard') }}"
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
                <h1 class="text-3xl font-bold text-gray-900">Verification Status</h1>
                <p class="mt-2 text-gray-600">Check your identity verification progress</p>
            </div>

            @if(!$kyc)
                <!-- No KYC Submitted -->
                <div class="rounded-2xl bg-white p-12 text-center shadow-lg">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-xl font-semibold text-gray-900">No Verification Submitted</h3>
                    <p class="mt-2 text-gray-600">You haven't submitted any identity verification yet</p>
                    <a
                        href="{{ route('kyc.index') }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                    >
                        Start Verification
                    </a>
                </div>
            @else
                <!-- KYC Status Card -->
                <div class="rounded-2xl bg-white p-8 shadow-lg">
                    <div class="flex items-center gap-4 mb-6">
                        @if($kyc->status === 'approved')
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Verification Approved</h2>
                                <p class="text-gray-600">Your identity has been successfully verified</p>
                            </div>
                        @elseif($kyc->status === 'rejected')
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Verification Rejected</h2>
                                <p class="text-gray-600">Your verification was not approved</p>
                            </div>
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Verification Pending</h2>
                                <p class="text-gray-600">We're reviewing your documents (1-2 business days)</p>
                            </div>
                        @endif
                    </div>

                    <!-- Status Details -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900">Submission Details</h3>

                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Document Type</span>
                                    <span class="font-medium">{{ ucfirst($kyc->document_type ?? 'ID Card') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Submitted</span>
                                    <span class="font-medium">{{ $kyc->created_at->format('M j, Y') }}</span>
                                </div>
                                @if($kyc->reviewed_at)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Reviewed</span>
                                        <span class="font-medium">{{ $kyc->reviewed_at->format('M j, Y') }}</span>
                                    </div>
                                @endif
                                @if($kyc->reviewer)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Reviewed by</span>
                                        <span class="font-medium">{{ $kyc->reviewer->name ?? 'Admin' }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900">Uploaded Documents</h3>

                            <div class="space-y-3">
                                @if($kyc->id_front_path)
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">ID Front</span>
                                        <span class="ml-auto text-xs text-green-600">Uploaded</span>
                                    </div>
                                @endif

                                @if($kyc->id_back_path)
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">ID Back</span>
                                        <span class="ml-auto text-xs text-green-600">Uploaded</span>
                                    </div>
                                @endif

                                @if($kyc->selfie_path)
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm text-gray-600">Selfie</span>
                                        <span class="ml-auto text-xs text-green-600">Uploaded</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($kyc->status === 'rejected' && $kyc->rejection_reason)
                        <div class="mt-6 rounded-lg bg-red-50 p-4">
                            <h4 class="font-semibold text-red-900 mb-2">Rejection Reason</h4>
                            <p class="text-red-700">{{ $kyc->rejection_reason }}</p>
                        </div>
                    @endif

                    @if($kyc->status === 'rejected')
                        <div class="mt-6">
                            <a
                                href="{{ route('kyc.index') }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-3 font-semibold text-white shadow-lg transition-all hover:shadow-xl"
                            >
                                Resubmit Verification
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </main>
    </body>
</html>