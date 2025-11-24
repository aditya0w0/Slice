@extends("layouts.app")

@section("title", $notification->title)

@section("content")
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="border-b border-gray-200 bg-white shadow-sm">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between py-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $notification->title }}</h1>
                        <p class="mt-1 text-sm text-gray-600">
                            {{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a
                            href="{{ route("notifications.index") }}"
                            class="font-medium text-slate-600 hover:text-slate-700"
                        >
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Notifications
                        </a>
                        <form
                            action="{{ route("notifications.destroy", $notification->id) }}"
                            method="POST"
                            class="inline"
                            onsubmit="return confirm('Are you sure you want to delete this notification? This action cannot be undone.')"
                        >
                            @csrf
                            @method("DELETE")
                            <button type="submit" class="font-medium text-red-600 hover:text-red-700">
                                <i class="fas fa-trash mr-2"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Content -->
        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <!-- Notification Header -->
                <div class="border-b border-gray-200 px-6 py-4">
                    <div class="flex items-center gap-3">
                        @switch($notification->type)
                            @case("info")
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100">
                                    <i class="fas fa-info text-lg text-blue-600"></i>
                                </div>

                                @break
                            @case("warning")
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100">
                                    <i class="fas fa-exclamation-triangle text-lg text-yellow-600"></i>
                                </div>

                                @break
                            @case("success")
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                    <i class="fas fa-check-circle text-lg text-green-600"></i>
                                </div>

                                @break
                            @case("error")
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                                    <i class="fas fa-times-circle text-lg text-red-600"></i>
                                </div>

                                @break
                            @default
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100">
                                    <i class="fas fa-bell text-lg text-gray-600"></i>
                                </div>
                        @endswitch
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">{{ $notification->title }}</h2>
                            <div class="mt-1 flex items-center gap-4">
                                <span class="text-sm text-gray-600 capitalize">
                                    {{ $notification->type }} notification
                                </span>
                                @if ($notification->is_read)
                                    <span
                                        class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800"
                                    >
                                        <i class="fas fa-check mr-1"></i>
                                        Read
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800"
                                    >
                                        <i class="fas fa-envelope mr-1"></i>
                                        Unread
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notification Message -->
                <div class="px-6 py-6">
                    <div class="prose prose-gray max-w-none">
                        <p class="text-lg leading-relaxed whitespace-pre-line text-gray-700">
                            {{ $notification->message }}
                        </p>
                    </div>

                    @if ($notification->action_url)
                        <div class="mt-6 border-t border-gray-200 pt-6">
                            <a
                                href="{{ $notification->action_url }}"
                                class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 font-medium text-white transition-colors hover:bg-slate-700"
                            >
                                <i class="fas fa-external-link-alt mr-2"></i>
                                Take Action
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Support Chat Section -->
            <div class="mt-8 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">Need Help?</h3>
                    <p class="text-sm text-gray-600">Contact our support team for assistance with this notification</p>
                </div>

                <div class="p-6">
                    <div id="support-chat" class="min-h-64 space-y-4">
                        <!-- Chat messages will be loaded here -->
                        <div id="chat-placeholder" class="py-8 text-center">
                            <i class="fas fa-comments mb-4 text-3xl text-gray-400"></i>
                            <h4 class="mb-2 text-lg font-medium text-gray-900">Start a Support Conversation</h4>
                            <p class="mb-4 text-gray-600">Get help from our support team regarding this notification</p>
                            <button
                                onclick="startSupportChat()"
                                class="inline-flex items-center rounded-lg bg-slate-600 px-4 py-2 font-medium text-white transition-colors hover:bg-slate-700"
                            >
                                <i class="fas fa-comment-dots mr-2"></i>
                                Start Chat
                            </button>
                        </div>
                    </div>

                    <!-- Chat Input (hidden initially) -->
                    <div id="chat-input" class="mt-4 hidden border-t border-gray-200 pt-4">
                        <div class="flex gap-3">
                            <input
                                type="text"
                                id="chat-message"
                                placeholder="Type your message..."
                                class="flex-1 rounded-lg border border-gray-300 px-3 py-2 focus:border-slate-500 focus:ring-2 focus:ring-slate-500"
                                onkeypress="handleChatKeyPress(event)"
                            />
                            <button
                                onclick="sendChatMessage()"
                                class="rounded-lg bg-slate-600 px-4 py-2 font-medium text-white transition-colors hover:bg-slate-700"
                            >
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Our support team typically responds within 24 hours</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let chatStarted = false;
        let chatMessages = [];
        let notificationId = {{ $notification->id }};

        function startSupportChat() {
            if (chatStarted) return;

            chatStarted = true;

            // Show chat input
            document.getElementById('chat-input').classList.remove('hidden');

            // Hide placeholder
            document.getElementById('chat-placeholder').style.display = 'none';

            // Load existing messages
            loadChatMessages();

            // Add welcome message if no messages exist
            setTimeout(() => {
                if (chatMessages.length === 0) {
                    addLocalMessage(
                        'admin',
                        "Hello! I'm here to help you with your notification. How can I assist you today?",
                    );
                }
            }, 500);
        }

        function loadChatMessages() {
            fetch(`{{ route("support.messages") }}?notification_id=${notificationId}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    Accept: 'application/json',
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    chatMessages = data.messages || [];
                    updateChatUI();
                })
                .catch((error) => {
                    console.error('Error loading messages:', error);
                });
        }

        function addLocalMessage(sender, message) {
            chatMessages.push({
                sender_type: sender,
                message: message,
                created_at: new Date(),
                formatted_time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
            });
            updateChatUI();
        }

        function updateChatUI() {
            const chatContainer = document.getElementById('support-chat');

            // Clear existing messages (but keep placeholder if no chat started)
            if (chatStarted) {
                chatContainer.innerHTML = '';
            }

            // Add messages
            chatMessages.forEach((msg) => {
                const messageDiv = document.createElement('div');
                messageDiv.className = `flex ${msg.sender_type === 'user' ? 'justify-end' : 'justify-start'}`;

                messageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
                msg.sender_type === 'user' ? 'bg-slate-600 text-white' : 'bg-gray-100 text-gray-900'
            }">
                <p class="text-sm">${msg.message}</p>
                <p class="text-xs mt-1 opacity-70">${msg.formatted_time}</p>
            </div>
        `;

                chatContainer.appendChild(messageDiv);
            });

            // Scroll to bottom
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function sendChatMessage() {
            const input = document.getElementById('chat-message');
            const message = input.value.trim();

            if (!message) return;

            // Disable input while sending
            input.disabled = true;

            fetch('{{ route("support.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    notification_id: notificationId,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        // Add the sent message to local chat
                        addLocalMessage('user', message);

                        // Clear input
                        input.value = '';
                        input.disabled = false;

                        // Focus back on input
                        input.focus();

                        // In a real app, you'd wait for admin response via WebSocket or polling
                        // For now, simulate a response after a delay
                        setTimeout(() => {
                            simulateAdminResponse(message);
                        }, 2000);
                    } else {
                        alert('Failed to send message. Please try again.');
                        input.disabled = false;
                    }
                })
                .catch((error) => {
                    console.error('Error sending message:', error);
                    alert('Failed to send message. Please try again.');
                    input.disabled = false;
                });
        }

        function simulateAdminResponse(userMessage) {
            // Simple response simulation - in a real app this would come from admin
            const responses = [
                "Thank you for your message. I'm looking into this for you.",
                'I understand your concern. Let me check our records.',
                "I've noted your request. Our team will get back to you soon.",
                'Is there any additional information that would help me assist you better?',
                "Thank you for bringing this to our attention. We're working on it.",
            ];

            const randomResponse = responses[Math.floor(Math.random() * responses.length)];
            addLocalMessage('admin', randomResponse);
        }

        function handleChatKeyPress(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendChatMessage();
            }
        }

        // Auto-mark as read when page loads (if not already read)
        document.addEventListener('DOMContentLoaded', function () {
            // The notification is already marked as read in the controller
            // This is just for any additional client-side logic if needed
        });
    </script>
@endsection
