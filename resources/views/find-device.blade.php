<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Find My Device - {{ $device->name }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <style>
            #map {
                height: 100%;
                width: 100%;
                z-index: 1;
            }

            .apple-card {
                backdrop-filter: blur(20px);
                background-color: rgba(255, 255, 255, 0.9);
            }

            .dark .apple-card {
                background-color: rgba(30, 30, 30, 0.9);
            }

            .pulsing-dot {
                animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            }

            @keyframes pulse {
                0%,
                100% {
                    opacity: 1;
                }
                50% {
                    opacity: 0.5;
                }
            }

            .leaflet-container {
                font-family:
                    system-ui,
                    -apple-system,
                    sans-serif;
                z-index: 1 !important;
            }

            .leaflet-pane,
            .leaflet-tile-pane {
                z-index: 1 !important;
            }

            .leaflet-control-container {
                z-index: 40 !important;
            }
        </style>
    </head>
    <body class="bg-gray-50 antialiased">
        <div x-data="findDevice()" class="relative h-screen w-full overflow-hidden">
            <!-- Map Container -->
            <div id="map" class="absolute inset-0" style="z-index: 1"></div>

            <!-- Top Bar - Apple Style -->
            <div class="absolute top-0 right-0 left-0 z-50 p-4" style="pointer-events: auto">
                <div
                    class="apple-card mx-auto flex max-w-4xl items-center justify-between rounded-2xl px-6 py-4 shadow-xl"
                >
                    <a
                        href="{{ route("dashboard") }}"
                        class="flex items-center space-x-2 text-blue-600 transition hover:text-blue-700"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        <span class="font-medium">Back</span>
                    </a>

                    <div class="text-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            {{ $device->name ?? ($order->device_name ?? "Device") }}
                        </h1>
                        <p class="text-sm text-gray-600" x-text="lastUpdated">Locating...</p>
                    </div>

                    <div class="w-24"></div>
                </div>
            </div>

            <!-- Device Info Card - Bottom -->
            <div class="absolute right-0 bottom-0 left-0 z-50 p-4" style="pointer-events: auto">
                <div class="apple-card mx-auto max-w-4xl rounded-3xl shadow-2xl">
                    <!-- Device Header -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="flex items-center space-x-4">
                            <!-- Device Icon -->
                            <div
                                class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600"
                            >
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if (isset($device->family) && $device->family === "smartphone")
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                                        />
                                    @elseif (isset($device->family) && $device->family === "tablet")
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                        />
                                    @elseif (isset($device->family) && $device->family === "laptop")
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    @else
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    @endif
                                </svg>
                            </div>

                            <!-- Device Info -->
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    {{ $device->name ?? ($order->device_name ?? "Device") }}
                                </h2>
                                <div class="mt-1 flex items-center space-x-4">
                                    <div class="flex items-center space-x-1">
                                        <span class="pulsing-dot inline-block h-2 w-2 rounded-full bg-green-500"></span>
                                        <span class="text-sm font-medium text-green-600" x-text="status">
                                            Online
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-600">•</span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"
                                            />
                                            <path
                                                d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"
                                            />
                                        </svg>
                                        <span class="text-sm text-gray-600" x-text="battery + '%'">
                                            {{ $order->battery_level ?? 85 }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Details -->
                    <div class="border-b border-gray-200 p-6">
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <svg
                                    class="mt-0.5 h-5 w-5 flex-shrink-0 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Current Location</p>
                                    <p class="text-sm text-gray-600" x-text="address">
                                        {{ $order->delivery_address ?? "Locating device..." }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <svg
                                    class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-sm text-gray-600" x-text="lastSeen">Just now</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-3 gap-4 p-6">
                        <!-- Play Sound -->
                        <button
                            @click="playSound()"
                            class="flex flex-col items-center justify-center rounded-2xl bg-gray-100 p-4 transition hover:bg-gray-200 active:scale-95"
                            :disabled="!isOnline"
                            :class="{ 'opacity-50 cursor-not-allowed': !isOnline }"
                        >
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"
                                />
                            </svg>
                            <span class="mt-2 text-xs font-medium text-gray-900">Play Sound</span>
                        </button>

                        <!-- Directions -->
                        <button
                            @click="getDirections()"
                            class="flex flex-col items-center justify-center rounded-2xl bg-gray-100 p-4 transition hover:bg-gray-200 active:scale-95"
                        >
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                />
                            </svg>
                            <span class="mt-2 text-xs font-medium text-gray-900">Directions</span>
                        </button>

                        <!-- Notify -->
                        <button
                            @click="notifyWhenFound()"
                            class="flex flex-col items-center justify-center rounded-2xl bg-gray-100 p-4 transition hover:bg-gray-200 active:scale-95"
                            :disabled="isOnline"
                            :class="{ 'opacity-50 cursor-not-allowed': isOnline }"
                        >
                            <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                />
                            </svg>
                            <span class="mt-2 text-xs font-medium text-gray-900">Notify</span>
                        </button>
                    </div>

                    <!-- Lost Mode Toggle -->
                    <div class="border-t border-gray-200 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-900">Lost Mode</h3>
                                <p class="text-sm text-gray-600">Lock device and show contact message</p>
                            </div>
                            <button
                                @click="toggleLostMode()"
                                :class="lostMode ? 'bg-red-600' : 'bg-gray-300'"
                                class="relative inline-flex h-8 w-14 items-center rounded-full transition-colors focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
                            >
                                <span
                                    :class="lostMode ? 'translate-x-7' : 'translate-x-1'"
                                    class="inline-block h-6 w-6 transform rounded-full bg-white transition-transform"
                                ></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function findDevice() {
                return {
                    map: null,
                    marker: null,
                    circle: null,
                    isOnline: true,
                    lostMode: false,
                    battery: {{ $order->battery_level ?? 85 }},
                    status: 'Online',
                    address: '{{ $order->delivery_address ?? "Locating device..." }}',
                    lastSeen: 'Just now',
                    lastUpdated: 'Updated just now',

                    // Device location (Jakarta coordinates as example)
                    deviceLat: {{ $order->delivery_latitude ?? -6.2088 }},
                    deviceLng: {{ $order->delivery_longitude ?? 106.8456 }},

                    init() {
                        this.initMap();
                        this.startLocationUpdates();
                    },

                    initMap() {
                        // Initialize Leaflet map
                        this.map = L.map('map', {
                            zoomControl: false,
                            attributionControl: false,
                        }).setView([this.deviceLat, this.deviceLng], 15);

                        // Add Apple Maps style tile layer
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                        }).addTo(this.map);

                        // Custom icon for device
                        const deviceIcon = L.divIcon({
                            className: 'custom-device-marker',
                            html: `
                            <div class="relative">
                                <div class="absolute -inset-4 rounded-full bg-blue-500 opacity-20 animate-ping"></div>
                                <div class="relative flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        `,
                            iconSize: [48, 48],
                            iconAnchor: [24, 24],
                        });

                        // Add marker
                        this.marker = L.marker([this.deviceLat, this.deviceLng], {
                            icon: deviceIcon,
                        }).addTo(this.map);

                        // Add accuracy circle
                        this.circle = L.circle([this.deviceLat, this.deviceLng], {
                            color: '#3b82f6',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.1,
                            radius: 50,
                        }).addTo(this.map);

                        // Add zoom controls at bottom right
                        L.control
                            .zoom({
                                position: 'bottomright',
                            })
                            .addTo(this.map);
                    },

                    startLocationUpdates() {
                        // Simulate location updates every 10 seconds
                        setInterval(() => {
                            this.updateLastSeen();
                        }, 10000);
                    },

                    updateLastSeen() {
                        const now = new Date();
                        this.lastSeen = 'Just now';
                        this.lastUpdated = 'Updated just now';
                    },

                    playSound() {
                        alert(
                            "🔊 Playing sound on {{ $device->name ?? ($order->device_name ?? "your device") }}...\n\nThe device will emit a loud sound for 2 minutes, even if it's on silent mode.",
                        );
                    },

                    getDirections() {
                        const url = `https://www.google.com/maps/dir/?api=1&destination=${this.deviceLat},${this.deviceLng}`;
                        window.open(url, '_blank');
                    },

                    notifyWhenFound() {
                        alert(
                            "🔔 Notification Set\n\nYou'll be notified when {{ $device->name ?? ($order->device_name ?? "your device") }} comes online.",
                        );
                    },

                    toggleLostMode() {
                        this.lostMode = !this.lostMode;
                        if (this.lostMode) {
                            alert(
                                '🔒 Lost Mode Activated\n\n{{ $device->name ?? ($order->device_name ?? "Your device") }} is now locked. Anyone who finds it will see your contact information on the screen.',
                            );
                        } else {
                            alert(
                                '🔓 Lost Mode Deactivated\n\n{{ $device->name ?? ($order->device_name ?? "Your device") }} is now unlocked and functioning normally.',
                            );
                        }
                    },
                };
            }
        </script>
    </body>
</html>
