import React from "react";
import { createRoot } from "react-dom/client";
import UnifiedChat from "./pages/UnifiedChat";
import "./bootstrap";

console.log("🚀 Admin chat entry point loaded");

const rootElement= document.getElementById("admin-chat-root");
if (rootElement) {
    const root = createRoot(rootElement);
    root.render(<UnifiedChat role="admin" />);
    console.log("✅ UnifiedChat (admin) rendered");
} else {
    console.error("❌ Could not find #admin-chat-root element");
}
