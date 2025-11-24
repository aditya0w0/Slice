<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1" />
        <title>KYC Verification — Admin — Slice</title>
        @vite("resources/css/app.css")
    </head>
    <body class="bg-gray-50">
        <main class="mx-auto max-w-7xl px-6 py-12">
            <!-- Header -->
            <div class="mb-8">
                <a
                    href="{{ route("admin.dashboard") }}"
                    class="mb-2 inline-flex items-center text-sm font-medium text-gray-600 transition hover:text-gray-900"
                >
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back to Admin Dashboard
                </a>
                <h1 class="text-4xl font-semibold tracking-tight text-gray-900">KYC Verification</h1>
                <p class="mt-2 text-gray-500">Review and approve identity submissions</p>
            </div>

            @if (session("success"))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-500/20">
                    <p class="text-sm font-medium text-green-800">{{ session("success") }}</p>
                </div>
            @endif

            <!-- KYC Submissions Table -->
            <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-900/5">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-100 bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    User
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Document Type
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Document Number
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Submitted
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Reviewed By
                                </th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold tracking-wider text-gray-500 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kycs as $kyc)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $kyc->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $kyc->user->email }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ ucwords(str_replace("_", " ", $kyc->document_type)) }}
                                    </td>
                                    <td class="px-6 py-4 font-mono text-sm text-gray-600">
                                        {{ $kyc->document_number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($kyc->status === "pending")
                                            <span
                                                class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800"
                                            >
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                Pending Review
                                            </span>
                                        @elseif ($kyc->status === "approved")
                                            <span
                                                class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800"
                                            >
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                Approved
                                            </span>
                                        @elseif ($kyc->status === "rejected")
                                            <span
                                                class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800"
                                            >
                                                <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                                Rejected
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600"
                                            >
                                                {{ ucfirst($kyc->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $kyc->submitted_at ? $kyc->submitted_at->format("M j, Y") : "Not submitted" }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        @if ($kyc->reviewer)
                                            {{ $kyc->reviewer->name }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a
                                            href="{{ route("admin.kyc.show", $kyc) }}"
                                            class="text-sm font-medium text-blue-600 hover:text-blue-700"
                                        >
                                            Review
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No KYC submissions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($kycs->hasPages())
                    <div class="border-t border-gray-100 px-6 py-4">
                        {{ $kycs->links() }}
                    </div>
                @endif
            </div>
        </main>
    </body>
</html>
