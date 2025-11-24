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
    MessageCircle,
    X,
} from "lucide-react";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
} from "@/components/ui/dialog";
import { Separator } from "@/components/ui/separator";

const DashboardCard = ({ title, children, className = "", padding = true }) => (
    <div
        className={`overflow-hidden rounded-xl border border-white/20 bg-white/70 shadow-lg shadow-black/5 backdrop-blur-lg transition-all duration-300 hover:shadow-xl hover:shadow-black/10 dark:border-gray-700/50 dark:bg-gray-900/70 dark:shadow-black/20 ${className}`}
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
    isNotificationModalOpen,
    onCloseNotifications,
    notifications,
    onMarkAsRead,
    onMarkAllAsRead,
    onDelete,
}) => (
    <header className="flex items-center justify-between p-6">
        {/* App Logo */}
        <div className="flex items-center space-x-2">
            <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-slate-700 to-slate-800 shadow-sm">
                <Smartphone size={16} className="text-white" />
            </div>
            <h1 className="text-xl font-bold text-gray-900 dark:text-white">
                iRent
            </h1>
        </div>

        {/* Actions: Chat + Notifications + Dark Mode Toggle */}
        <div className="flex items-center gap-2">
            {/* Chat Button */}
            <Button
                variant="ghost"
                size="icon"
                onClick={() => (window.location.href = "/chat")}
                className="bg-white/80 text-gray-600 backdrop-blur-sm hover:bg-white/90 hover:text-gray-900 dark:bg-gray-800/80 dark:text-gray-400 dark:hover:bg-gray-700/90 dark:hover:text-gray-200"
                title="Support Chat"
            >
                <MessageCircle size={20} />
            </Button>

            {/* Notification Bell */}
            <div className="relative">
                <Button
                    variant="ghost"
                    size="icon"
                    onClick={onOpenNotifications}
                    className="relative bg-white/80 text-gray-600 backdrop-blur-sm hover:bg-white/90 hover:text-gray-900 dark:bg-gray-800/80 dark:text-gray-400 dark:hover:bg-gray-700/90 dark:hover:text-gray-200"
                >
                    <Bell size={20} />
                    {unreadCount > 0 && (
                        <span className="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                            {unreadCount > 9 ? "9+" : unreadCount}
                        </span>
                    )}
                </Button>

                {/* Notification Dropdown - positioned relative to the button */}
                <NotificationDropdown
                    isOpen={isNotificationModalOpen}
                    onClose={onCloseNotifications}
                    notifications={notifications}
                    onMarkAsRead={onMarkAsRead}
                    onMarkAllAsRead={onMarkAllAsRead}
                    onDelete={onDelete}
                />
            </div>

            {/* Apple-Style Dark/Light Mode Toggle */}
            <Button
                variant="ghost"
                size="icon"
                onClick={onToggleDarkMode}
                className="bg-white/80 backdrop-blur-sm hover:bg-white/90 dark:bg-gray-800/80 dark:hover:bg-gray-700/90"
            >
                {isDarkMode ? (
                    <Sun size={20} className="text-yellow-500" />
                ) : (
                    <Moon size={20} className="text-indigo-600" />
                )}
            </Button>
        </div>
    </header>
);

const NotificationDropdown = ({
    isOpen,
    onClose,
    notifications,
    onMarkAsRead,
    onMarkAllAsRead,
    onDelete,
}) => {
    React.useEffect(() => {
        const handleClickOutside = (event) => {
            if (isOpen && !event.target.closest(".notification-dropdown")) {
                onClose();
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, [isOpen, onClose]);

    if (!isOpen) return null;

    return (
        <div className="notification-dropdown absolute top-full right-0 z-50 mt-2 w-96 rounded-xl border border-white/20 bg-white/90 shadow-xl shadow-black/10 backdrop-blur-lg dark:border-gray-700/50 dark:bg-gray-900/90">
            <div className="border-b border-gray-200/50 p-4 dark:border-gray-700/50">
                <div className="flex items-center justify-between">
                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                        Notifications
                    </h3>
                    {notifications.length > 0 && (
                        <Button
                            variant="ghost"
                            size="sm"
                            onClick={onMarkAllAsRead}
                            className="text-xs text-slate-600 hover:text-slate-700"
                        >
                            Mark all read
                        </Button>
                    )}
                </div>
            </div>

            <div className="max-h-96 overflow-y-auto">
                {notifications.length === 0 ? (
                    <div className="flex flex-col items-center justify-center p-8 text-center">
                        <Bell size={32} className="mb-3 text-gray-400" />
                        <p className="text-sm text-gray-500 dark:text-gray-400">
                            No notifications yet
                        </p>
                        <p className="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            We'll notify you when something important happens.
                        </p>
                    </div>
                ) : (
                    <div className="p-2">
                        {notifications.map((notif) => (
                            <div
                                key={notif.id}
                                className={`mb-2 rounded-lg p-3 transition-all ${
                                    notif.is_read
                                        ? "bg-gray-50/50 dark:bg-gray-800/50"
                                        : "border-l-4 border-slate-600 bg-slate-50/70 dark:bg-slate-900/50"
                                }`}
                            >
                                <div className="flex items-start justify-between gap-3">
                                    <div className="min-w-0 flex-1">
                                        <p className="truncate text-sm font-medium text-gray-900 dark:text-white">
                                            {notif.title}
                                        </p>
                                        <p className="mt-1 line-clamp-2 text-xs text-gray-600 dark:text-gray-300">
                                            {notif.message}
                                        </p>
                                        <p className="mt-2 text-xs text-gray-400 dark:text-gray-500">
                                            {new Date(
                                                notif.created_at,
                                            ).toLocaleDateString()}
                                        </p>
                                    </div>
                                    {!notif.is_read && (
                                        <div className="flex-shrink-0">
                                            <div className="h-2 w-2 rounded-full bg-slate-600"></div>
                                        </div>
                                    )}
                                </div>

                                <div className="mt-3 flex items-center gap-2">
                                    {!notif.is_read && (
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={() =>
                                                onMarkAsRead(notif.id)
                                            }
                                            className="h-auto p-0 text-xs text-slate-600 hover:text-slate-700"
                                        >
                                            Mark as read
                                        </Button>
                                    )}
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        asChild
                                        className="h-auto p-0 text-xs text-slate-600 hover:text-slate-700"
                                    >
                                        <a href={`/notifications/${notif.id}`}>
                                            View full
                                        </a>
                                    </Button>
                                    {notif.action_url && (
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            asChild
                                            className="h-auto p-0 text-xs text-slate-600 hover:text-slate-700"
                                        >
                                            <a href={notif.action_url}>
                                                Action
                                            </a>
                                        </Button>
                                    )}
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        onClick={() => onDelete(notif.id)}
                                        className="ml-auto h-auto p-0 text-xs text-red-600 hover:text-red-700"
                                    >
                                        Delete
                                    </Button>
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
};

const UserProfileCard = ({ userName, userBalance, isTrusted, userAvatar }) => {
    // Use the avatar prop directly, with fallback to generated avatar
    const displayAvatar =
        userAvatar ||
        "https://ui-avatars.com/api/?name=" +
            encodeURIComponent(userName) +
            "&background=64748b&color=fff&size=120";

    const handleCardClick = (e) => {
        // Don't navigate if clicking on the balance link
        if (e.target.closest('a[href="/balance"]')) {
            return;
        }
        window.location.href = "/settings";
    };

    return (
        <div onClick={handleCardClick} className="group cursor-pointer">
            <DashboardCard className="h-64 transition-all hover:shadow-lg md:col-span-1">
                <div className="flex h-full flex-col items-center justify-center text-center">
                    {/* Avatar with trusted badge */}
                    <div className="relative mb-3">
                        <Avatar className="h-20 w-20">
                            <AvatarImage src={displayAvatar} alt={userName} />
                            <AvatarFallback>
                                {userName
                                    .split(" ")
                                    .map((n) => n[0])
                                    .join("")
                                    .toUpperCase()}
                            </AvatarFallback>
                        </Avatar>
                        {isTrusted && (
                            <div className="absolute -right-1 -bottom-1 flex h-6 w-6 items-center justify-center rounded-full bg-slate-600 ring-2 ring-white">
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

                    {/* Name with verified badge */}
                    <div className="flex items-center gap-1.5">
                        <h4 className="text-lg font-semibold text-gray-900 dark:text-white">
                            {userName}
                        </h4>
                        {isTrusted && (
                            <Badge className="bg-slate-100 text-xs text-slate-700 dark:bg-slate-900/30 dark:text-slate-400">
                                Verified
                            </Badge>
                        )}
                    </div>

                    {/* Balance - Clickable */}
                    <a href="/balance" className="group mt-4 block">
                        <p className="text-xs font-medium text-gray-500 transition-all group-hover:text-slate-600 dark:text-gray-400">
                            Balance
                        </p>
                        <div className="flex items-center gap-2">
                            <p className="text-2xl font-semibold text-gray-900 transition-all group-hover:text-slate-700 dark:text-white dark:group-hover:text-slate-400">
                                Rp {userBalance.toLocaleString("id-ID")}
                            </p>
                            <svg
                                className="h-5 w-5 text-gray-400 transition-all group-hover:translate-x-1 group-hover:text-slate-600"
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
        </div>
    );
};

const RentedDeviceCard = ({
    isDelivering,
    onMarkAsReceived,
    deviceName,
    orderId,
    hasOrders,
}) => {
    console.log("RentedDeviceCard render:", {
        isDelivering,
        deviceName,
        orderId,
        hasOrders,
    });

    // If no orders exist at all (new user), show welcome card
    if (!hasOrders) {
        console.log("Rendering new user welcome card");
        return (
            <a href="/devices" className="block md:col-span-2">
                <DashboardCard className="h-64" padding={false}>
                    <div className="relative h-64 overflow-hidden rounded-xl bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-slate-900/90 backdrop-blur-sm">
                        <div className="absolute inset-0 flex items-center justify-center p-8">
                            <div className="text-center text-white">
                                <div className="mb-4 flex justify-center">
                                    <div className="flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
                                        <Smartphone
                                            size={32}
                                            className="text-white"
                                        />
                                    </div>
                                </div>
                                <h3 className="mb-2 text-2xl font-bold">
                                    Ready to Rent?
                                </h3>
                                <p className="mb-6 text-white/90">
                                    Browse our collection of premium devices and
                                    start your rental today.
                                </p>
                                <Button
                                    asChild
                                    className="rounded-lg bg-white font-medium text-gray-900 shadow-lg transition-all hover:bg-gray-100 hover:shadow-xl"
                                >
                                    <span className="inline-flex items-center gap-2">
                                        Browse Devices
                                        <ChevronRight size={16} />
                                    </span>
                                </Button>
                            </div>
                        </div>
                    </div>
                </DashboardCard>
            </a>
        );
    }

    if (isDelivering) {
        console.log("Rendering delivery card");
        return (
            <DashboardCard className="h-64 md:col-span-2" padding={false}>
                <div className="relative h-64 overflow-hidden rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    {/* Simple delivery illustration */}
                    <div className="absolute inset-0 flex items-center justify-center p-8">
                        <div className="text-center">
                            <div className="mb-4 flex justify-center">
                                <div className="flex h-20 w-20 items-center justify-center rounded-full bg-slate-600 shadow-lg">
                                    <Truck size={32} className="text-white" />
                                </div>
                            </div>
                            <h3 className="mb-2 text-xl font-bold text-gray-900">
                                Your {deviceName} is on the way!
                            </h3>
                            <p className="text-gray-600">
                                Out for delivery - Arriving soon
                            </p>
                        </div>
                    </div>

                    {/* Bottom action bar */}
                    <div className="absolute right-0 bottom-0 left-0 border-t border-gray-200/50 bg-white/95 p-4 backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/95">
                        <div className="flex items-center justify-between">
                            <div>
                                <h4 className="font-semibold text-gray-900">
                                    {deviceName}
                                </h4>
                                <p className="text-sm text-gray-600">
                                    Track your delivery progress
                                </p>
                            </div>
                            <Button asChild className="rounded-xl">
                                <a href={`/delivery/track/${orderId}`}>
                                    Track Delivery
                                </a>
                            </Button>
                        </div>
                    </div>
                </div>
            </DashboardCard>
        );
    }

    // When device is received - clickable card that goes to Find My Device
    console.log("Rendering device card");
    return (
        <a href={`/find-device/${orderId}`} className="block md:col-span-2">
            <DashboardCard padding={false}>
                <div className="relative h-64 w-full overflow-hidden rounded-2xl transition-all hover:scale-[1.02]">
                    <img
                        src="https://placehold.co/800x400/f0f0f0/3b82f6?text=iPhone+15"
                        alt="Rented Device"
                        className="h-full w-full object-cover"
                        onError={(e) => {
                            // Prevent infinite loop by checking if we've already tried fallback
                            if (!e.target.src.includes("cc0000")) {
                                e.target.onerror = null;
                                e.target.src =
                                    "https://placehold.co/800x400/f0f0f0/cc0000?text=Device";
                            }
                        }}
                    />

                    {/* Overlay Gradient */}
                    <div className="absolute inset-0 rounded-xl bg-gradient-to-r from-transparent via-gray-900/40 to-gray-900/80"></div>

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
    const [locationName, setLocationName] = useState("Loading location...");
    const [weather, setWeather] = useState(null);

    // Get location data from server
    useEffect(() => {
        const rootElement = document.getElementById("dashboard-root");
        if (rootElement) {
            const city = rootElement.getAttribute("data-location-city");
            const country = rootElement.getAttribute("data-location-country");
            const lat = rootElement.getAttribute("data-location-lat");
            const lon = rootElement.getAttribute("data-location-lon");

            console.log("Location data from server:", {
                city,
                country,
                lat,
                lon,
            });

            if (city && country && city !== "Location unavailable") {
                setLocationName(`${city}, ${country}`);

                // Try to get weather if we have coordinates
                if (lat && lon) {
                    getWeatherData(lat, lon);
                } else {
                    console.log("No coordinates available for weather");
                }
            } else {
                setLocationName("Location unavailable");
                console.log("Location data not available from server");
            }
        }
    }, []);

    const getWeatherData = async (lat, lon) => {
        try {
            console.log("Fetching weather for coordinates:", { lat, lon });
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 5000); // Increased timeout

            const response = await fetch(
                `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`,
                { signal: controller.signal },
            );

            clearTimeout(timeoutId);

            if (response.ok) {
                const data = await response.json();
                console.log("Weather data received:", data.current_weather);
                if (data.current_weather) {
                    setWeather({
                        temp: Math.round(data.current_weather.temperature),
                        code: data.current_weather.weathercode,
                    });
                }
            } else {
                console.warn(
                    "Weather API returned non-OK status:",
                    response.status,
                );
            }
        } catch (error) {
            console.log("Weather API error:", error.message);
            // Weather is optional, don't show error to user
        }
    };

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
                            <div className="h-14 w-14 animate-ping rounded-full border-4 border-slate-500 opacity-30"></div>
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
            if ("memory" in performance && performance.memory) {
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
                            <div className="rounded-lg bg-slate-50 p-2 dark:bg-slate-900/20">
                                <Database
                                    size={18}
                                    className="text-slate-600 dark:text-slate-500"
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
                <div className="relative h-full overflow-hidden rounded-xl bg-gradient-to-br from-slate-900/90 via-slate-800/80 to-slate-900/90 backdrop-blur-sm">
                    <div className="absolute inset-0 flex items-center justify-between px-8">
                        <div className="max-w-xl text-white">
                            <div className="mb-2 inline-flex items-center space-x-2 rounded-full bg-white/10 px-3 py-1 backdrop-blur-sm">
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
    // Read data from Laravel - moved to component level for safety
    const [userBalance, setUserBalance] = useState(0);
    const [isDelivered, setIsDelivered] = useState(true);
    const [deviceName, setDeviceName] = useState("Your Device");
    const [orderId, setOrderId] = useState(null);
    const [hasOrders, setHasOrders] = useState(false);
    const [userName, setUserName] = useState("User");
    const [isTrusted, setIsTrusted] = useState(false);

    // Initialize data from DOM safely
    useEffect(() => {
        const rootElement = document.getElementById("dashboard-root");
        if (rootElement) {
            setIsDelivered(
                rootElement.getAttribute("data-is-delivered") !== "true",
            );
            setDeviceName(
                rootElement.getAttribute("data-device-name") || "Your Device",
            );
            setOrderId(rootElement.getAttribute("data-order-id") || null);
            setHasOrders(
                rootElement.getAttribute("data-has-orders") === "true",
            );
            setUserName(rootElement.getAttribute("data-user-name") || "User");
            setUserBalance(
                parseInt(rootElement.getAttribute("data-user-balance") || "0"),
            );
            setIsTrusted(
                rootElement.getAttribute("data-is-trusted") === "true",
            );

            // Get user avatar from data attribute first, then navbar as fallback
            const avatarUrl =
                rootElement.getAttribute("data-user-avatar") || "";
            const userName =
                rootElement.getAttribute("data-user-name") || "User";

            if (avatarUrl) {
                setUserAvatar(avatarUrl);
            } else {
                // Try to get from header navigation
                const navbarAvatar = document.querySelector(
                    'header img[src*="storage"]',
                );
                if (navbarAvatar && navbarAvatar.src) {
                    setUserAvatar(navbarAvatar.src);
                } else {
                    // Generate avatar with user's name
                    setUserAvatar(
                        "https://ui-avatars.com/api/?name=" +
                            encodeURIComponent(userName) +
                            "&background=3b82f6&color=fff&size=120",
                    );
                }
            }
        }
    }, []);
    const [userAvatar, setUserAvatar] = useState("");
    const [isDarkMode, setIsDarkMode] = useState(false);
    const [notifications, setNotifications] = useState([]);
    const [unreadCount, setUnreadCount] = useState(0);
    const [isNotificationModalOpen, setIsNotificationModalOpen] =
        useState(false);

    // Derived state for isDelivering
    const isDelivering = !isDelivered;

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
            <div className="min-h-screen bg-gray-50 text-gray-900 transition-colors duration-300 dark:bg-black dark:text-white">
                <main className="mx-auto max-w-7xl">
                    <Header
                        onToggleDarkMode={toggleDarkMode}
                        isDarkMode={isDarkMode}
                        unreadCount={unreadCount}
                        onOpenNotifications={handleOpenNotifications}
                        isNotificationModalOpen={isNotificationModalOpen}
                        onCloseNotifications={() =>
                            setIsNotificationModalOpen(false)
                        }
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
                                    <div className="relative overflow-hidden rounded-xl border border-slate-700/50 bg-gradient-to-r from-slate-900/90 via-slate-800/80 to-slate-900/90 p-6 backdrop-blur-sm transition-all hover:border-slate-600/50">
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
                                                <div className="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-white/10 backdrop-blur-sm">
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
                                                <div className="flex h-12 w-12 items-center justify-center rounded-lg bg-white text-gray-900 transition-all group-hover:bg-slate-50">
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
                            hasOrders={hasOrders}
                        />
                        <UserProfileCard
                            userName={userName}
                            userBalance={userBalance}
                            isTrusted={isTrusted}
                            userAvatar={userAvatar}
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
import { StrictMode } from "react";
const rootElement = document.getElementById("dashboard-root");
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(
        <StrictMode>
            <App />
        </StrictMode>,
    );
}
