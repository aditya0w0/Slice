@extends('layouts.app')

@section('title', 'Admin Operations - Support Center')

@section('content')
<div class="h-screen bg-[#0B0C10] flex overflow-hidden font-sans selection:bg-blue-500/30 selection:text-blue-100" x-data="{ sidebarOpen: false, profileOpen: false, mobileMenuOpen: false }">

    <!-- 1. Admin Navigation Rail (Collapsible) -->
    <div
        :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="flex-shrink-0 bg-[#121217] border-r border-white/5 flex flex-col transition-all duration-300 ease-in-out relative z-30"
    >
        <!-- Header / Logo -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-white/5">
            <div class="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" x-transition.opacity.duration.200ms class="text-lg font-bold text-white tracking-tight">Slice<span class="text-blue-500">.</span></span>
            </div>

            <!-- Toggle Button -->
            <button @click="sidebarOpen = !sidebarOpen" class="absolute -right-3 top-20 bg-blue-600 text-white rounded-full p-1 shadow-lg hover:bg-blue-500 transition-colors z-50 border border-[#0B0C10]">
                <svg class="w-3 h-3 transition-transform duration-300" :class="!sidebarOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- Nav Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-1 custom-scrollbar">
            <!-- Section Label -->
            <div x-show="sidebarOpen" class="px-3 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider transition-opacity duration-200">
                Operations
            </div>

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Overview</span>
            </a>

            <!-- Inbox (Active) -->
            <div class="group flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-600/10 text-white border border-blue-600/20 relative">
                <!-- Active Indicator Strip -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r-full"></div>

                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center ml-1">
                    <svg class="w-5 h-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Inbox</span>
                <span x-show="sidebarOpen" class="ml-auto bg-blue-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md">4</span>
            </div>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Users</span>
            </a>

            <!-- Notifications -->
            <a href="{{ route('admin.notifications.index') }}" class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                <div class="flex-shrink-0 w-6 h-6 flex items-center justify-center">
                    <svg class="w-5 h-5 group-hover:text-yellow-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Broadcasts</span>
            </a>
        </nav>

        <!-- Admin Profile -->
        <div class="p-4 border-t border-white/5">
            <div class="relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 w-full p-2 rounded-xl hover:bg-white/5 transition-colors group">
                    @php
                        $admin = Auth::user();
                        $adminHash = md5(strtolower(trim($admin->email)));
                        $adminAvatar = "https://www.gravatar.com/avatar/{$adminHash}?s=48&d=identicon";
                    @endphp
                    <img src="{{ $adminAvatar }}" class="w-8 h-8 rounded-full ring-2 ring-white/10 group-hover:ring-white/30 transition-all" alt="">

                    <div class="flex-1 min-w-0 text-left" x-show="sidebarOpen">
                        <p class="text-sm font-medium text-white truncate">{{ $admin->name }}</p>
                        <p class="text-xs text-slate-500 truncate">Administrator</p>
                    </div>

                    <svg x-show="sidebarOpen" class="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div
                    x-show="profileOpen"
                    @click.away="profileOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                    x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                    class="absolute bottom-full left-0 w-full mb-2 bg-[#1A1B21] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50"
                    style="display: none;"
                >
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Conversations List Panel -->
    <div class="w-80 bg-[#0F1015] border-r border-white/5 flex flex-col z-20">
        <!-- Header -->
        <div class="h-16 px-6 border-b border-white/5 flex items-center justify-between bg-[#0F1015]">
            <h2 class="text-lg font-bold text-white">Inbox</h2>
            <div class="flex gap-2">
                <button class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                </button>
                <button class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="px-4 py-3">
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    placeholder="Search messages..."
                    class="w-full bg-[#1A1B21] border border-white/5 rounded-lg py-2 pl-10 pr-4 text-sm text-slate-300 placeholder-slate-600 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all"
                >
            </div>
        </div>

        <!-- List -->
        <div class="flex-1 overflow-y-auto custom-scrollbar">
            @forelse($contacts as $contact)
            <div
                onclick="loadConversation({{ $contact['id'] }})"
                class="group px-4 py-3 border-b border-white/5 hover:bg-white/[0.02] cursor-pointer transition-all {{ isset($contact['active']) ? 'bg-blue-500/5 border-l-2 border-l-blue-500' : 'border-l-2 border-l-transparent' }}"
            >
                <div class="flex items-start gap-3">
                    <div class="relative flex-shrink-0">
                        <img src="{{ $contact['avatar'] }}" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/5 group-hover:ring-white/10 transition-all">
                        @if($contact['status'] === 'online')
                        <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-[#0F1015]"></div>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-0.5">
                            <h3 class="text-sm font-semibold text-white truncate">{{ $contact['name'] }}</h3>
                            <span class="text-[10px] text-slate-500">{{ $contact['time'] }}</span>
                        </div>
                        <p class="text-xs text-slate-400 truncate group-hover:text-slate-300 transition-colors {{ $contact['is_typing'] ? 'text-green-400 font-medium' : '' }}">
                            {{ $contact['is_typing'] ? 'Typing...' : $contact['last_message'] }}
                        </p>
                    </div>

                    @if($contact['unread'] > 0)
                    <div class="flex-shrink-0 self-center ml-2">
                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-[10px] font-bold text-white shadow-lg shadow-blue-600/30">
                            {{ $contact['unread'] }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-64 text-slate-500 px-6 text-center">
                <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="text-sm">No active tickets</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- 3. Active Chat Canvas -->
    <div class="flex-1 flex flex-col bg-[#0B0C10] relative z-10">

        @if(isset($activeChat['user']['id']))
        <!-- Header -->
        <div class="h-16 px-6 border-b border-white/5 flex items-center justify-between bg-[#0B0C10]/80 backdrop-blur-xl">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="{{ $activeChat['user']['avatar'] }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-white/10">
                    <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-[#0B0C10]"></div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        {{ $activeChat['user']['name'] }}
                        <span class="px-2 py-0.5 rounded-md bg-white/5 border border-white/5 text-[10px] text-slate-400 font-normal uppercase tracking-wide">Client</span>
                    </h3>
                    <p class="text-xs text-green-400 flex items-center gap-1.5 mt-0.5">
                        <span class="w-1 h-1 rounded-full bg-green-400"></span>
                        {{ $activeChat['user']['status'] }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.users.show', $activeChat['user']['id']) }}" class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-xs font-medium text-slate-300 hover:bg-white/10 hover:text-white transition-colors">
                    View Profile
                </a>
                <button class="p-2 text-slate-400 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                </button>
            </div>
        </div>

        <!-- Messages Feed -->
        <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')]">

            <div class="flex justify-center py-4">
                <span class="px-3 py-1 rounded-full bg-white/5 border border-white/5 text-[10px] font-medium text-slate-500">Start of conversation</span>
            </div>

            @foreach($activeChat['messages'] as $message)
            <div class="flex {{ $message['sender'] === 'me' ? 'justify-end' : 'justify-start' }} group">
                <!-- Avatar if 'them' -->
                @if($message['sender'] === 'them')
                <img src="{{ $activeChat['user']['avatar'] }}" class="w-8 h-8 rounded-full mr-3 mt-1 opacity-70 self-end mb-1">
                @endif

                <div class="max-w-[70%]">
                    <!-- Bubble -->
                    <div class="relative px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-md
                        {{ $message['sender'] === 'me'
                            ? 'bg-blue-600 text-white rounded-br-sm'
                            : 'bg-[#1F2029] border border-white/5 text-slate-200 rounded-bl-sm'
                        }}">
                        {{ $message['content'] }}
                    </div>

                    <!-- Meta -->
                    <div class="flex items-center gap-1.5 mt-1 {{ $message['sender'] === 'me' ? 'justify-end' : 'justify-start' }} opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                        <p class="text-[10px] text-slate-500">{{ $message['time'] }}</p>
                        @if($message['sender'] === 'me')
                            <svg class="w-3 h-3 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        @endif
                    </div>
                </div>

                <!-- Avatar if 'me' -->
                @if($message['sender'] === 'me')
                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff' }}" class="w-8 h-8 rounded-full ml-3 mt-1 border-2 border-[#0B0C10] self-end mb-1">
                @endif
            </div>
            @endforeach
        </div>

        <!-- Input Zone -->
        <div class="p-5 bg-[#0B0C10] border-t border-white/5">
            <form id="message-form" onsubmit="sendMessage(event)" class="relative">
                <input type="hidden" id="active-user-id" value="{{ $activeChat['user']['id'] }}">

                <div class="relative flex items-center bg-[#16171D] border border-white/10 rounded-xl shadow-lg focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50 transition-all">
                    <!-- Attachment -->
                    <button type="button" class="p-3 text-slate-500 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                    </button>

                    <input
                        type="text"
                        id="message-input"
                        placeholder="Type a message to {{ $activeChat['user']['name'] }}..."
                        class="flex-1 bg-transparent border-none text-sm text-white placeholder-slate-500 focus:ring-0 py-3.5 px-2"
                        autocomplete="off"
                    >

                    <!-- Actions -->
                    <div class="flex items-center gap-2 pr-2">
                        <button type="button" class="p-2 text-slate-500 hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </button>
                        <button type="submit" class="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-all shadow-lg shadow-blue-600/20 hover:scale-105">
                            <svg class="w-5 h-5 transform rotate-90 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @else
        <!-- Empty State -->
        <div class="flex-1 flex flex-col items-center justify-center text-center p-8">
            <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 relative">
                <div class="absolute inset-0 bg-blue-500/10 rounded-full animate-pulse"></div>
                <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
            </div>
            <h2 class="text-xl font-bold text-white">Admin Command Center</h2>
            <p class="text-slate-500 max-w-sm mt-2">Select a conversation from the inbox to manage support tickets and user inquiries.</p>
        </div>
        @endif
    </div>
</div>

<script>
    function sendMessage(event) {
        event.preventDefault();

        const messageInput = document.getElementById('message-input');
        const userId = document.getElementById('active-user-id').value;
        const message = messageInput.value.trim();

        if (!message) return;

        // Optimistic UI: Append message immediately (optional, implemented basic flow here)

        fetch('{{ route("admin.chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_id: userId,
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messagesContainer = document.getElementById('messages-container');
                const messageDiv = document.createElement('div');
                messageDiv.className = 'flex justify-end group animate-fade-in-up';
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
                `;
                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                messageInput.value = '';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to transmit message. Check console.');
        });
    }

    function loadConversation(userId) {
        window.location.href = `{{ route('admin.chat.index') }}?user_id=${userId}`;
    }

    // Auto-scroll to bottom on load
    document.addEventListener('DOMContentLoaded', function() {
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    });
</script>
@endsection
