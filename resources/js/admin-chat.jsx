import "./bootstrap";
import "../css/app.css";
import React from "react";
import { createRoot } from "react-dom/client";
import AdminChat from "./pages/AdminChat";

const root = createRoot(document.getElementById("admin-chat-root"));
root.render(<AdminChat />);
