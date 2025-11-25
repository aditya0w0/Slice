import "./bootstrap";
import "../css/app.css";
import React from "react";
import { createRoot } from "react-dom/client";
import UnifiedChat from "./pages/UnifiedChat";

const root = createRoot(document.getElementById("chat-root"));
root.render(<UnifiedChat role="user" />);
