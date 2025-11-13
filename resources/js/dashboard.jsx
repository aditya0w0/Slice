import React, { useState, useEffect } from "react";
import {
    Smartphone,
    MapPin,
    User,
    Sun,
    Moon,
    BatteryCharging,
    CalendarDays,
    Database,
    Wifi,
    Truck,
    ChevronRight,
    Bell,
    X,
} from "lucide-react";

const DashboardCard = ({ title, children, className = "", padding = true }) => (
    <div
        className={`overflow-hidden rounded-2xl bg-white/60 shadow-lg backdrop-blur-xl dark:bg-gray-800/60 ${className}`}
    >
        <div className={padding ? "p-6" : "relative"}>
            {title && (
                <h3 className="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                    {title}
                </h3>
            )}
            <div className={padding ? "" : "h-full"}>{children}</div>
        </div>
    </div>
);

const Header = ({
    onToggleDarkMode,
    isDarkMode,
    unreadCount,
    onOpenNotifications,
}) => (
    <header className="flex items-center justify-between p-6">
        {/* App Logo */}
        <div className="flex items-center space-x-2">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600">
                <Smartphone size={16} className="text-white" />
            </div>
            <h1 className="text-xl font-bold text-gray-900 dark:text-white">
                iRent
            </h1>
        </div>

        {/* Actions: Notifications + Dark Mode Toggle */}
        <div className="flex items-center gap-2">
            {/* Notification Bell */}
            <button
                onClick={onOpenNotifications}
                className="relative rounded-lg bg-white/60 p-2 text-gray-700 backdrop-blur-xl transition-colors hover:text-blue-500 dark:bg-gray-800/60 dark:text-gray-300 dark:hover:text-blue-400"
            >
                <Bell size={20} />
                {unreadCount > 0 && (
                    <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                        {unreadCount > 9 ? "9+" : unreadCount}
                    </span>
                )}
            </button>

            {/* Apple-Style Dark/Light Mode Toggle */}
            <button
                onClick={onToggleDarkMode}
                className="rounded-lg bg-white/60 p-2 backdrop-blur-xl transition-colors dark:bg-gray-800/60"
            >
                {isDarkMode ? (
                    <Sun size={20} className="text-yellow-500" />
                ) : (
                    <Moon size={20} className="text-indigo-600" />
                )}
            </button>
        </div>
    </header>
);

const NotificationModal = ({
    isOpen,
    onClose,
    notifications,
    onMarkAsRead,
    onMarkAllAsRead,
    onDelete,
}) => {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 z-50 flex items-start justify-center bg-black/50 p-4 pt-20">
            <div className="w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-gray-800">
                {/* Header */}
                <div className="flex items-center justify-between border-b border-gray-200 p-4 dark:border-gray-700">
                    <h3 className="text-lg font-bold text-gray-900 dark:text-white">
                        Notifications
                    </h3>
                    <div className="flex items-center gap-2">
                        {notifications.length > 0 && (
                            <button
                                onClick={onMarkAllAsRead}
                                className="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400"
                            >
                                Mark all read
                            </button>
                        )}
                        <button
                            onClick={onClose}
                            className="rounded-lg p-1 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <X size={20} />
                        </button>
                    </div>
                </div>

                {/* Notifications List */}
                <div className="max-h-[500px] overflow-y-auto">
                    {notifications.length === 0 ? (
                        <div className="flex flex-col items-center justify-center p-12 text-center">
                            <Bell
                                size={48}
                                className="mb-4 text-gray-300 dark:text-gray-600"
                            />
                            <p className="text-gray-500 dark:text-gray-400">
                                No notifications yet
                            </p>
                        </div>
                    ) : (
                        notifications.map((notif) => (
                            <div
                                key={notif.id}
                                className={`border-b border-gray-100 p-4 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-700/50 ${
                                    !notif.is_read
                                        ? "bg-blue-50/50 dark:bg-blue-900/20"
                                        : ""
                                }`}
                            >
                                <div className="flex items-start gap-3">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2">
                                            <h4 className="font-semibold text-gray-900 dark:text-white">
                                                {notif.title}
                                            </h4>
                                            {!notif.is_read && (
                                                <span className="h-2 w-2 rounded-full bg-blue-600"></span>
                                            )}
                                        </div>
                                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-300">
                                            {notif.message}
                                        </p>
                                        <p className="mt-2 text-xs text-gray-400">
                                            {new Date(
                                                notif.created_at,
                                            ).toLocaleString()}
                                        </p>
                                        <div className="mt-2 flex items-center gap-2">
                                            {!notif.is_read && (
                                                <button
                                                    onClick={() =>
                                                        onMarkAsRead(notif.id)
                                                    }
                                                    className="text-xs font-medium text-blue-600 hover:text-blue-700"
                                                >
                                                    Mark as read
                                                </button>
                                            )}
                                            {notif.action_url && (
                                                <a
                                                    href={notif.action_url}
                                                    className="text-xs font-medium text-blue-600 hover:text-blue-700"
                                                >
                                                    View details
                                                </a>
                                            )}
                                            <button
                                                onClick={() =>
                                                    onDelete(notif.id)
                                                }
                                                className="text-xs font-medium text-red-600 hover:text-red-700"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </div>
    );
};

const UserProfileCard = ({ userName, userBalance, isTrusted }) => {
    // Get user avatar dynamically from uploaded avatar or navbar
    const [userAvatar, setUserAvatar] = React.useState("");

    React.useEffect(() => {
        // Try to get avatar from navbar
        const navbarAvatar = document.querySelector('img[alt="avatar"]');
        if (navbarAvatar && navbarAvatar.src) {
            setUserAvatar(navbarAvatar.src);
        } else {
            // Fallback to generated avatar
            setUserAvatar(
                "https://ui-avatars.com/api/?name=" +
                    encodeURIComponent(userName) +
                    "&background=3b82f6&color=fff",
            );
        }
    }, [userName]);

    return (
        <a href="/settings" className="block">
            <DashboardCard className="h-64 cursor-pointer transition-all hover:shadow-lg md:col-span-1">
                <div className="flex h-full flex-col items-center justify-center text-center">
                    {/* Avatar with trusted badge */}
                    <div className="relative mb-3">
                        {isTrusted && (
                            <div className="absolute -inset-1 rounded-full bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 opacity-75 blur"></div>
                        )}
                        <div className="relative">
                            <img
                                src={userAvatar}
                                alt={userName}
                                className={`h-20 w-20 rounded-full object-cover shadow-lg ${isTrusted ? "ring-4 ring-blue-600" : "ring-4 ring-blue-100"}`}
                            />
                            {isTrusted && (
                                <div className="absolute -right-1 -bottom-1 flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 ring-2 ring-white">
                                    <svg
                                        className="h-3.5 w-3.5 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fillRule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clipRule="evenodd"
                                        />
                                    </svg>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Name with verified badge text */}
                    <div className="flex items-center gap-1.5">
                        <h4 className="text-lg font-semibold text-gray-900 dark:text-white">
                            {userName}
                        </h4>
                        {isTrusted && (
                            <span className="text-xs font-medium text-blue-600">
                                Verified
                            </span>
                        )}
                    </div>

                    {/* Balance - Clickable */}
                    <a href="/balance" className="group mt-4 block">
                        <p className="text-xs font-medium text-gray-500 transition-all group-hover:text-blue-500 dark:text-gray-400">
                            Balance
                        </p>
                        <div className="flex items-center gap-2">
                            <p className="text-2xl font-semibold text-gray-900 transition-all group-hover:text-blue-600 dark:text-white">
                                Rp {userBalance.toLocaleString("id-ID")}
                            </p>
                            <svg
                                className="h-5 w-5 text-gray-400 transition-all group-hover:translate-x-1 group-hover:text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </div>
                    </a>
                </div>
            </DashboardCard>
        </a>
    );
};

const RentedDeviceCard = ({
    isDelivering,
    onMarkAsReceived,
    deviceName,
    orderId,
}) => {
    if (isDelivering) {
        return (
            <DashboardCard className="h-64 md:col-span-2" padding={false}>
                <div className="relative h-64 overflow-hidden rounded-2xl bg-gray-50">
                    {/* Road with lanes */}
                    <div className="absolute inset-0 flex items-center bg-gradient-to-b from-gray-100 via-gray-200 to-gray-100">
                        {/* Road center line */}
                        <div className="absolute top-1/2 right-0 left-0 h-1 -translate-y-1/2 bg-yellow-300 opacity-50"></div>
                    </div>

                    {/* Warehouse icon (start) - LEFT */}
                    <div className="absolute top-1/2 left-6 z-10 -translate-y-1/2">
                        <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-600 shadow-xl">
                            <svg
                                className="h-7 w-7 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                />
                            </svg>
                        </div>
                        <div className="mt-1 text-center text-xs font-medium text-gray-600">
                            Warehouse
                        </div>
                    </div>

                    {/* Home icon (destination) - RIGHT */}
                    <div className="absolute top-1/2 right-6 z-10 -translate-y-1/2">
                        <div className="flex h-14 w-14 items-center justify-center rounded-xl bg-green-600 shadow-xl">
                            <svg
                                className="h-7 w-7 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                />
                            </svg>
                        </div>
                        <div className="mt-1 text-center text-xs font-medium text-gray-600">
                            Your Home
                        </div>
                    </div>

                    {/* Animated delivery truck - Amazon style */}
                    <div className="amazon-truck-move absolute top-1/2 -translate-y-1/2">
                        <div className="relative">
                            {/* Truck body */}
                            <svg
                                className="h-16 w-20"
                                viewBox="0 0 80 64"
                                fill="none"
                            >
                                {/* Container/cargo area */}
                                <rect
                                    x="8"
                                    y="12"
                                    width="48"
                                    height="32"
                                    fill="#3b82f6"
                                    rx="2"
                                />
                                <rect
                                    x="8"
                                    y="12"
                                    width="48"
                                    height="32"
                                    stroke="#2563eb"
                                    strokeWidth="2"
                                    rx="2"
                                />

                                {/* Amazon smile arrow */}
                                <path
                                    d="M 20 24 Q 32 28, 44 24"
                                    stroke="#fbbf24"
                                    strokeWidth="2.5"
                                    strokeLinecap="round"
                                />
                                <path
                                    d="M 42 23 L 44 24 L 43 26"
                                    stroke="#fbbf24"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    fill="none"
                                />

                                {/* Cab */}
                                <path
                                    d="M 56 20 L 56 44 L 72 44 L 72 28 L 64 20 Z"
                                    fill="#2563eb"
                                />
                                <path
                                    d="M 56 20 L 56 44 L 72 44 L 72 28 L 64 20 Z"
                                    stroke="#1e40af"
                                    strokeWidth="2"
                                />

                                {/* Window */}
                                <rect
                                    x="60"
                                    y="24"
                                    width="8"
                                    height="8"
                                    fill="#93c5fd"
                                    rx="1"
                                />

                                {/* Wheels */}
                                <circle cx="20" cy="48" r="8" fill="#374151" />
                                <circle cx="20" cy="48" r="5" fill="#6b7280" />
                                <circle cx="52" cy="48" r="8" fill="#374151" />
                                <circle cx="52" cy="48" r="5" fill="#6b7280" />

                                {/* Wheel motion blur */}
                                <circle
                                    cx="20"
                                    cy="48"
                                    r="8"
                                    fill="none"
                                    stroke="#9ca3af"
                                    strokeWidth="2"
                                    opacity="0.3"
                                    className="wheel-spin"
                                />
                                <circle
                                    cx="52"
                                    cy="48"
                                    r="8"
                                    fill="none"
                                    stroke="#9ca3af"
                                    strokeWidth="2"
                                    opacity="0.3"
                                    className="wheel-spin"
                                />
                            </svg>

                            {/* Dust/speed lines */}
                            <div className="absolute top-1/2 -left-8 -translate-y-1/2">
                                <div className="speed-lines">
                                    <div className="mb-2 h-0.5 w-6 bg-gray-400 opacity-50"></div>
                                    <div className="mb-2 h-0.5 w-4 bg-gray-400 opacity-30"></div>
                                    <div className="h-0.5 w-5 bg-gray-400 opacity-40"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Bottom info */}
                    <div className="absolute right-0 bottom-0 left-0 bg-white/95 p-4 backdrop-blur">
                        <div className="flex items-center justify-between">
                            <div>
                                <h3 className="font-semibold text-gray-900">
                                    {deviceName}
                                </h3>
                                <p className="text-sm text-gray-600">
                                    Out for delivery - Arriving today
                                </p>
                            </div>
                            <a
                                href={`/delivery/track/${orderId}`}
                                className="rounded-xl bg-blue-500 px-6 py-2 text-sm font-semibold text-white transition-all hover:bg-blue-600 active:scale-95"
                            >
                                Track
                            </a>
                        </div>
                    </div>
                </div>
            </DashboardCard>
        );
    }

    // When device is received - clickable card that goes to Find My Device
    return (
        <a href={`/find-device/${orderId}`} className="block md:col-span-2">
            <DashboardCard padding={false}>
                <div className="relative h-64 w-full overflow-hidden rounded-2xl transition-all hover:scale-[1.02]">
                    <img
                        src="https://placehold.co/800x400/f0f0f0/3b82f6?text=iPhone+15"
                        alt="Rented Device"
                        className="h-full w-full object-cover"
                        onError={(e) => {
                            e.target.onerror = null;
                            e.target.src =
                                "https://placehold.co/800x400/f0f0f0/cc0000?text=Device";
                        }}
                    />

                    {/* Overlay Gradient */}
                    <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-black/30 to-black/70"></div>

                    {/* Content - Top Right */}
                    <div className="absolute top-6 right-6 text-right">
                        <div className="text-white">
                            <h4 className="text-2xl font-bold">{deviceName}</h4>
                            <div className="mt-2 flex items-center justify-end space-x-2">
                                <span className="font-medium text-green-400">
                                    Active
                                </span>
                                <span className="h-2 w-2 rounded-full bg-green-500"></span>
                            </div>

                            <div className="mt-4 space-y-2 text-sm">
                                {/* Battery Info */}
                                <div className="flex w-full items-center justify-end space-x-2 rounded-lg bg-black/20 p-2 backdrop-blur-sm">
                                    <span>92%</span>
                                    <svg
                                        className="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M13 10V3L4 14h7v7l9-11h-7z"
                                        />
                                    </svg>
                                </div>

                                {/* Location Indicator - Click hint */}
                                <div className="flex w-full items-center justify-end space-x-2 rounded-lg bg-black/20 p-2 backdrop-blur-sm">
                                    <span className="text-xs">
                                        Tap to locate
                                    </span>
                                    <svg
                                        className="h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </DashboardCard>
        </a>
    );
};

const MapCard = () => {
    const [locationName, setLocationName] = useState("Detecting location...");
    const [weather, setWeather] = useState(null);

    useEffect(() => {
        let mounted = true;

        const getLocation = async () => {
            try {
                // Try IP-based location first (faster)
                const ipResponse = await fetch("https://ipapi.co/json/");
                const ipData = await ipResponse.json();

                if (!mounted) return;

                if (ipData.city && ipData.country_name) {
                    setLocationName(`${ipData.city}, ${ipData.country_name}`);

                    // Get weather
                    if (ipData.latitude && ipData.longitude) {
                        const weatherResponse = await fetch(
                            `https://api.open-meteo.com/v1/forecast?latitude=${ipData.latitude}&longitude=${ipData.longitude}&current_weather=true`,
                        );
                        const weatherData = await weatherResponse.json();

                        if (mounted && weatherData.current_weather) {
                            setWeather({
                                temp: Math.round(
                                    weatherData.current_weather.temperature,
                                ),
                                code: weatherData.current_weather.weathercode,
                            });
                        }
                    }
                } else {
                    setLocationName("Location unavailable");
                }
            } catch (error) {
                console.error("Location error:", error);
                if (mounted) {
                    setLocationName("Location unavailable");
                }
            }
        };

        getLocation();

        return () => {
            mounted = false;
        };
    }, []);

    const getWeatherIcon = (code) => {
        if (code === 0) return "☀️";
        if (code <= 3) return "⛅";
        if (code <= 48) return "🌫️";
        if (code <= 67) return "🌧️";
        if (code <= 77) return "🌨️";
        if (code <= 99) return "⛈️";
        return "🌤️";
    };

    return (
        <DashboardCard className="h-64 md:col-span-2" padding={false}>
            <div className="relative h-64 w-full overflow-hidden rounded-2xl bg-[#f5f5f0]">
                {/* Apple Maps Style Background */}
                <svg
                    className="absolute inset-0 h-full w-full"
                    viewBox="0 0 800 400"
                    preserveAspectRatio="xMidYMid slice"
                >
                    {/* Main roads */}
                    <path
                        d="M 0,150 L 800,150"
                        stroke="#e8e8e3"
                        strokeWidth="8"
                    />
                    <path
                        d="M 0,250 L 800,250"
                        stroke="#e8e8e3"
                        strokeWidth="8"
                    />
                    <path
                        d="M 300,0 L 300,400"
                        stroke="#e8e8e3"
                        strokeWidth="8"
                    />
                    <path
                        d="M 500,0 L 500,400"
                        stroke="#e8e8e3"
                        strokeWidth="8"
                    />

                    {/* Smaller streets */}
                    <path
                        d="M 0,100 L 800,100"
                        stroke="#efefea"
                        strokeWidth="4"
                    />
                    <path
                        d="M 0,200 L 800,200"
                        stroke="#efefea"
                        strokeWidth="4"
                    />
                    <path
                        d="M 0,300 L 800,300"
                        stroke="#efefea"
                        strokeWidth="4"
                    />
                    <path
                        d="M 150,0 L 150,400"
                        stroke="#efefea"
                        strokeWidth="4"
                    />
                    <path
                        d="M 400,0 L 400,400"
                        stroke="#efefea"
                        strokeWidth="4"
                    />
                    <path
                        d="M 650,0 L 650,400"
                        stroke="#efefea"
                        strokeWidth="4"
                    />

                    {/* Green spaces (parks) */}
                    <rect
                        x="50"
                        y="50"
                        width="80"
                        height="70"
                        fill="#c5e8c1"
                        opacity="0.6"
                        rx="4"
                    />
                    <rect
                        x="550"
                        y="270"
                        width="90"
                        height="80"
                        fill="#c5e8c1"
                        opacity="0.6"
                        rx="4"
                    />

                    {/* Buildings (light rectangles) */}
                    <rect
                        x="160"
                        y="110"
                        width="120"
                        height="80"
                        fill="#e0e0db"
                        opacity="0.5"
                        rx="2"
                    />
                    <rect
                        x="320"
                        y="160"
                        width="160"
                        height="80"
                        fill="#e0e0db"
                        opacity="0.5"
                        rx="2"
                    />
                    <rect
                        x="510"
                        y="50"
                        width="100"
                        height="90"
                        fill="#e0e0db"
                        opacity="0.5"
                        rx="2"
                    />
                    <rect
                        x="180"
                        y="260"
                        width="110"
                        height="120"
                        fill="#e0e0db"
                        opacity="0.5"
                        rx="2"
                    />
                    <rect
                        x="510"
                        y="160"
                        width="130"
                        height="100"
                        fill="#e0e0db"
                        opacity="0.5"
                        rx="2"
                    />

                    {/* Water bodies (blue) */}
                    <ellipse
                        cx="700"
                        cy="100"
                        rx="60"
                        ry="40"
                        fill="#b3d9ff"
                        opacity="0.5"
                    />
                </svg>

                {/* Apple-style location pin - centered */}
                <div className="absolute top-1/2 left-1/2 z-10 -translate-x-1/2 -translate-y-full">
                    <div className="relative">
                        {/* Pin shadow */}
                        <div className="absolute top-full left-1/2 h-2 w-8 -translate-x-1/2 rounded-full bg-black/20 blur-sm"></div>

                        {/* Pin */}
                        <div className="relative">
                            <svg
                                width="44"
                                height="56"
                                viewBox="0 0 44 56"
                                fill="none"
                            >
                                {/* Outer glow */}
                                <circle
                                    cx="22"
                                    cy="22"
                                    r="20"
                                    fill="#007AFF"
                                    opacity="0.2"
                                />

                                {/* Main pin shape */}
                                <path
                                    d="M22 0C12.626 0 5 7.626 5 17C5 29.75 22 52 22 52C22 52 39 29.75 39 17C39 7.626 31.374 0 22 0Z"
                                    fill="#007AFF"
                                />

                                {/* Inner white circle */}
                                <circle cx="22" cy="17" r="8" fill="white" />

                                {/* Inner blue dot */}
                                <circle cx="22" cy="17" r="4" fill="#007AFF" />
                            </svg>
                        </div>

                        {/* Pulsing ring animation */}
                        <div className="absolute top-[22px] left-1/2 -translate-x-1/2 -translate-y-1/2">
                            <div className="h-14 w-14 animate-ping rounded-full border-4 border-blue-500 opacity-30"></div>
                        </div>
                    </div>
                </div>

                {/* Bottom info bar - Apple style */}
                <div className="absolute right-0 bottom-0 left-0 border-t border-gray-200/50 bg-white/95 backdrop-blur-xl">
                    <div className="px-4 py-3">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center gap-3">
                                <div className="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100">
                                    <MapPin
                                        size={18}
                                        className="text-gray-600"
                                    />
                                </div>
                                <div>
                                    <p className="text-xs font-medium text-gray-500">
                                        Current Location
                                    </p>
                                    <p className="text-sm font-semibold text-gray-900">
                                        {locationName}
                                    </p>
                                </div>
                            </div>

                            {weather && (
                                <div className="flex items-center gap-2 rounded-full bg-gray-100 px-3 py-1.5">
                                    <span className="text-lg">
                                        {getWeatherIcon(weather.code)}
                                    </span>
                                    <span className="text-sm font-semibold text-gray-900">
                                        {weather.temp}°C
                                    </span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </DashboardCard>
    );
};

const QuickActionsCard = () => {
    const [deviceStats, setDeviceStats] = useState({
        storage: { used: 0, total: 0, available: true },
        battery: { level: 0, available: true },
        memory: { used: 0, total: 0, available: false },
    });

    useEffect(() => {
        // Get real device info from browser
        const updateDeviceInfo = async () => {
            // Storage API
            if ("storage" in navigator && "estimate" in navigator.storage) {
                try {
                    const estimate = await navigator.storage.estimate();
                    const usedGB = (estimate.usage / 1024 ** 3).toFixed(1);
                    const totalGB = (estimate.quota / 1024 ** 3).toFixed(0);
                    setDeviceStats((prev) => ({
                        ...prev,
                        storage: {
                            used: parseFloat(usedGB),
                            total: parseInt(totalGB),
                            available: true,
                        },
                    }));
                } catch (err) {
                    console.log("Storage API error:", err);
                    setDeviceStats((prev) => ({
                        ...prev,
                        storage: { used: 0, total: 0, available: false },
                    }));
                }
            } else {
                setDeviceStats((prev) => ({
                    ...prev,
                    storage: { used: 0, total: 0, available: false },
                }));
            }

            // Battery API
            if ("getBattery" in navigator) {
                try {
                    const battery = await navigator.getBattery();
                    setDeviceStats((prev) => ({
                        ...prev,
                        battery: {
                            level: Math.round(battery.level * 100),
                            available: true,
                        },
                    }));

                    // Update battery on change
                    battery.addEventListener("levelchange", () => {
                        setDeviceStats((prev) => ({
                            ...prev,
                            battery: {
                                level: Math.round(battery.level * 100),
                                available: true,
                            },
                        }));
                    });
                } catch (err) {
                    console.log("Battery API error:", err);
                    setDeviceStats((prev) => ({
                        ...prev,
                        battery: { level: 0, available: false },
                    }));
                }
            } else {
                setDeviceStats((prev) => ({
                    ...prev,
                    battery: { level: 0, available: false },
                }));
            }

            // Memory API (experimental - Chrome only)
            if ("memory" in performance) {
                try {
                    const memoryInfo = performance.memory;
                    const usedMB = (
                        memoryInfo.usedJSHeapSize /
                        1024 ** 2
                    ).toFixed(0);
                    const totalMB = (
                        memoryInfo.jsHeapSizeLimit /
                        1024 ** 2
                    ).toFixed(0);
                    setDeviceStats((prev) => ({
                        ...prev,
                        memory: {
                            used: parseInt(usedMB),
                            total: parseInt(totalMB),
                            available: true,
                        },
                    }));
                } catch (err) {
                    console.log("Memory API error:", err);
                }
            }
        };

        updateDeviceInfo();
        const interval = setInterval(updateDeviceInfo, 30000); // Update every 30s

        return () => clearInterval(interval);
    }, []);

    return (
        <DashboardCard className="h-64 md:col-span-1">
            <div className="flex h-full flex-col justify-around py-2">
                {/* Storage - Apple Style Compact */}
                <div className="group cursor-pointer rounded-xl p-3 transition-all hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <div className="rounded-lg bg-blue-100 p-2 dark:bg-blue-900/30">
                                <Database
                                    size={18}
                                    className="text-blue-600 dark:text-blue-400"
                                />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Storage
                                </p>
                                {deviceStats.storage.available ? (
                                    <p className="text-sm font-semibold text-gray-900 dark:text-white">
                                        {deviceStats.storage.used} GB used
                                    </p>
                                ) : (
                                    <p className="text-xs text-gray-400 dark:text-gray-500">
                                        Not available
                                    </p>
                                )}
                            </div>
                        </div>
                        {deviceStats.storage.available && (
                            <div className="text-right">
                                <p className="text-xs text-gray-400">
                                    {deviceStats.storage.total} GB
                                </p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Battery - Apple Style Compact */}
                <div className="group cursor-pointer rounded-xl p-3 transition-all hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <div
                                className={`rounded-lg p-2 ${
                                    !deviceStats.battery.available
                                        ? "bg-gray-100 dark:bg-gray-700"
                                        : deviceStats.battery.level > 50
                                          ? "bg-green-100 dark:bg-green-900/30"
                                          : deviceStats.battery.level > 20
                                            ? "bg-yellow-100 dark:bg-yellow-900/30"
                                            : "bg-red-100 dark:bg-red-900/30"
                                }`}
                            >
                                <BatteryCharging
                                    size={18}
                                    className={
                                        !deviceStats.battery.available
                                            ? "text-gray-400"
                                            : deviceStats.battery.level > 50
                                              ? "text-green-600 dark:text-green-400"
                                              : deviceStats.battery.level > 20
                                                ? "text-yellow-600 dark:text-yellow-400"
                                                : "text-red-600 dark:text-red-400"
                                    }
                                />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Battery
                                </p>
                                {deviceStats.battery.available ? (
                                    <p className="text-sm font-semibold text-gray-900 dark:text-white">
                                        {deviceStats.battery.level}%
                                    </p>
                                ) : (
                                    <p className="text-xs text-gray-400 dark:text-gray-500">
                                        Not available
                                    </p>
                                )}
                            </div>
                        </div>
                        {deviceStats.battery.available && (
                            <div className="h-2 w-16 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                <div
                                    className={`h-full rounded-full transition-all duration-500 ${
                                        deviceStats.battery.level > 50
                                            ? "bg-green-500"
                                            : deviceStats.battery.level > 20
                                              ? "bg-yellow-500"
                                              : "bg-red-500"
                                    }`}
                                    style={{
                                        width: `${deviceStats.battery.level}%`,
                                    }}
                                ></div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Memory - Apple Style Compact */}
                <div className="group cursor-pointer rounded-xl p-3 transition-all hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div className="flex items-center justify-between">
                        <div className="flex items-center space-x-3">
                            <div className="rounded-lg bg-gray-100 p-2 dark:bg-gray-700">
                                <Wifi
                                    size={18}
                                    className="text-gray-600 dark:text-gray-400"
                                />
                            </div>
                            <div>
                                <p className="text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Memory
                                </p>
                                {deviceStats.memory.available ? (
                                    <p className="text-sm font-semibold text-gray-900 dark:text-white">
                                        {deviceStats.memory.used} MB
                                    </p>
                                ) : (
                                    <p className="text-xs text-gray-400 dark:text-gray-500">
                                        Not available
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardCard>
    );
};

const QuickListCard = () => {
    return (
        <DashboardCard className="md:col-span-3" padding={false}>
            <a href="/pricing" className="block h-48 w-full">
                <div className="relative h-full overflow-hidden rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600">
                    <div className="absolute inset-0 flex items-center justify-between px-8">
                        <div className="max-w-xl text-white">
                            <div className="mb-2 inline-flex items-center space-x-2 rounded-full bg-white/20 px-3 py-1">
                                <div className="h-2 w-2 animate-pulse rounded-full bg-white"></div>
                                <span className="text-xs font-semibold">
                                    Premium Plans
                                </span>
                            </div>
                            <h4 className="mb-2 text-3xl font-bold">
                                Upgrade to Premium
                            </h4>
                            <p className="text-sm text-white/90">
                                Unlock advanced AI features and benefits
                            </p>
                        </div>

                        <div className="flex shrink-0 items-center justify-center">
                            <div className="rounded-2xl bg-white/10 p-4 backdrop-blur-sm">
                                <ChevronRight
                                    size={32}
                                    className="text-white"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </DashboardCard>
    );
};

/**
 * Komponen Aplikasi Utama
// ... (Kode komponen App tidak berubah) ...
 */
export default function App() {
    const [isDarkMode, setIsDarkMode] = useState(false); // Default to light mode (Apple style)

    // Read data from Laravel
    const rootElement = document.getElementById("dashboard-root");
    const isDeliveredFromServer =
        rootElement?.getAttribute("data-is-delivered") === "true";
    const deviceNameFromServer =
        rootElement?.getAttribute("data-device-name") || "Your Device";
    const orderIdFromServer = rootElement?.getAttribute("data-order-id");
    const userName = rootElement?.getAttribute("data-user-name") || "User";
    const userBalance = parseInt(
        rootElement?.getAttribute("data-user-balance") || "0",
    );
    const isTrusted = rootElement?.getAttribute("data-is-trusted") === "true";

    // STATE
    const [isDelivering, setIsDelivering] = useState(!isDeliveredFromServer);
    const [deviceName] = useState(deviceNameFromServer);
    const [orderId] = useState(orderIdFromServer);
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [isNotificationModalOpen, setIsNotificationModalOpen] =
        useState(false);

    // Fetch notifications
    useEffect(() => {
        fetchNotifications();
        fetchUnreadCount();

        // Poll for new notifications every 30 seconds
        const interval = setInterval(() => {
            fetchUnreadCount();
        }, 30000);

        return () => clearInterval(interval);
    }, []);

    const fetchNotifications = async () => {
        try {
            const response = await fetch("/notifications");
            const data = await response.json();
            setNotifications(data.notifications.data || []);
            setUnreadCount(data.unread_count);
        } catch (error) {
            console.error("Error fetching notifications:", error);
        }
    };

    const fetchUnreadCount = async () => {
        try {
            const response = await fetch("/notifications/unread-count");
            const data = await response.json();
            setUnreadCount(data.count);
        } catch (error) {
            console.error("Error fetching unread count:", error);
        }
    };

    const handleMarkAsRead = async (id) => {
        try {
            const response = await fetch(`/notifications/${id}/read`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
            });

            if (response.ok) {
                setNotifications(
                    notifications.map((n) =>
                        n.id === id ? { ...n, is_read: true } : n,
                    ),
                );
                setUnreadCount(Math.max(0, unreadCount - 1));
            }
        } catch (error) {
            console.error("Error marking notification as read:", error);
        }
    };

    const handleMarkAllAsRead = async () => {
        try {
            const response = await fetch("/notifications/read-all", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
            });

            if (response.ok) {
                setNotifications(
                    notifications.map((n) => ({ ...n, is_read: true })),
                );
                setUnreadCount(0);
            }
        } catch (error) {
            console.error("Error marking all as read:", error);
        }
    };

    const handleDeleteNotification = async (id) => {
        try {
            const response = await fetch(`/notifications/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN":
                        document.querySelector('meta[name="csrf-token"]')
                            ?.content || "",
                },
            });

            if (response.ok) {
                const deleted = notifications.find((n) => n.id === id);
                setNotifications(notifications.filter((n) => n.id !== id));
                if (deleted && !deleted.is_read) {
                    setUnreadCount(Math.max(0, unreadCount - 1));
                }
            }
        } catch (error) {
            console.error("Error deleting notification:", error);
        }
    };

    const toggleDarkMode = () => setIsDarkMode(!isDarkMode);

    const handleMarkAsReceived = () => {
        setIsDelivering(false);
    };

    const handleOpenNotifications = () => {
        setIsNotificationModalOpen(true);
        fetchNotifications(); // Refresh when opening
    };

    return (
        <div className={isDarkMode ? "dark" : ""}>
            <div className="min-h-screen bg-gray-100 text-gray-900 transition-colors duration-300 dark:bg-gray-900 dark:text-white">
                <main className="mx-auto max-w-7xl">
                    <Header
                        onToggleDarkMode={toggleDarkMode}
                        isDarkMode={isDarkMode}
                        unreadCount={unreadCount}
                        onOpenNotifications={handleOpenNotifications}
                    />

                    <NotificationModal
                        isOpen={isNotificationModalOpen}
                        onClose={() => setIsNotificationModalOpen(false)}
                        notifications={notifications}
                        onMarkAsRead={handleMarkAsRead}
                        onMarkAllAsRead={handleMarkAllAsRead}
                        onDelete={handleDeleteNotification}
                    />

                    {/* Grid Dasbor Utama */}
                    <div className="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">
                        {/* KYC Verification Banner - Only show if not verified */}
                        {!isTrusted && (
                            <div className="md:col-span-3">
                                <a href="/kyc/submit" className="group block">
                                    <div className="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 p-6 shadow-lg transition-all hover:scale-[1.02] hover:shadow-2xl">
                                        {/* Background Pattern */}
                                        <div className="absolute inset-0 opacity-10">
                                            <div
                                                className="absolute inset-0"
                                                style={{
                                                    backgroundImage:
                                                        "radial-gradient(circle at 2px 2px, white 1px, transparent 0)",
                                                    backgroundSize: "32px 32px",
                                                }}
                                            ></div>
                                        </div>

                                        {/* Content */}
                                        <div className="relative flex items-center justify-between">
                                            <div className="flex items-center gap-4">
                                                {/* Icon */}
                                                <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/20 backdrop-blur-sm">
                                                    <svg
                                                        className="h-7 w-7 text-white"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                                        />
                                                    </svg>
                                                </div>

                                                {/* Text */}
                                                <div className="text-white">
                                                    <div className="mb-1 flex items-center gap-2">
                                                        <h3 className="text-xl font-bold">
                                                            Verify Your Identity
                                                        </h3>
                                                        <span className="rounded-full bg-yellow-400 px-2 py-0.5 text-xs font-semibold text-yellow-900">
                                                            Required
                                                        </span>
                                                    </div>
                                                    <p className="text-sm text-white/90">
                                                        Complete KYC
                                                        verification to unlock
                                                        full account features
                                                        and higher rental limits
                                                    </p>
                                                </div>
                                            </div>

                                            {/* CTA Button */}
                                            <div className="flex shrink-0 items-center gap-3">
                                                <div className="hidden text-right text-xs text-white/80 sm:block">
                                                    <div className="font-medium">
                                                        Quick & Secure
                                                    </div>
                                                    <div>~3 minutes</div>
                                                </div>
                                                <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-blue-600 transition-transform group-hover:scale-110">
                                                    <ChevronRight
                                                        size={24}
                                                        className="font-bold"
                                                    />
                                                </div>
                                            </div>
                                        </div>

                                        {/* Bottom Progress Indicator */}
                                        <div className="mt-4 flex items-center gap-2">
                                            <div className="flex items-center gap-1.5">
                                                <div className="h-1.5 w-8 rounded-full bg-white/30"></div>
                                                <div className="h-1.5 w-8 rounded-full bg-white/30"></div>
                                                <div className="h-1.5 w-8 rounded-full bg-white/30"></div>
                                            </div>
                                            <span className="text-xs text-white/70">
                                                0% Complete
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        )}

                        {/* Baris 1 */}
                        <RentedDeviceCard
                            isDelivering={isDelivering}
                            onMarkAsReceived={handleMarkAsReceived}
                            deviceName={deviceName}
                            orderId={orderId}
                        />
                        <UserProfileCard
                            userName={userName}
                            userBalance={userBalance}
                            isTrusted={isTrusted}
                        />

                        {/* Baris 2 */}
                        <QuickActionsCard />
                        <MapCard />

                        {/* Baris 3 */}
                        <QuickListCard />
                    </div>
                </main>
            </div>
        </div>
    );
}

// Mount React app
import { createRoot } from "react-dom/client";
const rootElement = document.getElementById("dashboard-root");
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<App />);
}
