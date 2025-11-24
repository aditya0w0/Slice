@extends("layouts.app")

@section("title", "Messages - Slice")

@section("content")
    <div
        class="h-screen bg-[#0B0C10] flex overflow-hidden font-sans selection:bg-blue-500/30 selection:text-blue-100"
    >
        <!-- Conversations Sidebar -->
        <div class="w-80 bg-[#0F1015] border-r border-white/5 flex flex-col">
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-white/5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">Messages</h2>
                    <button
                        class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4v16m8-8H4"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        placeholder="Search conversations..."
                        class="w-full bg-[#16171D] border border-white/10 rounded-lg pl-10 pr-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50"
                    />
                    <svg
                        class="w-5 h-5 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </div>
            </div>

            <!-- Conversations List -->
            <div class="flex-1 overflow-y-auto">
                @foreach ($conversations as $conversation)
                    <a
                        href="{{ route("chat.index", ["userId" => $conversation["user"]["id"]]) }}"
                        class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors border-l-2 {{ $conversation["active"] ? "border-blue-500 bg-white/5" : "border-transparent" }}"
                    >
                        <div class="relative flex-shrink-0">
                            <img
                                src="{{ $conversation["user"]["avatar"] }}"
                                class="w-12 h-12 rounded-full border-2 border-white/10"
                                alt=""
                            />
                            @if ($conversation["user"]["online"])
                                <span
                                    class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-[#0F1015]"
                                ></span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h3
                                    class="text-sm font-semibold text-white truncate"
                                >
                                    {{ $conversation["user"]["name"] }}
                                </h3>
                                <span class="text-xs text-slate-500">
                                    {{ $conversation["last_message_time"] }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 truncate">
                                {{ $conversation["last_message"] }}
                            </p>
                        </div>
                        @if ($conversation["unread_count"] > 0)
                            <span
                                class="flex-shrink-0 px-2 py-0.5 bg-blue-600 text-white text-xs font-semibold rounded-full"
                            >
                                {{ $conversation["unread_count"] }}
                            </span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- User Profile Footer -->
            <div class="p-4 border-t border-white/5">
                <div class="flex items-center gap-3">
                    <img
                        src="{{ Auth::user()->profile_photo ? asset("storage/" . Auth::user()->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode(Auth::user()->name) . "&background=0D8ABC&color=fff" }}"
                        class="w-10 h-10 rounded-full border-2 border-white/10"
                        alt=""
                    />
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-white truncate">
                            {{ Auth::user()->name }}
                        </h3>
                        <p class="text-xs text-slate-400">Online</p>
                    </div>
                    <a
                        href="{{ route("dashboard") }}"
                        class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chat Container -->
        <div class="flex-1 flex flex-col bg-[#0B0C10]">
            <!-- Chat Header -->
            <div
                class="h-16 px-6 bg-[#0F1015] border-b border-white/5 flex items-center justify-between"
            >
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <img
                                src="{{ $activeChat["user"]["avatar"] }}"
                                class="w-10 h-10 rounded-full border-2 border-white/10"
                                alt=""
                            />
                            @if ($activeChat["user"]["online"])
                                <span
                                    class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-[#0F1015]"
                                ></span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-white">
                                {{ $activeChat["user"]["name"] }}
                            </h3>
                            <p
                                class="text-xs {{ $activeChat["user"]["online"] ? "text-green-500" : "text-slate-500" }}"
                            >
                                {{ $activeChat["user"]["status"] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </button>
                    <button
                        class="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                            />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Messages Area -->
            <div
                id="messages-container"
                class="flex-1 overflow-y-auto p-6 space-y-4"
                style="
                    background: linear-gradient(
                        180deg,
                        #0b0c10 0%,
                        #0f1015 100%
                    );
                "
            >
                @if (count($activeChat["messages"]) === 0)
                    <!-- Empty State -->
                    <div
                        class="flex flex-col items-center justify-center h-full text-center"
                    >
                        <div
                            class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4"
                        >
                            <svg
                                class="w-10 h-10 text-slate-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">
                            Start a conversation
                        </h3>
                        <p class="text-sm text-slate-500 max-w-sm">
                            Send a message to our support team. We're here to
                            help with any questions or issues you may have.
                        </p>
                    </div>
                @else
                    <!-- Date Separator -->
                    <div class="flex justify-center my-4">
                        <span
                            class="px-3 py-1 bg-white/5 text-slate-500 text-xs rounded-full border border-white/10"
                        >
                            Today
                        </span>
                    </div>

                    @foreach ($activeChat["messages"] as $message)
                        <!-- Message -->
                        <div
                            class="flex {{ $message["sender"] === "me" ? "justify-end" : "justify-start" }} group animate-fade-in-up"
                        >
                            <!-- Avatar if 'them' -->
                            @if ($message["sender"] === "them")
                                <img
                                    src="{{ $activeChat["user"]["avatar"] }}"
                                    class="w-8 h-8 rounded-full mr-3 mt-1 border-2 border-[#0B0C10] self-end mb-1"
                                />
                            @endif

                            <div class="max-w-[70%]">
                                <!-- Bubble -->
                                <div
                                    class="relative px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-md {{
                                        $message["sender"] === "me" ? "rounded-br-sm bg-blue-600 text-white" : "rounded-bl-sm border border-white/5 bg-[#1F2029] text-slate-200"
                                    }}"
                                >
                                    {{ $message["content"] }}
                                </div>

                                <!-- Meta -->
                                <div
                                    class="flex items-center gap-1.5 mt-1 {{ $message["sender"] === "me" ? "justify-end" : "justify-start" }} opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                >
                                    <p class="text-[10px] text-slate-500">
                                        {{ $message["time"] }}
                                    </p>
                                    @if ($message["sender"] === "me")
                                        <svg
                                            class="w-3 h-3 text-blue-500"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 13l4 4L19 7"
                                            />
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <!-- Avatar if 'me' -->
                            @if ($message["sender"] === "me")
                                <img
                                    src="{{ Auth::user()->profile_photo ? asset("storage/" . Auth::user()->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode(Auth::user()->name) . "&background=0D8ABC&color=fff" }}"
                                    class="w-8 h-8 rounded-full ml-3 mt-1 border-2 border-[#0B0C10] self-end mb-1"
                                />
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Input Zone -->
            <div class="p-5 bg-[#0B0C10] border-t border-white/5">
                <form
                    id="message-form"
                    onsubmit="sendMessage(event)"
                    class="relative"
                >
                    <div
                        class="relative flex items-center bg-[#16171D] border border-white/10 rounded-xl shadow-lg focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50 transition-all"
                    >
                        <!-- Attachment -->
                        <button
                            type="button"
                            class="p-3 text-slate-500 hover:text-white transition-colors"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                />
                            </svg>
                        </button>

                        <input
                            type="text"
                            id="message-input"
                            placeholder="Type a message to support..."
                            class="flex-1 bg-transparent border-none text-sm text-white placeholder-slate-500 focus:ring-0 py-3.5 px-2"
                            autocomplete="off"
                        />

                        <!-- Actions -->
                        <div class="flex items-center gap-2 pr-2">
                            <button
                                type="button"
                                class="p-2 text-slate-500 hover:text-white transition-colors"
                            >
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </button>
                            <button
                                type="submit"
                                class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-all shadow-lg shadow-blue-600/20 hover:scale-105"
                            >
                                <svg
                                    class="w-5 h-5 transform rotate-90 ml-0.5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.3s ease-out;
        }
    </style>

    <script>
        function sendMessage(event) {
            event.preventDefault();

            const messageInput = document.getElementById('message-input');
            const message = messageInput.value.trim();

            if (!message) return;

            fetch('{{ route("chat.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    message: message,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        const messagesContainer =
                            document.getElementById('messages-container');
                        const messageDiv = document.createElement('div');
                        messageDiv.className =
                            'flex justify-end group animate-fade-in-up';
                        messageDiv.innerHTML = `
                    <div class="max-w-[70%]">
                        <div class="relative px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-md bg-blue-600 text-white rounded-br-sm">
                            ${message}
                        </div>
                        <div class="flex items-center gap-1.5 mt-1 justify-end opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <p class="text-[10px] text-slate-500">Just now</p>
                            <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                    </div>
                    <img src="{{ Auth::user()->profile_photo ? asset("storage/" . Auth::user()->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode(Auth::user()->name) . "&background=0D8ABC&color=fff" }}" class="w-8 h-8 rounded-full ml-3 mt-1 border-2 border-[#0B0C10] self-end mb-1">
                `;
                        messagesContainer.appendChild(messageDiv);
                        messagesContainer.scrollTop =
                            messagesContainer.scrollHeight;
                        messageInput.value = '';
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Failed to send message. Please try again.');
                });
        }

        // Auto-scroll to bottom on load
        document.addEventListener('DOMContentLoaded', function () {
            const messagesContainer =
                document.getElementById('messages-container');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
@endsection
