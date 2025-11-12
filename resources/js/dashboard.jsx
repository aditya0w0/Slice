import React, { useState } from "react";
import {
    Smartphone,
    Map,
    User,
    Sun,
    Moon,
    BatteryCharging,
    MapPin,
    LifeBuoy,
    CalendarDays,
    Wallet,
    Database,
    Wifi,
    Truck,
    Scroll,
    ChevronRight,
    CalendarCheck,
    ShieldAlert,
    // --- ICON BARU UNTUK GIMMICK AI ---
    Sparkles, // <-- Ikon AI
} from "lucide-react";

/**
 * Komponen Kartu Dasbor Reusable
// ... (Kode komponen DashboardCard tidak berubah) ...
 */
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

/**
 * Komponen Header
// ... (Kode komponen Header tidak berubah) ...
 */
const Header = ({ onToggleDarkMode, isDarkMode }) => (
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

        {/* Dark Mode Toggle */}
        <button
            onClick={onToggleDarkMode} // Handler sudah ada
            className="rounded-lg bg-white/60 p-2 text-gray-700 backdrop-blur-xl hover:text-blue-500 dark:bg-gray-800/60 dark:text-gray-300 dark:hover:text-blue-400"
        >
            {isDarkMode ? <Sun size={20} /> : <Moon size={20} />}
        </button>
    </header>
);

/**
 * Kartu: Profil Pengguna (Dasbor User)
 * Sesuai blueprint: [USER PROFILE]
 * !! PERBAIKAN: Tombol-tombol dibuang, Saldo di-redesign (biar gak "goofy") !!
 */
const UserProfileCard = () => (
    <DashboardCard className="h-64 md:col-span-1">
        {/* justify-center akan membuat ini tetap rapi walau tombol hilang */}
        <div className="flex h-full flex-col items-center justify-center text-center">
            {/* Avatar */}
            <div className="mb-3 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 shadow-lg">
                <User size={40} className="text-white" />
            </div>
            {/* Nama */}
            <h4 className="text-lg font-semibold text-gray-900 dark:text-white">
                John Doe
            </h4>

            {/* !! SALDO REDESIGN (GAK GOOFY LAGI) !! */}
            {/* Ini jadi metric bersih yang clickable, bukan tombol pil */}
            <div
                onClick={() => console.log("Klik: Top Up Saldo")}
                className="group mt-4 cursor-pointer"
            >
                <p className="text-xs font-medium text-gray-500 transition-all group-hover:text-blue-400 dark:text-gray-400">
                    Saldo
                </p>
                <p className="text-2xl font-bold text-gray-900 transition-all group-hover:text-blue-400 dark:text-white">
                    Rp 150.000
                </p>
            </div>

            {/* !! TOMBOL-TOMBOL DIBAWAH INI DIHAPUS SESUAI REQUEST !! */}
            {/* <div className="flex flex-col space-y-2 mt-4 w-full px-4">
        <button ...>
          Ubah Profil
        </button>
        <button ...>
          Riwayat Pesanan
        </button>
        <button ...>
          Chat Bantuan
        </button>
      </div> */}
        </div>
    </DashboardCard>
);

/**
 * Kartu: Perangkat Disewa (Dasbor User)
// ... (Kode komponen RentedDeviceCard tidak berubah) ...
 */
const RentedDeviceCard = ({ isDelivering, onMarkAsReceived }) => {
    // SKENARIO B: Lagi nunggu kiriman
    if (isDelivering) {
        return (
            <DashboardCard
                title="Pelacakan Pengiriman"
                className="h-64 md:col-span-2"
            >
                <div className="flex h-full flex-col items-center justify-center text-center">
                    <Truck size={48} className="mb-4 text-blue-500" />
                    <p className="font-semibold text-gray-800 dark:text-gray-200">
                        iPhone 15 Pro Anda
                    </p>
                    <p className="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        Sedang dalam perjalanan!
                    </p>
                    {/* Tombol Lacak */}
                    <button
                        onClick={() => console.log("Klik: Lacak Pengiriman")}
                        className="rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-all hover:bg-blue-600"
                    >
                        Lacak Pengiriman
                    </button>
                    <button
                        onClick={onMarkAsReceived} // Handler sudah ada
                        className="mt-3 text-xs font-medium text-gray-500 hover:text-blue-500 dark:text-gray-400"
                    >
                        Tandai Sudah Diterima
                    </button>
                </div>
            </DashboardCard>
        );
    }

    // SKENARIO A (Default): Perangkat sudah di tangan
    return (
        <DashboardCard className="md:col-span-2" padding={false}>
            {/* Container Relatif untuk overlay. Set h-64 (sama kayak Map) biar rapi */}
            <div className="relative h-64 w-full">
                {/* 1. Gambar Background */}
                <img
                    src="https://placehold.co/800x400/f0f0f0/300?text=iPhone+15"
                    alt="Perangkat Disewa"
                    className="h-full w-full rounded-2xl object-cover"
                    onError={(e) => {
                        e.target.onerror = null;
                        e.target.src =
                            "https://placehold.co/800x400/f0f0f0/cc0000?text=Error";
                    }}
                />

                {/* 2. Overlay Gradient (untuk readability) */}
                <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-black/30 to-black/70"></div>

                {/* 3. Konten Teks (Ngambang) - Pindah ke kanan atas */}
                <div className="absolute top-6 right-6 text-right">
                    <div className="text-white">
                        <h4 className="text-2xl font-bold">iPhone 15 Pro</h4>
                        <div className="mt-2 flex items-center justify-end space-x-2">
                            <span className="font-medium text-green-400">
                                Online
                            </span>
                            <span className="h-2 w-2 rounded-full bg-green-500"></span>
                        </div>

                        <div className="mt-4 space-y-2 text-sm">
                            {/* Tombol Perpanjang */}
                            <button
                                onClick={() =>
                                    console.log("Klik: Perpanjang Langganan")
                                }
                                className="flex w-full items-center justify-end space-x-2 rounded-lg bg-black/20 p-1.5 transition-all hover:bg-black/40"
                            >
                                <span>Sisa 21 hari</span>
                                <CalendarDays size={16} />
                            </button>
                            {/* Info Baterai (Tetap Teks) */}
                            <div className="flex w-full items-center justify-end space-x-2 rounded-lg bg-black/20 p-1.5">
                                <span>92%</span>
                                <BatteryCharging size={16} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </DashboardCard>
    );
};

/**
 * Kartu: Peta (Dasbor User)
// ... (Kode komponen MapCard tidak berubah) ...
 */
const MapCard = () => (
    <DashboardCard title="Peta Lokasi" className="h-64 md:col-span-2">
        <div className="flex h-full items-center justify-center rounded-lg bg-gray-200 text-gray-500 dark:bg-gray-700 dark:text-gray-400">
            <MapPin size={48} />
            <p className="ml-2">Peta akan ditampilkan di sini</p>
        </div>
    </DashboardCard>
);

/**
 * Kartu: Statistik Penggunaan (Dasbor User)
// ... (Kode komponen QuickActionsCard tidak berubah) ...
 */
const QuickActionsCard = () => {
    // SKENARIO A (Default): Perangkat sudah di tangan
    return (
        <DashboardCard
            title="Statistik Penggunaan"
            className="h-64 md:col-span-1"
        >
            <div className="flex h-full flex-col justify-center space-y-6">
                {/* Statistik Data */}
                <div className="flex items-center space-x-4">
                    <div className="rounded-full bg-blue-100 p-3 dark:bg-blue-900/50">
                        <Wifi size={24} className="text-blue-500" />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Sisa Kuota Data
                        </p>
                        <p className="text-xl font-semibold text-gray-900 dark:text-white">
                            84.8 GB
                        </p>
                    </div>
                </div>
                {/* Statistik Storage */}
                <div className="flex items-center space-x-4">
                    <div className="rounded-full bg-blue-100 p-3 dark:bg-blue-900/50">
                        <Database size={24} className="text-blue-500" />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-gray-500 dark:text-gray-400">
                            Sisa Penyimpanan
                        </p>
                        <p className="text-xl font-semibold text-gray-900 dark:text-white">
                            176 GB
                        </p>
                    </div>
                </div>
            </div>
        </DashboardCard>
    );
};

/**
 * Kartu Bawah: Gimmick AI
// ... (Kode komponen QuickListCard tidak berubah) ...
 */
const QuickListCard = ({ isProSubscriber }) => {
    const [isLoading, setIsLoading] = useState(false);
    const [aiTip, setAiTip] = useState(
        'Klik "Generate" untuk mendapatkan tips harian khusus perangkat Anda.',
    );

    // Fungsi yang nge-call Gemini API (disimulasi)
    const handleGenerateTip = async () => {
        // 1. Kalo BUKAN subscriber, munculin paywall (Gimmick + Charge)
        if (!isProSubscriber) {
            console.log("GIMMICK: Tampilkan modal paywall!");
            setAiTip(
                "Upgrade ke Pro untuk mendapatkan tips AI harian yang dipersonalisasi!",
            );
            return;
        }

        // 2. Kalo subscriber, beneran call API
        setIsLoading(true);
        setAiTip("AI sedang memikirkan tips terbaik untuk Anda...");

        // Simulasi call Gemini API
        await new Promise((res) => setTimeout(res, 1500));

        // Hasil dari API
        const tips = [
            "Tip AI: Gunakan mode ProRAW untuk kontrol penuh atas foto malam Anda.",
            "Tip AI: Atur Aksi Tombol untuk membuka kamera secara instan.",
            "Tip AI: Coba rekam Video Spasial untuk pengalaman imersif di Vision Pro.",
        ];
        const randomTip = tips[Math.floor(Math.random() * tips.length)];

        setAiTip(randomTip);
        setIsLoading(false);
    };

    return (
        <DashboardCard className="md:col-span-3" padding={false}>
            {/*
        Kita ganti banner statis jadi "AI Gimmick".
        Ini 100% non-redundant dan 100% chargeable.
      */}
            <div className="relative h-48 w-full overflow-hidden rounded-2xl">
                {/* Gambar background (bisa diganti) */}
                <img
                    src="https://placehold.co/1200x300/1e293b/94a3b8?text=AI"
                    alt="AI Background"
                    className="h-full w-full object-cover"
                />
                {/* Overlay */}
                <div className="absolute inset-0 bg-gradient-to-r from-indigo-800/70 to-purple-800/80"></div>

                {/* Konten */}
                <div className="absolute inset-0 flex items-center justify-between p-8">
                    {/* Sisi Kiri: Info AI */}
                    <div className="max-w-xl text-white">
                        <div className="mb-2 flex items-center space-x-2">
                            <Sparkles size={16} />
                            <span className="text-sm font-medium">
                                AI ASSISTANT
                                {isProSubscriber ? " PRO" : " (Upgrade!)"}
                            </span>
                        </div>
                        <h4 className="mb-3 text-2xl font-bold">
                            Tips Harian Personal
                        </h4>
                        {/* Di sinilah hasil AI-nya muncul */}
                        <p className="text-sm text-indigo-100/80 italic">
                            {aiTip}
                        </p>
                    </div>

                    {/* Sisi Kanan: 2 Tombol Aksi (NON-REDUNDANT) */}
                    {/* !! PERBAIKAN: flex-col (numpuk) TAPI items-end (rata kanan) !! */}
                    <div className="flex flex-col items-end space-y-3">
                        {/* 1. Tombol Utama: Generate AI */}
                        <button
                            onClick={handleGenerateTip}
                            disabled={isLoading}
                            className={`flex flex-shrink-0 items-center space-x-2 rounded-lg px-6 py-3 font-semibold shadow-lg transition-all ${
                                isLoading
                                    ? "bg-gray-400 text-gray-800"
                                    : "bg-white text-indigo-700 hover:bg-gray-100"
                            }`}
                        >
                            <Sparkles size={18} />
                            <span>
                                {isLoading ? "Memproses..." : "Generate Tip"}
                            </span>
                        </button>
                        {/* 2. Tombol Sekunder: Workshop (Biar gak hilang) */}
                        <button
                            onClick={() => console.log("Klik: Daftar Workshop")}
                            className="flex items-center space-x-2 text-sm text-white/70 transition-all hover:text-white"
                        >
                            <CalendarCheck size={16} />
                            <span>Daftar Workshop</span>
                        </button>
                    </div>
                </div>
            </div>
        </DashboardCard>
    );
};

/**
 * Komponen Aplikasi Utama
// ... (Kode komponen App tidak berubah) ...
 */
export default function App() {
    const [isDarkMode, setIsDarkMode] = useState(true);
    // STATE BARU: Untuk melacak status pengiriman
    const [isDelivering, setIsDelivering] = new useState(true);
    // --- STATE BARU: Tentukan user ini Pro atau bukan (Gimmick) ---
    const [isProSubscriber, setIsProSubscriber] = useState(false); // <-- Set false buat liat paywall!

    const toggleDarkMode = () => setIsDarkMode(!isDarkMode);

    // Fungsi untuk disimulasi
    const handleMarkAsReceived = () => {
        setIsDelivering(false);
    };

    return (
        <div className={isDarkMode ? "dark" : ""}>
            <div className="min-h-screen bg-gray-100 text-gray-900 transition-colors duration-300 dark:bg-gray-900 dark:text-white">
                <main className="mx-auto max-w-7xl">
                    <Header
                        onToggleDarkMode={toggleDarkMode}
                        isDarkMode={isDarkMode}
                    />

                    {/* Grid Dasbor Utama */}
                    <div className="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">
                        {/* Baris 1: Sesuai blueprint */}
                        {/* Baris 1: Dasbor User */}
                        <RentedDeviceCard
                            isDelivering={isDelivering}
                            onMarkAsReceived={handleMarkAsReceived}
                        />
                        <UserProfileCard />

                        {/* Baris 2: Dasbor User */}
                        <QuickActionsCard />
                        <MapCard />

                        {/* Baris 3: Gimmick AI */}
                        <QuickListCard isProSubscriber={isProSubscriber} />
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
