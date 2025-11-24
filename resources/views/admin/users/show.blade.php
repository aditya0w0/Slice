@extends("layouts.app")

@section("title", "User Details - " . $user->name)

@section("content")
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            User Details
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">
                            View and manage user information
                        </p>
                    </div>
                    <a
                        href="{{ route("admin.users.index") }}"
                        class="text-slate-600 hover:text-slate-700 font-medium"
                    >
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Users
                    </a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - User Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Profile Card -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img
                                    src="{{ $user->profile_photo ? asset("storage/" . $user->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode($user->name) }}"
                                    alt="{{ $user->name }}"
                                    class="w-24 h-24 rounded-full object-cover mx-auto border-4 border-gray-100"
                                />
                                @if ($user->is_admin)
                                    <div
                                        class="absolute bottom-0 right-0 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full"
                                    >
                                        ADMIN
                                    </div>
                                @endif
                            </div>
                            <h3 class="mt-4 text-xl font-bold text-gray-900">
                                {{ $user->name }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $user->email }}
                            </p>

                            <!-- Status Badges -->
                            <div
                                class="mt-4 flex flex-wrap gap-2 justify-center"
                            >
                                @if ($user->kyc_verified)
                                    <span
                                        class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full"
                                    >
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Verified
                                    </span>
                                @else
                                    <span
                                        class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full"
                                    >
                                        <i class="fas fa-clock mr-1"></i>
                                        Unverified
                                    </span>
                                @endif

                                @if ($user->is_blacklisted)
                                    <span
                                        class="px-3 py-1 bg-red-100 text-red-800 text-xs font-medium rounded-full"
                                    >
                                        <i class="fas fa-ban mr-1"></i>
                                        Blacklisted
                                    </span>
                                @endif

                                @if ($user->credit_tier)
                                    <span
                                        class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                                    >
                                        {{ strtoupper($user->credit_tier) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div
                            class="mt-6 pt-6 border-t border-gray-200 grid grid-cols-2 gap-4 text-center"
                        >
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $user->orders->count() }}
                                </p>
                                <p class="text-xs text-gray-600">Orders</p>
                            </div>
                            <div>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $user->credit_score ?? 0 }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    Credit Score
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h4 class="font-semibold text-gray-900 mb-4">
                            Contact Information
                        </h4>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-start gap-3">
                                <i
                                    class="fas fa-envelope text-gray-400 mt-1"
                                ></i>
                                <div>
                                    <p class="text-gray-600">Email</p>
                                    <p class="text-gray-900 font-medium">
                                        {{ $user->email }}
                                    </p>
                                </div>
                            </div>
                            @if ($user->phone)
                                <div class="flex items-start gap-3">
                                    <i
                                        class="fas fa-phone text-gray-400 mt-1"
                                    ></i>
                                    <div>
                                        <p class="text-gray-600">Phone</p>
                                        <p class="text-gray-900 font-medium">
                                            {{ $user->phone }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($user->address)
                                <div class="flex items-start gap-3">
                                    <i
                                        class="fas fa-map-marker-alt text-gray-400 mt-1"
                                    ></i>
                                    <div>
                                        <p class="text-gray-600">Address</p>
                                        <p class="text-gray-900 font-medium">
                                            {{ $user->address }}
                                        </p>
                                        @if ($user->city || $user->state || $user->zip_code)
                                            <p
                                                class="text-gray-900 font-medium"
                                            >
                                                {{ $user->city }}{{ $user->state ? ", " . $user->state : "" }}
                                                {{ $user->zip_code }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <h4 class="font-semibold text-gray-900 mb-4">
                            Actions
                        </h4>
                        <div class="space-y-2">
                            <form
                                action="{{ route("admin.users.toggleAdmin", $user) }}"
                                method="POST"
                            >
                                @csrf
                                @method("PATCH")
                                <button
                                    type="submit"
                                    class="w-full px-4 py-2 text-left text-sm font-medium rounded-lg transition-colors {{ $user->is_admin ? "bg-yellow-50 text-yellow-700 hover:bg-yellow-100" : "bg-blue-50 text-blue-700 hover:bg-blue-100" }}"
                                >
                                    <i class="fas fa-user-shield mr-2"></i>
                                    {{ $user->is_admin ? "Remove Admin" : "Make Admin" }}
                                </button>
                            </form>

                            <form
                                action="{{ route("admin.users.destroy", $user) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this user?')"
                            >
                                @csrf
                                @method("DELETE")
                                <button
                                    type="submit"
                                    class="w-full px-4 py-2 text-left text-sm font-medium bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                                >
                                    <i class="fas fa-trash mr-2"></i>
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Account Information -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">
                                Account Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Full Name
                                    </label>
                                    <p class="mt-1 text-gray-900">
                                        {{ $user->name }}
                                    </p>
                                </div>
                                @if ($user->legal_name)
                                    <div>
                                        <label
                                            class="text-sm font-medium text-gray-600"
                                        >
                                            Legal Name
                                        </label>
                                        <p class="mt-1 text-gray-900">
                                            {{ $user->legal_name }}
                                        </p>
                                    </div>
                                @endif

                                @if ($user->date_of_birth)
                                    <div>
                                        <label
                                            class="text-sm font-medium text-gray-600"
                                        >
                                            Date of Birth
                                        </label>
                                        <p class="mt-1 text-gray-900">
                                            {{ $user->date_of_birth->format("M d, Y") }}
                                        </p>
                                    </div>
                                @endif

                                <div>
                                    <label
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Member Since
                                    </label>
                                    <p class="mt-1 text-gray-900">
                                        {{ $user->created_at->format("M d, Y") }}
                                    </p>
                                </div>
                                @if ($user->balance)
                                    <div>
                                        <label
                                            class="text-sm font-medium text-gray-600"
                                        >
                                            Balance
                                        </label>
                                        <p
                                            class="mt-1 text-gray-900 font-semibold"
                                        >
                                            ${{ number_format($user->balance, 2) }}
                                        </p>
                                    </div>
                                @endif

                                @if ($user->referral_code)
                                    <div>
                                        <label
                                            class="text-sm font-medium text-gray-600"
                                        >
                                            Referral Code
                                        </label>
                                        <p class="mt-1 text-gray-900 font-mono">
                                            {{ $user->referral_code }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Recent Orders
                                </h3>
                                @if ($totalOrders > 3)
                                    <button
                                        onclick="showAllOrders()"
                                        class="text-sm text-blue-600 hover:text-blue-700"
                                    >
                                        View All ({{ $totalOrders }})
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200" id="orders-list">
                            @forelse ($user->orders as $order)
                                <div class="px-6 py-4 hover:bg-gray-50">
                                    <div
                                        class="flex justify-between items-start"
                                    >
                                        <div>
                                            <p
                                                class="font-medium text-gray-900"
                                            >
                                                Order #{{ $order->id }}
                                            </p>
                                            <p
                                                class="text-sm text-gray-600 mt-1"
                                            >
                                                {{ $order->created_at->format("M d, Y g:i A") }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                class="font-semibold text-gray-900"
                                            >
                                                ${{ number_format($order->total_price, 2) }}
                                            </p>
                                            <span
                                                class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full {{ $order->status === "completed" ? "bg-green-100 text-green-800" : "" }} {{ $order->status === "pending" ? "bg-yellow-100 text-yellow-800" : "" }} {{ $order->status === "cancelled" ? "bg-red-100 text-red-800" : "" }}"
                                            >
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="px-6 py-8 text-center text-gray-500"
                                >
                                    <i
                                        class="fas fa-shopping-cart text-3xl mb-2"
                                    ></i>
                                    <p>No orders yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Login Activity -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200"
                    >
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Recent Login Activity
                                </h3>
                                @if ($totalLoginLogs > 3)
                                    <button
                                        onclick="showAllLogins()"
                                        class="text-sm text-blue-600 hover:text-blue-700"
                                    >
                                        View All ({{ $totalLoginLogs }})
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="divide-y divide-gray-200" id="logins-list">
                            @forelse ($user->loginLogs as $log)
                                <div class="px-6 py-4">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center"
                                        >
                                            <i
                                                class="fas fa-sign-in-alt text-gray-600 text-sm"
                                            ></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">
                                                {{ $log->logged_in_at->format("M d, Y g:i A") }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-600 mt-1"
                                            >
                                                {{ $log->ip_address ?? "Unknown IP" }}
                                                •
                                                {{ $log->user_agent ?? "Unknown Device" }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div
                                    class="px-6 py-8 text-center text-gray-500"
                                >
                                    <i class="fas fa-history text-3xl mb-2"></i>
                                    <p>No login history available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Orders Modal -->
        <div
            id="orders-modal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        >
            <div
                class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden"
            >
                <div
                    class="px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        All Orders for {{ $user->name }}
                    </h3>
                    <button
                        onclick="closeOrdersModal()"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div
                    class="overflow-y-auto max-h-[60vh]"
                    id="all-orders-container"
                >
                    <div class="text-center py-8">
                        <i
                            class="fas fa-spinner fa-spin text-3xl text-gray-400"
                        ></i>
                        <p class="text-gray-600 mt-2">Loading orders...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Login Logs Modal -->
        <div
            id="logins-modal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        >
            <div
                class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-hidden"
            >
                <div
                    class="px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        All Login Activity for {{ $user->name }}
                    </h3>
                    <button
                        onclick="closeLoginsModal()"
                        class="text-gray-500 hover:text-gray-700"
                    >
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div
                    class="overflow-y-auto max-h-[60vh]"
                    id="all-logins-container"
                >
                    <div class="text-center py-8">
                        <i
                            class="fas fa-spinner fa-spin text-3xl text-gray-400"
                        ></i>
                        <p class="text-gray-600 mt-2">
                            Loading login activity...
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAllOrders() {
            document.getElementById('orders-modal').classList.remove('hidden');

            fetch('{{ route("admin.users.orders", $user) }}')
                .then((response) => response.json())
                .then((data) => {
                    const container = document.getElementById(
                        'all-orders-container',
                    );
                    if (data.orders.length === 0) {
                        container.innerHTML =
                            '<div class="px-6 py-8 text-center text-gray-500"><i class="fas fa-shopping-cart text-3xl mb-2"></i><p>No orders yet</p></div>';
                        return;
                    }

                    let html = '<div class="divide-y divide-gray-200">';
                    data.orders.forEach((order) => {
                        let statusClass = '';
                        if (order.status === 'completed')
                            statusClass = 'bg-green-100 text-green-800';
                        else if (order.status === 'pending')
                            statusClass = 'bg-yellow-100 text-yellow-800';
                        else if (order.status === 'cancelled')
                            statusClass = 'bg-red-100 text-red-800';
                        else if (order.status === 'active')
                            statusClass = 'bg-blue-100 text-blue-800';

                        html += `
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900">Order #${order.id}</p>
                                <p class="text-sm text-gray-600 mt-1">${order.created_at}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">$${order.total_price}</p>
                                <span class="inline-block mt-1 px-2 py-1 text-xs font-medium rounded-full ${statusClass}">
                                    ${order.status.charAt(0).toUpperCase() + order.status.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                })
                .catch((error) => {
                    console.error('Error:', error);
                    document.getElementById('all-orders-container').innerHTML =
                        '<div class="px-6 py-8 text-center text-red-500"><p>Failed to load orders</p></div>';
                });
        }

        function closeOrdersModal() {
            document.getElementById('orders-modal').classList.add('hidden');
        }

        function showAllLogins() {
            document.getElementById('logins-modal').classList.remove('hidden');

            fetch('{{ route("admin.users.logins", $user) }}')
                .then((response) => response.json())
                .then((data) => {
                    const container = document.getElementById(
                        'all-logins-container',
                    );
                    if (data.logins.length === 0) {
                        container.innerHTML =
                            '<div class="px-6 py-8 text-center text-gray-500"><i class="fas fa-history text-3xl mb-2"></i><p>No login history available</p></div>';
                        return;
                    }

                    let html = '<div class="divide-y divide-gray-200">';
                    data.logins.forEach((log) => {
                        html += `
                    <div class="px-6 py-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-gray-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">${log.logged_in_at}</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    ${log.ip_address} • ${log.user_agent}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                })
                .catch((error) => {
                    console.error('Error:', error);
                    document.getElementById('all-logins-container').innerHTML =
                        '<div class="px-6 py-8 text-center text-red-500"><p>Failed to load login activity</p></div>';
                });
        }

        function closeLoginsModal() {
            document.getElementById('logins-modal').classList.add('hidden');
        }

        // Close modals on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeOrdersModal();
                closeLoginsModal();
            }
        });
    </script>
@endsection
