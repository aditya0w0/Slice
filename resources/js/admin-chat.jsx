import React from "react";
import { createRoot } from "react-dom/client";
import AdminChat from "./pages/AdminChat";
import "./bootstrap";

console.log("🚀 Admin chat entry point loaded");
console.log("👤 window.admin:", window.admin);

const rootElement = document.getElementById("admin-chat-root");
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<AdminChat />);
    console.log("✅ AdminChat component rendered");
} else {
    console.error("❌ Could not find #admin-chat-root element");
}
