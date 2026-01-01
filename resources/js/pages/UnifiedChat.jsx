import React, { useState, useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { checkImage, preloadModel } from "@/utils/nsfwDetector";

export default function UnifiedChat({ role = "admin" }) {
    const isAdmin = role === "admin";
    const [sidebarOpen, setSidebarOpen] = useState(isAdmin);
    const [profileOpen, setProfileOpen] = useState(false);
    const [newChatOpen, setNewChatOpen] = useState(false);
    const [fileModalOpen, setFileModalOpen] = useState(false);
    const [emojiPickerOpen, setEmojiPickerOpen] = useState(false);
    const [filterOpen, setFilterOpen] = useState(false);
    const [filePreviewOpen, setFilePreviewOpen] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    const [fileCaption, setFileCaption] = useState("");
    const [imagePreviewUrl, setImagePreviewUrl] = useState(null);
    const [contactFilter, setContactFilter] = useState("all"); // all, unread, favorites
    const [searchTerm, setSearchTerm] = useState("");
    const [allUsers, setAllUsers] = useState([]);
    const [contacts, setContacts] = useState([]);
    const [activeChat, setActiveChat] = useState(null);
    const [messages, setMessages] = useState([]);
    const [messageInput, setMessageInput] = useState("");
    const [loading, setLoading] = useState(true);
    const [uploading, setUploading] = useState(false);
    const [emojiSearch, setEmojiSearch] = useState("");
    const [emojiCategory, setEmojiCategory] = useState("all");
    const [selectionMode, setSelectionMode] = useState(false);
    const [selectedMessages, setSelectedMessages] = useState(new Set());
    const [menuOpen, setMenuOpen] = useState(false);
    const [confirmModal, setConfirmModal] = useState({
        isOpen: false,
        title: "",
        message: "",
        onConfirm: null,
        isDanger: false,
    });
    const [error, setError] = useState(null);
    const messagesEndRef = useRef(null);
    const fileInputRef = useRef(null);
    const menuRef = useRef(null);

    // Helper to get fresh CSRF token
    const getCsrfToken = () => {
        return document.querySelector('meta[name="csrf-token"]')?.content || '';
    };

    // Helper to refresh CSRF token from server
    const refreshCsrfToken = async () => {
        try {
            const response = await fetch('/csrf-token', { 
                method: 'GET',
                credentials: 'same-origin'
            });
            if (response.ok) {
                const data = await response.json();
                if (data.csrf_token) {
                    const metaTag = document.querySelector('meta[name="csrf-token"]');
                    if (metaTag) {
                        metaTag.content = data.csrf_token;
                    }
                    return data.csrf_token;
                }
            }
        } catch (error) {
            console.error('Failed to refresh CSRF token:', error);
        }
        return getCsrfToken();
    };

    // Safe fetch with CSRF retry
    const fetchWithCsrf = async (url, options = {}, retryCount = 0) => {
        const maxRetries = 1;
        const headers = {
            ...options.headers,
            'X-CSRF-TOKEN': getCsrfToken()
        };
        
        try {
            const response = await fetch(url, { ...options, headers });
            
            // If 419 CSRF error, refresh token and retry once
            if (response.status === 419 && retryCount < maxRetries) {
                console.warn('CSRF token expired, refreshing...');
                await refreshCsrfToken();
                return fetchWithCsrf(url, options, retryCount + 1);
            }
            
            return response;
        } catch (error) {
            throw error;
        }
    };

    // Custom emoji data with categories
    const emojiData = {
        smileys: {
            name: "Smileys",
            emojis: [
                "😀",
                "😂",
                "🥰",
                "😍",
                "🤗",
                "😉",
                "😎",
                "🤔",
                "😢",
                "😭",
                "😤",
                "😡",
                "🥺",
                "😴",
                "🤤",
                "😈",
                "👻",
                "🤖",
                "👽",
                "💩",
            ],
        },
        gestures: {
            name: "Gestures",
            emojis: [
                "👍",
                "👎",
                "👌",
                "✌️",
                "🤞",
                "🤘",
                "🤙",
                "👏",
                "🙌",
                "🤝",
                "🙏",
                "✊",
                "🤛",
                "🤜",
                "🤚",
                "🖐️",
                "✋",
                "🖖",
                "👋",
                "🤟",
            ],
        },
        hearts: {
            name: "Hearts",
            emojis: [
                "❤️",
                "💛",
                "💚",
                "💙",
                "💜",
                "🖤",
                "🤍",
                "🤎",
                "💔",
                "❤️‍🔥",
                "❤️‍🩹",
                "💕",
                "💞",
                "💓",
                "💗",
                "💖",
                "💘",
                "💝",
                "💟",
                "♥️",
            ],
        },
        food: {
            name: "Food",
            emojis: [
                "🍎",
                "🍊",
                "🍋",
                "🍌",
                "🍉",
                "🍇",
                "🍓",
                "🫐",
                "🍈",
                "🍒",
                "🍑",
                "🥭",
                "🍍",
                "🥥",
                "🥝",
                "🍅",
                "🍆",
                "🥑",
                "🥦",
                "🥬",
            ],
        },
        activities: {
            name: "Activities",
            emojis: [
                "⚽",
                "🏀",
                "🏈",
                "⚾",
                "🎾",
                "🏐",
                "🏉",
                "🥏",
                "🎱",
                "🪀",
                "🏓",
                "🏸",
                "🏒",
                "🏑",
                "🥍",
                "🏏",
                "🪃",
                "🥅",
                "⛳",
                "🪁",
            ],
        },
        objects: {
            name: "Objects",
            emojis: [
                "💻",
                "🖥️",
                "🖱️",
                "⌨️",
                "🖨️",
                "📱",
                "📲",
                "💾",
                "💿",
                "📀",
                "📺",
                "📷",
                "📸",
                "📹",
                "🎥",
                "📽️",
                "🎞️",
                "📞",
                "☎️",
                "📟",
            ],
        },
        symbols: {
            name: "Symbols",
            emojis: [
                "❤️",
                "💯",
                "🔥",
                "⭐",
                "✨",
                "💫",
                "🎉",
                "🎊",
                "🎈",
                "🎁",
                "🏆",
                "🥇",
                "🥈",
                "🥉",
                "🏅",
                "🎖️",
                "🏵️",
                "🎗️",
                "🎀",
                "🎗️",
            ],
        },
    };

    // Helper to safely fetch JSON and handle non-OK responses or non-JSON body gracefully
    const safeFetchJson = async (endpoint, options = {}) => {
        const response = await fetch(endpoint, options);
        const contentType = response.headers.get("content-type") || "";
        if (!response.ok) {
            const body = contentType.includes("application/json")
                ? await response.json().catch(() => null)
                : await response.text().catch(() => null);
            const msg =
                `HTTP ${response.status}: ${response.statusText}` +
                (body
                    ? ` - ${typeof body === "string" ? body : body.message || JSON.stringify(body)}`
                    : "");
            const err = new Error(msg);
            err.status = response.status;
            err.body = body;
            throw err;
        }
        if (!contentType.includes("application/json")) {
            const text = await response.text();
            throw new Error("Server returned non-JSON response: " + text);
        }
        return response.json();
    };

    console.log(
        `🔄 UnifiedChat (${role}) rendering, contacts:`,
        contacts.length,
    );

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    // Merge server contacts with local contacts to avoid wiping existing UI state unexpectedly
    const normalizeId = (id) =>
        typeof id === "string" ? parseInt(id, 10) : id;

    const mergeContacts = (serverConvos = [], localConvos = []) => {
        const merged = [];
        const seenIds = new Set();
        const seenMeta = new Set();
        // Handle ids as numbers consistently and keep track of name/email to avoid duplicates
        (serverConvos || []).forEach((sc) => {
            const sid = normalizeId(sc.id);
            const meta = `${(sc.email || "").toLowerCase().trim()}|${(sc.name || "").toLowerCase().trim()}`;
            seenIds.add(sid);
            seenMeta.add(meta);
            merged.push({ ...sc, id: sid });
        });
        (localConvos || []).forEach((lc) => {
            const lid = normalizeId(lc.id);
            const meta = `${(lc.email || "").toLowerCase().trim()}|${(lc.name || "").toLowerCase().trim()}`;
            if (!seenIds.has(lid) && !seenMeta.has(meta)) {
                seenIds.add(lid);
                seenMeta.add(meta);
                merged.push({ ...lc, id: lid });
            }
        });
        return merged;
    };

    const getMappedContactId = (id) => {
        // if id is 0 (local support stub), try to map to server-provided support id in contacts or allUsers
        try {
            const numericId = Number(id);
            if (numericId !== 0) return numericId;
            // If we have a support contact listed with a non-zero id, use it
            const found =
                (contacts || []).find((c) => {
                    const email = (c.email || "").toLowerCase().trim();
                    const name = (c.name || "").toLowerCase().trim();
                    return (
                        Number(c.id) !== 0 &&
                        (email === "support@slice.com" ||
                            name.includes("slice support"))
                    );
                }) ||
                (allUsers || []).find((u) => {
                    const email = (u.email || "").toLowerCase().trim();
                    const name = (u.name || "").toLowerCase().trim();
                    return (
                        Number(u.id) !== 0 &&
                        (email === "support@slice.com" ||
                            name.includes("slice support"))
                    );
                });
            if (found) return Number(found.id);
            return numericId;
        } catch (err) {
            return id;
        }
    };

    // Check if a contact is admin/support
    const isAdminOrSupport = (contact) => {
        if (!contact) return false;
        const email = (contact.email || "").toLowerCase().trim();
        const name = (contact.name || "").toLowerCase().trim();
        return (
            email === "support@slice.com" ||
            name.includes("slice support") ||
            contact.is_admin
        );
    };

    // If support duplicates exist (e.g. id 0 stub and server id), consolidate them into a single contact.
    const canonicalizeSupport = (list = []) => {
        if (!Array.isArray(list) || list.length === 0) return list;
        // Find all support-like candidates
        const supportCandidates = list.filter((c) => {
            const email = (c.email || "").toLowerCase().trim();
            const name = (c.name || "").toLowerCase().trim();
            return (
                email === "support@slice.com" ||
                name.includes("slice support") ||
                Number(c.id) === 0
            );
        });
        if (supportCandidates.length <= 1) return list;

        // Prefer a non-zero id, otherwise keep the first one
        const canonical =
            supportCandidates.find((c) => Number(c.id) !== 0) ||
            supportCandidates[0];
        const canonicalId = Number(canonical.id);
        const canonicalName = canonical.name || "Slice Support";
        const canonicalEmail = canonical.email || "support@slice.com";

        // Build new list without the other support items and ensure canonical is present
        const cleaned = list.filter((c) => {
            const email = (c.email || "").toLowerCase().trim();
            const name = (c.name || "").toLowerCase().trim();
            // Keep only canonical one among support group
            if (
                email === "support@slice.com" ||
                name.includes("slice support") ||
                Number(c.id) === 0
            ) {
                return Number(c.id) === canonicalId;
            }
            return true;
        });

        // Make sure canonical has normalized fields
        const idx = cleaned.findIndex((c) => Number(c.id) === canonicalId);
        if (idx >= 0) {
            cleaned[idx] = {
                ...cleaned[idx],
                id: canonicalId,
                name: canonicalName,
                email: canonicalEmail,
            };
        } else {
            // If canonical was somehow missing, add it
            cleaned.unshift({
                id: canonicalId,
                name: canonicalName,
                email: canonicalEmail,
                avatar: canonical.avatar || null,
                last_message: canonical.last_message || "",
                time: canonical.time || "",
                unread: canonical.unread || 0,
                status: canonical.status || "online",
                active: canonical.active || false,
            });
        }

        return cleaned;
    };

    const setActiveContactById = (userId) => {
        setContacts((prev) =>
            (prev || []).map((c) => ({
                ...c,
                active: Number(c.id) === Number(userId),
            })),
        );
    };

    // Add or update message in a safe, idempotent way to avoid duplicates and temp messages
    const addOrReplaceMessage = (msg) => {
        console.log("addOrReplaceMessage called with:", { id: msg.id, type: msg.type, attachment: msg.attachment?.name });
        
        setMessages((prev) => {
            console.log("Current messages count:", prev.length);
            
            // CRITICAL: Deduplicate by ID FIRST (most reliable)
            if (msg.id && prev.some((m) => Number(m.id) === Number(msg.id))) {
                console.log("Dedupe: Found exact ID match, updating message", msg.id);
                const newMsg = { ...msg, pending: false };
                if (
                    !newMsg.sender ||
                    (newMsg.sender !== "me" && newMsg.sender !== "them")
                ) {
                    const viewerId = isAdmin
                        ? window.admin?.id
                        : window.user?.id;
                    newMsg.sender =
                        newMsg.sender_id &&
                        viewerId &&
                        Number(newMsg.sender_id) === Number(viewerId)
                            ? "me"
                            : "them";
                }
                return prev.map((m) =>
                    Number(m.id) === Number(msg.id) ? { ...m, ...newMsg } : m,
                );
            }
            
            // CRITICAL: Deduplicate by attachment name (catches temp/server mismatches)
            if (msg.attachment?.name) {
                const duplicateIndex = prev.findIndex(
                    (m) => 
                        m.attachment?.name === msg.attachment.name &&
                        Number(m.sender_id) === Number(msg.sender_id)
                );
                
                if (duplicateIndex >= 0) {
                    console.log("Dedupe: Found duplicate attachment name, replacing", {
                        oldId: prev[duplicateIndex].id,
                        newId: msg.id,
                        name: msg.attachment.name
                    });
                    const copy = [...prev];
                    const newMsg = { ...msg, pending: false };
                    if (
                        !newMsg.sender ||
                        (newMsg.sender !== "me" && newMsg.sender !== "them")
                    ) {
                        const viewerId = isAdmin ? window.admin?.id : window.user?.id;
                        newMsg.sender =
                            newMsg.sender_id &&
                            viewerId &&
                            Number(newMsg.sender_id) === Number(viewerId)
                                ? "me"
                                : "them";
                    }
                    copy[duplicateIndex] = newMsg;
                    return copy;
                }
            }

            // If there are pending messages that match (by content), replace the first one
            const viewerId = isAdmin ? window.admin?.id : window.user?.id;
            const pendingIndex = prev.findIndex((m) => {
                if (!m.pending) return false;
                
                // For file/image/document messages, match by attachment name
                if (m.attachment && msg.attachment) {
                    return m.attachment.name === msg.attachment.name &&
                           Number(m.sender_id || viewerId) === Number(msg.sender_id);
                }
                
                // For text messages, match by content
                if (m.content !== msg.content) return false;
                // Match if sender_id matches, or if pending doesn't have sender_id and incoming is from me
                const pendingSenderId = m.sender_id || viewerId;
                return Number(pendingSenderId) === Number(msg.sender_id);
            });
            if (pendingIndex >= 0) {
                const copy = [...prev];
                const newMsg = { ...msg, pending: false };
                if (
                    !newMsg.sender ||
                    (newMsg.sender !== "me" && newMsg.sender !== "them")
                ) {
                    newMsg.sender =
                        newMsg.sender_id &&
                        viewerId &&
                        Number(newMsg.sender_id) === Number(viewerId)
                            ? "me"
                            : "them";
                }
                copy[pendingIndex] = newMsg;
                console.debug(
                    "Replaced pending message with server message:",
                    newMsg.id,
                );
                return copy;
            }

            // Fallback dedupe: if last message has the same content and sender, replace it
        const lastIndex = prev.length - 1;
        if (lastIndex >= 0) {
            const last = prev[lastIndex];

            // Dedupe by attachment name (fixes double render if type differs but file is same)
            if (
                msg.attachment &&
                last.attachment &&
                msg.attachment.name === last.attachment.name &&
                (Number(last.sender_id) === Number(msg.sender_id) || 
                 (last.sender === 'me' && msg.sender === 'me'))
            ) {
                console.log("Dedupe: Found duplicate attachment match, replacing.", {
                    lastId: last.id,
                    newId: msg.id,
                    name: msg.attachment.name
                });
                const copy = [...prev];
                const newMsg = { ...msg, pending: false };
                if (
                    !newMsg.sender ||
                    (newMsg.sender !== "me" && newMsg.sender !== "them")
                ) {
                    newMsg.sender =
                        newMsg.sender_type === (isAdmin ? "admin" : "user")
                            ? "me"
                            : "them";
                }
                copy[lastIndex] = newMsg;
                return copy;
            }

            if (
                last.content === msg.content &&
                last.sender_id === msg.sender_id
            ) {
                const copy = [...prev];
                const newMsg = { ...msg, pending: false };
                if (
                    !newMsg.sender ||
                    (newMsg.sender !== "me" && newMsg.sender !== "them")
                ) {
                    newMsg.sender =
                        newMsg.sender_type === (isAdmin ? "admin" : "user")
                            ? "me"
                            : "them";
                }
                copy[lastIndex] = newMsg;
                console.debug(
                    "Replaced last message (fallback) with incoming message:",
                    newMsg.id,
                );
                return copy;
            }
        }

        // Otherwise append
        const newMsg = { ...msg, pending: false };
        if (
            !newMsg.sender ||
            (newMsg.sender !== "me" && newMsg.sender !== "them")
        ) {
            const viewerId = isAdmin ? window.admin?.id : window.user?.id;
            newMsg.sender =
                newMsg.sender_id &&
                viewerId &&
                Number(newMsg.sender_id) === Number(viewerId)
                    ? "me"
                    : "them";
        }
        console.log("Appending new message:", newMsg.id, newMsg);
        return [...prev, newMsg];
    });
    // Allow DOM to mount new message before scrolling
    setTimeout(() => {
        try {
            scrollToBottom();
        } catch (err) {
            /* ignore */
        }
    }, 50);
};

    // Normalize messages coming from server so frontend can render consistently
    const normalizeServerMessage = (msg) => {
        const m = { ...msg };
        try {
            // If backend already set sender, trust it
            if (m.sender && (m.sender === "me" || m.sender === "them")) {
                return m;
            }
            // Otherwise calculate it
            const viewerId = isAdmin ? window.admin?.id : window.user?.id;
            if (m.sender_id && viewerId) {
                m.sender =
                    Number(m.sender_id) === Number(viewerId) ? "me" : "them";
            } else if (m.sender_type) {
                m.sender =
                    m.sender_type === (isAdmin ? "admin" : "user")
                        ? "me"
                        : "them";
            }
            if (m.sender_id) m.sender_id = Number(m.sender_id);
            if (m.id) m.id = Number(m.id); // Ensure ID is number for strict equality checks
        
            // Force type to 'text' if it's an image/file message (backend logic)
            // This ensures WebSocket messages (which might come as 'image') match HTTP messages ('text')
            if (m.attachment && (m.type === 'image' || m.type === 'file')) {
                m.type = 'text';
            }
        } catch (err) {
            console.error("Failed to normalize server message", err);
        }
        return m;
    };

    const [localLoaded, setLocalLoaded] = useState(false);
    useEffect(() => {
        // For users: clear localStorage to avoid duplicate support entries
        // For admin: restore saved contacts
        try {
            if (!isAdmin) {
                // Users only chat with support, clear any cached data
                localStorage.removeItem("unifiedchat_contacts");
            } else {
                // Admin: restore contacts
                const savedContacts = localStorage.getItem(
                    "unifiedchat_contacts",
                );
                if (savedContacts) {
                    let parsed = JSON.parse(savedContacts) || [];
                    setContacts(mergeContacts(parsed, []));
                }
            }
            const params = new URLSearchParams(window.location.search);
            const chatId = params.get("chat");
            if (chatId) {
                const savedMessages = localStorage.getItem(
                    `unifiedchat_messages_${chatId}`,
                );
                if (savedMessages) setMessages(JSON.parse(savedMessages));
            }
        } catch (err) {
            console.error(
                "Failed to restore persisted chat UI state on mount:",
                err,
            );
        }
        setLocalLoaded(true);
    }, []);

    useEffect(() => {
        if (!localLoaded) return;
        const params = new URLSearchParams(window.location.search);
        const chatId = params.get("chat");
        fetchChatData(chatId);
    }, [localLoaded]);

    // Load any saved UI state (contacts/messages) from localStorage to minimize data loss on refresh
    useEffect(() => {
        try {
            const savedContacts = localStorage.getItem("unifiedchat_contacts");
            if (savedContacts) {
                setContacts(JSON.parse(savedContacts));
            }
            // Optionally restore active chat messages if present
            const params = new URLSearchParams(window.location.search);
            const chatId = params.get("chat");
            if (chatId) {
                const savedMessages = localStorage.getItem(
                    `unifiedchat_messages_${chatId}`,
                );
                if (savedMessages) {
                    setMessages(JSON.parse(savedMessages));
                }
            }
        } catch (err) {
            console.error("Failed to restore local UI state:", err);
        }
    }, []);

    // Persist contacts and messages to localStorage to avoid data loss on reload if server is down (admin only)
    useEffect(() => {
        if (isAdmin) {
            localStorage.setItem(
                "unifiedchat_contacts",
                JSON.stringify(mergeContacts(contacts || [], [])),
            );
        }
    }, [contacts]);
    useEffect(() => {
        if (
            isAdmin &&
            typeof activeChat?.user?.id !== "undefined" &&
            activeChat?.user?.id !== null
        ) {
            try {
                localStorage.setItem(
                    `unifiedchat_messages_${activeChat.user.id}`,
                    JSON.stringify(messages),
                );
            } catch (err) {
                console.error(
                    "Failed to persist messages to localStorage",
                    err,
                );
            }
        }
    }, [messages, activeChat?.user?.id]);

    // Removed aggressive canonicalization useEffect - it was causing duplicate support entries

    // Update URL when active chat changes
    useEffect(() => {
        if (activeChat?.user?.id) {
            const url = new URL(window.location);
            url.searchParams.set("chat", activeChat.user.id);
            window.history.pushState({}, "", url);
        }
    }, [activeChat?.user?.id]);

    // Poll for new messages every 5 seconds (only for admin)
    useEffect(() => {
        if (
            !isAdmin ||
            typeof activeChat?.user?.id === "undefined" ||
            activeChat?.user?.id === null
        )
            return;

        const interval = setInterval(async () => {
            try {
                const endpoint = `/api/admin/chat/conversation/${activeChat.user.id}`;
                const response = await fetch(endpoint);
                if (response.ok) {
                    const data = await response.json();
                    const serverMessages = (data.activeChat?.messages || []).map(normalizeServerMessage);
                    
                    // Only update if server has different messages (by ID)
                    const serverIds = new Set(serverMessages.map(m => m.id));
                    const clientIds = new Set(messages.map(m => m.id));
                    
                    const hasNewMessages = serverMessages.some(m => !clientIds.has(m.id));
                    
                    if (hasNewMessages || serverMessages.length > messages.length) {
                        console.log("Polling found new messages, updating state");
                        setMessages(serverMessages);
                        scrollToBottom();
                    }
                }
            } catch (error) {
                console.error("Failed to poll for new messages:", error);
            }
        }, 15000); // Increased to 15s to reduce race conditions

        return () => clearInterval(interval);
    }, [activeChat?.user?.id, messages.length, isAdmin]);

    // Listen for real-time messages
    useEffect(() => {
        // Only subscribe via Echo for non-admin clients (server broadcasts to user channels)
        if (isAdmin) return; // admin should use polling / server-side notifications
        const listenerId = window.user?.id;
        if (!listenerId || !window.Echo) return;

        const channel = window.Echo.private("chat." + listenerId).listen(
            ".message.sent",
            (e) => {
                console.log("New message received via Echo:", e);
                const viewerId = isAdmin ? window.admin?.id : window.user?.id;

                // Normalize incoming message structure
                const incoming = {
                    id: e.id,
                    content: e.content,
                    sender_type: e.sender_type,
                    sender_id: e.sender_id,
                    receiver_id: e.receiver_id,
                    time: e.time,
                    type: e.type,
                    attachment: e.attachment,
                };

                // Determine if this message should be appended to current chat
                // For user-to-user messages: show if sender or receiver matches active chat
                // For support messages: show if it's admin<->user conversation
                const isUserToUser = incoming.receiver_id != null;
                let shouldAppend = false;

                if (isUserToUser) {
                    // User-to-user message
                    const otherUserId =
                        incoming.sender_id === viewerId
                            ? incoming.receiver_id
                            : incoming.sender_id;
                    shouldAppend = activeChat?.user?.id === otherUserId;
                } else {
                    // Support message
                    if (isAdmin) {
                        // Admin sees messages from the user they're chatting with
                        shouldAppend =
                            activeChat?.user?.id === incoming.sender_id;
                    } else {
                        // User sees all support messages
                        shouldAppend = true;
                    }
                }

                // Only add message if it should be displayed in current chat
                // The addOrReplaceMessage function handles deduplication
                if (shouldAppend) {
                    addOrReplaceMessage(incoming);
                }

                // For admin only: Update contacts with incoming message
                if (isAdmin) {
                    setContacts((prev) => {
                        const senderId = incoming.sender_id;
                        const existingIndex = (prev || []).findIndex(
                            (c) => Number(c.id) === Number(senderId),
                        );
                        const isActive = activeChat?.user?.id === senderId;
                        if (existingIndex >= 0) {
                            const newContacts = [...(prev || [])];
                            newContacts[existingIndex] = {
                                ...newContacts[existingIndex],
                                last_message: incoming.content,
                                time: incoming.time,
                                unread: isActive
                                    ? 0
                                    : (newContacts[existingIndex].unread || 0) +
                                      1,
                            };
                            // Move to top unless the conversation is currently active
                            if (!newContacts[existingIndex].active) {
                                newContacts.splice(
                                    0,
                                    0,
                                    newContacts.splice(existingIndex, 1)[0],
                                );
                            }
                            return newContacts;
                        } else {
                            // Add new contact if not exists
                            const name =
                                senderId === 0
                                    ? "Slice Support"
                                    : "Unknown User";
                            const avatar =
                                senderId === 0
                                    ? null
                                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}`;
                            return [
                                {
                                    id: senderId,
                                    name: name,
                                    avatar: avatar,
                                    last_message: incoming.content,
                                    time: incoming.time,
                                    unread: isActive ? 0 : 1,
                                    status: "online",
                                    active: false,
                                },
                                ...(prev || []),
                            ];
                        }
                    });
                }
                scrollToBottom();
            },
        );

        return () => {
            window.Echo.leave("chat." + listenerId);
        };
    }, []);
    const fetchChatData = async (chatId = null) => {
        try {
            let endpoint = isAdmin ? "/api/admin/chat/data" : "/api/chat/data";
            if (chatId) {
                endpoint += `?user_id=${chatId}`;
            }

            console.log(`📡 Fetching ${role} chat data from ${endpoint}`);
            const response = await fetch(endpoint);
            console.log("📥 Response status:", response.status);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log(`📊 ${role} chat data received:`, data);

            if (isAdmin) {
                const srvContacts = data.contacts || [];
                const merged = mergeContacts(srvContacts, contacts || []);
                setContacts(() => merged);
                console.debug(
                    "fetchChatData (admin): merged contacts count",
                    merged.length,
                );
                if (data.activeChat && data.activeChat.user) {
                    setActiveChat(data.activeChat);
                    const serverMsgs = (data.activeChat?.messages || []).map(
                        normalizeServerMessage,
                    );
                    if (serverMsgs.length === 0) {
                        const saved = localStorage.getItem(
                            `unifiedchat_messages_${data.activeChat.user.id}`,
                        );
                        setMessages(saved ? JSON.parse(saved) : []);
                    } else {
                        setMessages(serverMsgs);
                    }
                }
            } else {
                // For non-admin users: just set the single support conversation
                const serverConvos = (data.conversations || []).map((conv) => {
                    // Flatten the structure - backend returns { user: {...}, last_message, ... }
                    // but we need { id, name, avatar, last_message, ... }
                    if (conv.user) {
                        return {
                            id: conv.user.id,
                            name: conv.user.name,
                            avatar: conv.user.avatar,
                            email: conv.user.email || "support@slice.com",
                            status: conv.user.online ? "online" : "offline",
                            last_message: conv.last_message || "",
                            time: conv.last_message_time || "",
                            unread: conv.unread_count || 0,
                            active: conv.active || false,
                        };
                    }
                    return conv;
                });
                setContacts(serverConvos);
                console.debug(
                    "fetchChatData (non-admin): set contacts count",
                    serverConvos.length,
                );

                if (data.activeChat && data.activeChat.user) {
                    setActiveChat(data.activeChat);
                    const serverMsgs = (data.activeChat?.messages || []).map(
                        normalizeServerMessage,
                    );
                    setMessages(serverMsgs);
                }
            }

            setLoading(false);
        } catch (error) {
            console.error(`❌ Failed to fetch ${role} chat data:`, error);
            setError("Failed to load chat data. Please refresh the page.");
            setLoading(false);
        }
    };

    const switchConversation = async (userId) => {
        try {
            const numId = Number(userId);
            if (isNaN(numId) || numId === undefined || numId === null) {
                console.warn("Invalid userId for switchConversation:", userId);
                return;
            }

            // Find the contact being switched to
            const contact = contacts.find((c) => Number(c.id) === numId);
            if (!contact) {
                console.warn("Contact not found:", numId);
                return;
            }

            // For non-admin users, fetch conversation data from server
            if (!isAdmin) {
                try {
                    // Fetch the specific conversation
                    const endpoint = `/api/chat/conversation/${numId}`;
                    const response = await fetch(endpoint);

                    if (!response.ok) {
                        throw new Error(
                            `Failed to fetch conversation: ${response.status}`,
                        );
                    }

                    const data = await response.json();

                    if (!data.activeChat) {
                        console.error("No activeChat in response");
                        return;
                    }

                    setActiveChat(data.activeChat);
                    setMessages(
                        data.activeChat.messages.map(normalizeServerMessage),
                    );
                    setContacts((prev) =>
                        (prev || []).map((c) => ({
                            ...c,
                            active: Number(c.id) === numId,
                        })),
                    );
                } catch (error) {
                    console.error("Failed to fetch conversation:", error);
                }
                return;
            }

            // For admin, fetch conversation from server
            const mappedId = getMappedContactId(numId);
            if (isNaN(mappedId)) {
                console.warn("Invalid mappedId:", mappedId);
                return;
            }

            const endpoint = `/api/admin/chat/conversation/${mappedId}`;
            const data = await safeFetchJson(endpoint);
            if (!data?.activeChat) {
                console.error(
                    "Conversation not found in server response for",
                    mappedId,
                );
                return;
            }
            setActiveChat(data.activeChat);
            setMessages(
                (data.activeChat.messages || []).map(normalizeServerMessage),
            );
            setContacts((prev) =>
                (prev || []).map((c) => ({
                    ...c,
                    active: Number(c.id) === Number(mappedId),
                })),
            );
        } catch (error) {
            console.error("Failed to switch conversation:", error);
            if (error.status === 404) {
                console.warn(
                    "Conversation not found (404), user may not have messages yet",
                );
            }
        }
    };

    const fetchAllUsers = async () => {
        try {
            console.log("📡 Fetching all users...");
            // Use the appropriate endpoint based on role
            const endpoint = isAdmin ? "/api/admin/users" : "/api/users";
            const response = await fetch(endpoint);

            if (response.ok) {
                let users = await response.json();
                console.log("👥 Users fetched:", users.length);

                // Deduplicate and normalize allUsers and canonicalize support duplicates
                setAllUsers(mergeContacts(users, []));
            }
        } catch (error) {
            console.error("Failed to fetch all users:", error);
            // Removed dumb fallback that forces support chat
        }
    };

    const startNewChat = (user) => {
        // Create a new chat with this user
        const avatarUrl = user.profile_photo
            ? user.profile_photo.startsWith("http")
                ? user.profile_photo
                : `${window.location.origin}/storage/${user.profile_photo}`
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`;

        setActiveChat({
            user: {
                id: user.id,
                name: user.name,
                avatar: avatarUrl,
                status: "Online",
            },
            messages: [],
        });
        setMessages([]);
        setNewChatOpen(false);
        setSearchTerm("");

        // Add to contacts if not already there
        setContacts((prev) => {
            if ((prev || []).some((c) => Number(c.id) === Number(user.id))) {
                return (prev || []).map((c) => ({
                    ...c,
                    active: Number(c.id) === Number(user.id),
                }));
            }
            return [
                {
                    id: user.id,
                    name: user.name,
                    avatar: avatarUrl,
                    last_message: "",
                    time: "now",
                    unread: 0,
                    status: "online",
                    active: true,
                },
                ...(prev || []),
            ];
        });
    };

    const handleFileSelect = async (e) => {
        const file = e.target.files[0];
        if (!file) return;

        console.log("File selected:", file.name, file.type, file.size);

        if (file.size > 10 * 1024 * 1024) {
            alert("File size must be less than 10MB");
            return;
        }

        // Check if it's an image - if yes, scan it with NSFWJS
        if (file.type.startsWith("image/")) {
            try {
                console.log("🔍 Scanning image for inappropriate content...");
                const result = await checkImage(file);
                
                console.log("Scan result:", result);

                if (!result.safe) {
                    // CONTENT BLOCKED - Replace with placeholder
                    console.warn("⛔ Blocked inappropriate content:", result.reason);
                    alert(
                        `🛑 Image blocked: ${result.reason}\n\n` +
                        `This image appears to contain inappropriate content and cannot be uploaded.\n\n` +
                        `Detection scores:\n` +
                        `• Porn: ${(result.scores.porn * 100).toFixed(1)}%\n` +
                        `• Hentai: ${(result.scores.hentai * 100).toFixed(1)}%\n` +
                        `• Sexy: ${(result.scores.sexy * 100).toFixed(1)}%`
                    );
                    
                    // Clear the file input
                    if (e.target) {
                        e.target.value = null;
                    }
                    return;
                }

                console.log("✅ Image passed content check");
            } catch (error) {
                console.error("Error scanning image:", error);
                // On error, allow the image but log it
                console.warn("⚠️ Content scanning failed, allowing image");
            }

            // Image is safe or scanning failed - proceed with preview
            const reader = new FileReader();
            reader.onload = (e) => {
                setImagePreviewUrl(e.target.result);
                setFileModalOpen(false);
                setFilePreviewOpen(true);
            };
            reader.readAsDataURL(file);
        } else {
            setImagePreviewUrl(null);
            setFileModalOpen(false);
            setFilePreviewOpen(true);
        }

        setSelectedFile(file);

        console.log("File preview modal should be open now");
    };

    const triggerFileInput = (accept) => {
        console.log("Triggering file input with accept:", accept);
        if (fileInputRef.current) {
            fileInputRef.current.accept = accept;
            fileInputRef.current.click();
            console.log("File input clicked");
        } else {
            console.log("File input ref not found");
        }
    };

    const sendFileMessage = async () => {
        if (!selectedFile) return;
        if (
            isAdmin &&
            (typeof activeChat?.user?.id === "undefined" ||
                activeChat?.user?.id === null)
        )
            return;

        setUploading(true);
        const formData = new FormData();
        formData.append("file", selectedFile);

        // For users: only send user_id if chatting with another user (not admin/support)
        if (
            !isAdmin &&
            activeChat?.user &&
            !isAdminOrSupport(activeChat.user)
        ) {
            formData.append("user_id", getMappedContactId(activeChat.user.id));
        }
        // For admins: always send user_id
        else if (isAdmin && activeChat?.user?.id) {
            formData.append("user_id", getMappedContactId(activeChat.user.id));
        }

        formData.append("message", fileCaption);

        try {
            // Don't add temp message to UI - just show it's uploading
            const clientTempId = `temp-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;
            
            const endpoint = isAdmin
                ? "/api/admin/chat/upload"
                : "/api/chat/upload";
            const response = await fetchWithCsrf(endpoint, {
                method: "POST",
                body: formData,
            });

            // Check if response is JSON
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                throw new Error("Server returned non-JSON response");
            }

            const data = await response.json();

            if (response.ok && data.success) {
                console.debug(
                    "sendFileMessage: server returned file message",
                    data.message.id,
                );
                // Close preview FIRST, THEN add the server message
                setFilePreviewOpen(false);
                setSelectedFile(null);
                setFileCaption("");
                setImagePreviewUrl(null);
                // Now add the message after preview is gone
                addOrReplaceMessage(data.message);
            } else {
                // Handle API error responses
                let errorMessage = data.message || "Failed to upload file";

                // Specific handling for 422 (Validation Error) which often means file too large in this context
                if (
                    response.status === 422 &&
                    errorMessage.includes("The file failed to upload")
                ) {
                    errorMessage = "File too large (max 10MB).";
                }

                console.error("Upload failed:", errorMessage);
                alert(errorMessage);
            }
        } catch (error) {
            console.error("File upload failed:", error);
            if (error.message.includes("JSON")) {
                alert(
                    "Server error: Please check your connection and try again",
                );
            } else {
                alert("Failed to upload file: " + error.message);
            }
        } finally {
            setUploading(false);
        }
    };

    const toggleSelection = (messageId) => {
        const newSelected = new Set(selectedMessages);
        if (newSelected.has(messageId)) {
            newSelected.delete(messageId);
        } else {
            newSelected.add(messageId);
        }
        setSelectedMessages(newSelected);

        if (newSelected.size === 0 && selectionMode) {
            // Optional: Auto-exit selection mode if nothing selected?
            // No, Apple keeps it open.
        }
    };

    const copySelectedMessages = () => {
        const selectedContent = messages
            .filter((m) => selectedMessages.has(m.id) && m.content)
            .map((m) => m.content)
            .join("\n\n");

        if (selectedContent) {
            navigator.clipboard.writeText(selectedContent);
            setSelectionMode(false);
            setSelectedMessages(new Set());
            // Could add a toast here
        }
    };

    const deleteSelectedMessages = async () => {
        if (selectedMessages.size === 0) return;

        setConfirmModal({
            isOpen: true,
            title: "Delete Messages?",
            message: `Are you sure you want to delete ${selectedMessages.size} messages? This action cannot be undone.`,
            isDanger: true,
            onConfirm: async () => {
                try {
                    const endpoint = isAdmin
                        ? "/api/admin/chat/messages/delete"
                        : "/api/chat/messages/delete";

                    const response = await fetch(endpoint, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]',
                            ).content,
                        },
                        body: JSON.stringify({
                            message_ids: Array.from(selectedMessages),
                        }),
                    });

                    if (response.ok) {
                        // Optimistic update is handled by realtime event, but we can do it here too for instant feedback
                        setMessages((prev) =>
                            prev.filter((m) => !selectedMessages.has(m.id)),
                        );
                        setSelectionMode(false);
                        setSelectedMessages(new Set());
                    }
                } catch (error) {
                    console.error("Failed to delete messages:", error);
                    alert("Failed to delete messages");
                }
                setConfirmModal((prev) => ({ ...prev, isOpen: false }));
            },
        });
    };

    const clearChat = async () => {
        if (!activeChat?.user?.id) return;

        setConfirmModal({
            isOpen: true,
            title: "Clear Conversation?",
            message:
                "Are you sure you want to delete this entire conversation? This cannot be undone.",
            isDanger: true,
            onConfirm: async () => {
                try {
                    const endpoint = isAdmin
                        ? `/api/admin/chat/conversation/${activeChat.user.id}`
                        : "/api/chat/conversation";

                    const response = await fetch(endpoint, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector(
                                'meta[name="csrf-token"]',
                            ).content,
                        },
                    });

                    if (response.ok) {
                        setMessages([]);
                        setSelectionMode(false);
                        setSelectedMessages(new Set());
                        setMenuOpen(false);
                    }
                } catch (error) {
                    console.error("Failed to clear chat:", error);
                    alert("Failed to clear chat");
                }
                setConfirmModal((prev) => ({ ...prev, isOpen: false }));
            },
        });
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        if (!messageInput.trim()) return;
        if (
            isAdmin &&
            (typeof activeChat?.user?.id === "undefined" ||
                activeChat?.user?.id === null)
        )
            return;

        const message = messageInput.trim();
        setMessageInput("");

        try {
            const endpoint = isAdmin
                ? "/api/admin/chat/send"
                : "/api/chat/send";
            const body = { message: message };

            // For users: only send user_id if chatting with another user (not admin/support)
            if (
                !isAdmin &&
                activeChat?.user &&
                !isAdminOrSupport(activeChat.user)
            ) {
                body.user_id = getMappedContactId(activeChat.user.id);
            }
            // For admins: always send user_id
            else if (
                isAdmin &&
                typeof activeChat?.user?.id !== "undefined" &&
                activeChat?.user?.id !== null
            ) {
                body.user_id = getMappedContactId(activeChat.user.id);
            }

            let data;
            try {
                const response = await fetchWithCsrf(endpoint, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(body),
                });
                
                if (!response.ok) {
                    const errorText = await response.text();
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }
                
                data = await response.json();
            } catch (err) {
                console.error("Failed to send message:", err);
                // Restore input so user can retry
                setMessageInput(message);
                if (err.status === 403) {
                    alert("You are not allowed to send messages in this chat.");
                } else if (err.status === 404) {
                    alert("Conversation not found.");
                } else {
                    alert("Failed to send message: " + err.message);
                }
                return;
            }
            if (data.success) {
                // Replace temp message if present, else safe add
                console.debug(
                    "sendMessage: server response message",
                    data.message.id,
                );
                addOrReplaceMessage(data.message);

                // Update contacts list to show this conversation
                setContacts((prev) => {
                    const id = activeChat.user.id;
                    const has = (prev || []).some(
                        (c) => Number(c.id) === Number(id),
                    );
                    const next = (prev || []).map((c) => ({
                        ...c,
                        active: Number(c.id) === Number(id),
                    }));
                    if (has) {
                        const updated = next
                            .map((c) =>
                                Number(c.id) === Number(id)
                                    ? {
                                          ...c,
                                          last_message: message,
                                          time: "now",
                                          unread: 0,
                                      }
                                    : c,
                            )
                            .sort((a, b) => (a.active ? -1 : 1));
                        return updated;
                    }
                    return [
                        {
                            id: id,
                            name: activeChat.user.name,
                            avatar: activeChat.user.avatar,
                            last_message: message,
                            time: "now",
                            unread: 0,
                            status: activeChat.user.status || "online",
                            active: true,
                        },
                        ...(prev || []),
                    ];
                });
            }
        } catch (error) {
            console.error("Error sending message:", error);
            // Re-add input so user can retry
            setMessageInput(message);
        }
    };

    if (loading) {
        return (
            <div className="flex h-screen items-center justify-center bg-[#0B0C10]">
                <div className="text-white">Loading...</div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="flex h-screen items-center justify-center bg-[#0B0C10]">
                <div className="text-center text-red-400">
                    <div className="mb-4">{error}</div>
                    <button
                        onClick={() => window.location.reload()}
                        className="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-500"
                    >
                        Refresh Page
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div className="flex h-screen overflow-hidden bg-[#0B0C10] font-sans selection:bg-blue-500/30 selection:text-blue-100">
            {/* Admin Sidebar */}
            <div
                className={`${sidebarOpen ? "w-64" : "w-20"} relative z-30 flex shrink-0 flex-col border-r border-white/5 bg-[#121217] transition-all duration-300 ease-in-out`}
            >
                {/* Logo */}
                <div className="flex h-16 items-center justify-between border-b border-white/5 px-4">
                    <div className="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                        <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-linear-to-br from-blue-600 to-purple-600 shadow-lg shadow-blue-500/20">
                            <svg
                                className="h-6 w-6 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2.5"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                />
                            </svg>
                        </div>
                        {sidebarOpen && (
                            <span className="text-lg font-bold tracking-tight text-white">
                                Slice<span className="text-blue-500">.</span>
                            </span>
                        )}
                    </div>

                    {/* Toggle Button */}
                    <button
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        className="absolute top-20 -right-3 z-50 rounded-full border border-[#0B0C10] bg-blue-600 p-1 text-white shadow-lg transition-colors hover:bg-blue-500"
                    >
                        <svg
                            className={`h-3 w-3 transition-transform duration-300 ${!sidebarOpen ? "rotate-180" : ""}`}
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>
                </div>

                {/* Nav Links */}
                <nav
                    className="flex-1 space-y-1 overflow-y-auto px-3 py-6"
                    style={{
                        scrollbarWidth: "thin",
                        scrollbarColor: "rgba(255,255,255,0.1) transparent",
                    }}
                >
                    {isAdmin && (
                        <>
                            {sidebarOpen && (
                                <div className="mb-2 px-3 text-xs font-bold tracking-wider text-slate-500 uppercase">
                                    Operations
                                </div>
                            )}

                            <a
                                href="/admin/dashboard"
                                className="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-400 transition-all hover:bg-white/5 hover:text-white"
                            >
                                <svg
                                    className="h-5 w-5 transition-colors group-hover:text-blue-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                    />
                                </svg>
                                {sidebarOpen && (
                                    <span className="text-sm font-medium whitespace-nowrap">
                                        Overview
                                    </span>
                                )}
                            </a>
                        </>
                    )}

                    {/* Inbox Active */}
                    <div className="group relative flex items-center gap-3 rounded-xl border border-blue-600/20 bg-blue-600/10 px-3 py-2.5 text-white">
                        <div className="absolute top-1/2 left-0 h-8 w-1 -translate-y-1/2 rounded-r-full bg-blue-500"></div>
                        <svg
                            className="ml-1 h-5 w-5 text-blue-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                            />
                        </svg>
                        {sidebarOpen && (
                            <span className="text-sm font-medium whitespace-nowrap">
                                Inbox
                            </span>
                        )}
                    </div>

                    {isAdmin && (
                        <>
                            {/* Users */}
                            <a
                                href="/admin/users"
                                className="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-400 transition-all hover:bg-white/5 hover:text-white"
                            >
                                <svg
                                    className="h-5 w-5 transition-colors group-hover:text-purple-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                    />
                                </svg>
                                {sidebarOpen && (
                                    <span className="text-sm font-medium whitespace-nowrap">
                                        Users
                                    </span>
                                )}
                            </a>

                            {/* Broadcasts */}
                            <a
                                href="/admin/notifications"
                                className="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-400 transition-all hover:bg-white/5 hover:text-white"
                            >
                                <svg
                                    className="h-5 w-5 transition-colors group-hover:text-yellow-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                    />
                                </svg>
                                {sidebarOpen && (
                                    <span className="text-sm font-medium whitespace-nowrap">
                                        Broadcasts
                                    </span>
                                )}
                            </a>
                        </>
                    )}
                </nav>

                {/* Admin Profile */}
                <div className="border-t border-white/5 p-4">
                    <div className="relative">
                        <button
                            onClick={() => setProfileOpen(!profileOpen)}
                            className="group flex w-full items-center gap-3 rounded-xl p-2 transition-colors hover:bg-white/5"
                        >
                            <img
                                src={
                                    (isAdmin
                                        ? window.admin?.avatar
                                        : window.user?.avatar) ||
                                    `https://ui-avatars.com/api/?name=${isAdmin ? "Admin" : "User"}`
                                }
                                className="h-8 w-8 rounded-full ring-2 ring-white/10 transition-all group-hover:ring-white/30"
                                alt=""
                            />
                            {sidebarOpen && (
                                <>
                                    <div className="min-w-0 flex-1 text-left">
                                        <p className="truncate text-sm font-medium text-white">
                                            {(isAdmin
                                                ? window.admin?.name
                                                : window.user?.name) || "User"}
                                        </p>
                                        <p className="truncate text-xs text-slate-500">
                                            {isAdmin
                                                ? "Administrator"
                                                : "Member"}
                                        </p>
                                    </div>
                                    <svg
                                        className="h-4 w-4 text-slate-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M8 9l4-4 4 4m0 6l-4 4-4-4"
                                        />
                                    </svg>
                                </>
                            )}
                        </button>

                        {profileOpen && (
                            <div
                                className={`absolute bottom-full z-50 mb-2 overflow-hidden rounded-xl border border-white/10 bg-[#1A1B21] shadow-xl ${
                                    sidebarOpen
                                        ? "left-0 w-full"
                                        : "left-full ml-2 w-48"
                                }`}
                                onClick={(e) => e.stopPropagation()}
                            >
                                {!isAdmin && (
                                    <a
                                        href="/settings"
                                        className="flex w-full items-center gap-2 px-4 py-3 text-sm text-slate-300 transition-colors hover:bg-white/5 hover:text-white"
                                    >
                                        <svg
                                            className="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                            />
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        Settings
                                    </a>
                                )}
                                <form method="POST" action="/logout">
                                    <input
                                        type="hidden"
                                        name="_token"
                                        value={
                                            document.querySelector(
                                                'meta[name="csrf-token"]',
                                            )?.content
                                        }
                                    />
                                    <button
                                        type="submit"
                                        className="flex w-full items-center gap-2 px-4 py-3 text-sm text-red-400 transition-colors hover:bg-white/5 hover:text-red-300"
                                    >
                                        <svg
                                            className="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                            />
                                        </svg>
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* Contacts List */}
            <div className="z-20 flex w-80 flex-col border-r border-white/5 bg-[#0F1015]">
                <div className="flex h-16 items-center justify-between border-b border-white/5 bg-[#0F1015] px-6">
                    <h2 className="text-lg font-bold text-white">Inbox</h2>
                    <div className="flex gap-2">
                        {/* Filter button */}
                        <button
                            onClick={() => setFilterOpen(!filterOpen)}
                            className="relative rounded-lg p-2 text-slate-400 transition-colors hover:bg-white/5 hover:text-white"
                        >
                            <svg
                                className="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                                />
                            </svg>
                            {/* Filter Dropdown */}
                            {filterOpen && (
                                <div className="absolute top-full right-0 z-50 mt-2 w-48 overflow-hidden rounded-xl border border-white/10 bg-[#1A1B21] shadow-xl">
                                    <button
                                        onClick={() => {
                                            setContactFilter("all");
                                            setFilterOpen(false);
                                        }}
                                        className={`w-full px-4 py-3 text-left text-sm transition-colors ${contactFilter === "all" ? "bg-blue-600/20 text-blue-400" : "text-slate-300 hover:bg-white/5"}`}
                                    >
                                        All Chats
                                    </button>
                                    <button
                                        onClick={() => {
                                            setContactFilter("unread");
                                            setFilterOpen(false);
                                        }}
                                        className={`w-full px-4 py-3 text-left text-sm transition-colors ${contactFilter === "unread" ? "bg-blue-600/20 text-blue-400" : "text-slate-300 hover:bg-white/5"}`}
                                    >
                                        Unread Only
                                    </button>
                                    <button
                                        onClick={() => {
                                            setContactFilter("favorites");
                                            setFilterOpen(false);
                                        }}
                                        className={`w-full px-4 py-3 text-left text-sm transition-colors ${contactFilter === "favorites" ? "bg-blue-600/20 text-blue-400" : "text-slate-300 hover:bg-white/5"}`}
                                    >
                                        Favorites
                                    </button>
                                </div>
                            )}
                        </button>
                        {/* Add button - For ALL users now */}
                        <button
                            onClick={() => {
                                setNewChatOpen(true);
                                fetchAllUsers();
                            }}
                            className="rounded-lg p-2 text-slate-400 transition-colors hover:bg-white/5 hover:text-white"
                        >
                            <svg
                                className="h-5 w-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Search */}
                <div className="px-4 py-3">
                    <div className="group relative">
                        <svg
                            className="pointer-events-none absolute inset-y-0 left-0 flex h-full w-10 items-center pl-3 text-slate-500"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                        <input
                            type="text"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            placeholder="Search messages..."
                            className="w-full rounded-lg border border-white/5 bg-[#1A1B21] py-2 pr-4 pl-10 text-sm text-slate-300 placeholder-slate-600 transition-all focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 focus:outline-none"
                        />
                    </div>
                </div>

                {/* New Chat Modal - AirDrop Style */}
                {newChatOpen && (
                    <div className="absolute inset-0 z-50 flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm">
                        <div className="flex max-h-[85vh] w-full max-w-2xl flex-col rounded-2xl border border-white/20 bg-[#1A1B21]/95 shadow-2xl backdrop-blur-xl">
                            {/* Modal Header */}
                            <div className="flex items-center justify-between border-b border-white/10 px-6 py-5">
                                <h3 className="text-xl font-bold text-white">
                                    Start New Chat
                                </h3>
                                <button
                                    onClick={() => {
                                        setNewChatOpen(false);
                                        setSearchTerm("");
                                    }}
                                    className="rounded-lg p-2 text-slate-400 transition-all hover:bg-white/5 hover:text-white"
                                >
                                    <svg
                                        className="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>

                            {/* Search */}
                            <div className="border-b border-white/10 px-6 py-4">
                                <div className="relative">
                                    <svg
                                        className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-500"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                    <input
                                        type="text"
                                        value={searchTerm}
                                        onChange={(e) =>
                                            setSearchTerm(e.target.value)
                                        }
                                        placeholder="Search users..."
                                        className="w-full rounded-xl border border-white/10 bg-[#0F1015] py-3 pr-4 pl-10 text-sm text-white placeholder-slate-500 transition-all focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                        autoFocus
                                    />
                                </div>
                            </div>

                            {/* User Grid - AirDrop Style */}
                            <div className="flex-1 overflow-y-auto p-6">
                                {/* Admins Section */}
                                {allUsers
                                    .filter((u) => u.is_admin)
                                    .filter(
                                        (user) =>
                                            user.name
                                                .toLowerCase()
                                                .includes(
                                                    searchTerm.toLowerCase(),
                                                ) ||
                                            user.email
                                                .toLowerCase()
                                                .includes(
                                                    searchTerm.toLowerCase(),
                                                ),
                                    ).length > 0 && (
                                    <div className="mb-6">
                                        <h4 className="mb-3 px-2 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                                            Admins & Support
                                        </h4>
                                        <div className="grid grid-cols-4 gap-6">
                                            {allUsers
                                                .filter((u) => u.is_admin)
                                                .filter(
                                                    (user) =>
                                                        user.name
                                                            .toLowerCase()
                                                            .includes(
                                                                searchTerm.toLowerCase(),
                                                            ) ||
                                                        user.email
                                                            .toLowerCase()
                                                            .includes(
                                                                searchTerm.toLowerCase(),
                                                            ),
                                                )
                                                .map((user) => (
                                                    <button
                                                        key={user.id}
                                                        onClick={() =>
                                                            startNewChat(user)
                                                        }
                                                        className="group flex flex-col items-center gap-3 rounded-2xl p-4 transition-all hover:bg-white/5"
                                                    >
                                                        <div className="relative">
                                                            <img
                                                                src={
                                                                    user.profile_photo
                                                                        ? user.profile_photo.startsWith(
                                                                              "http",
                                                                          )
                                                                            ? user.profile_photo
                                                                            : `${window.location.origin}/storage/${user.profile_photo}`
                                                                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=128&background=3B82F6&color=fff`
                                                                }
                                                                className="h-20 w-20 rounded-full ring-2 ring-blue-500/30 transition-all group-hover:ring-blue-500/70"
                                                                alt=""
                                                            />
                                                            <div className="absolute -right-1 -bottom-1 flex h-7 w-7 items-center justify-center rounded-full border-2 border-[#1A1B21] bg-blue-600">
                                                                <svg
                                                                    className="h-4 w-4 text-white"
                                                                    fill="currentColor"
                                                                    viewBox="0 0 20 20"
                                                                >
                                                                    <path
                                                                        fillRule="evenodd"
                                                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clipRule="evenodd"
                                                                    />
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div className="w-full text-center">
                                                            <p className="truncate text-sm font-medium text-white">
                                                                {user.name}
                                                            </p>
                                                            <span className="mt-1 inline-block rounded-full border border-blue-500/30 bg-blue-500/20 px-2 py-0.5 text-[10px] font-medium text-blue-400">
                                                                ADMIN
                                                            </span>
                                                        </div>
                                                    </button>
                                                ))}
                                        </div>
                                    </div>
                                )}

                                {/* Users Section */}
                                {allUsers
                                    .filter((u) => !u.is_admin)
                                    .filter(
                                        (user) =>
                                            user.name
                                                .toLowerCase()
                                                .includes(
                                                    searchTerm.toLowerCase(),
                                                ) ||
                                            user.email
                                                .toLowerCase()
                                                .includes(
                                                    searchTerm.toLowerCase(),
                                                ),
                                    ).length > 0 && (
                                    <div>
                                        <h4 className="mb-3 px-2 text-xs font-semibold tracking-wider text-slate-500 uppercase">
                                            Users
                                        </h4>
                                        <div className="grid grid-cols-4 gap-6">
                                            {allUsers
                                                .filter((u) => !u.is_admin)
                                                .filter(
                                                    (user) =>
                                                        user.name
                                                            .toLowerCase()
                                                            .includes(
                                                                searchTerm.toLowerCase(),
                                                            ) ||
                                                        user.email
                                                            .toLowerCase()
                                                            .includes(
                                                                searchTerm.toLowerCase(),
                                                            ),
                                                )
                                                .map((user) => (
                                                    <button
                                                        key={user.id}
                                                        onClick={() =>
                                                            startNewChat(user)
                                                        }
                                                        className="group flex flex-col items-center gap-3 rounded-2xl p-4 transition-all hover:bg-white/5"
                                                    >
                                                        <div className="relative">
                                                            <img
                                                                src={
                                                                    user.profile_photo
                                                                        ? user.profile_photo.startsWith(
                                                                              "http",
                                                                          )
                                                                            ? user.profile_photo
                                                                            : `${window.location.origin}/storage/${user.profile_photo}`
                                                                        : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=128`
                                                                }
                                                                className="h-20 w-20 rounded-full ring-2 ring-white/10 transition-all group-hover:ring-blue-500/50"
                                                                alt=""
                                                            />
                                                            <div className="absolute -right-1 -bottom-1 h-6 w-6 rounded-full border-2 border-[#1A1B21] bg-green-500"></div>
                                                        </div>
                                                        <div className="w-full text-center">
                                                            <p className="truncate text-sm font-medium text-white">
                                                                {user.name}
                                                            </p>
                                                            <p className="mt-0.5 truncate text-xs text-slate-500">
                                                                {user.email?.split(
                                                                    "@",
                                                                )[0] || "User"}
                                                            </p>
                                                        </div>
                                                    </button>
                                                ))}
                                        </div>
                                    </div>
                                )}

                                {allUsers.filter(
                                    (user) =>
                                        user.name
                                            .toLowerCase()
                                            .includes(
                                                searchTerm.toLowerCase(),
                                            ) ||
                                        user.email
                                            .toLowerCase()
                                            .includes(searchTerm.toLowerCase()),
                                ).length === 0 && (
                                    <div className="flex h-64 flex-col items-center justify-center text-slate-500">
                                        <svg
                                            className="mb-4 h-16 w-16 opacity-50"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                            />
                                        </svg>
                                        <p className="text-sm">
                                            No users found
                                        </p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                )}

                {/* Contacts List */}
                <div className="flex-1 overflow-y-auto">
                    {contacts &&
                    contacts.filter((c) => c.last_message).length === 0
                        ? // User requested to hide placeholder if chat is 0
                          null
                        : contacts
                              .filter((c) => c.last_message || c.active) // Show if it has messages OR is the currently active one (so you don't lose it while chatting)
                              .map((contact) => (
                                  <div
                                      key={contact.id}
                                      onClick={() =>
                                          switchConversation(contact.id)
                                      }
                                      className={`group cursor-pointer border-b border-white/5 px-4 py-3 transition-all hover:bg-white/2 ${contact.active ? "border-l-2 border-l-blue-500 bg-blue-500/5" : "border-l-2 border-l-transparent"}`}
                                  >
                                      <div className="flex items-start gap-3">
                                          <div className="relative shrink-0">
                                              <img
                                                  src={contact.avatar}
                                                  alt=""
                                                  className="h-10 w-10 rounded-full object-cover ring-2 ring-white/5 transition-all group-hover:ring-white/10"
                                              />
                                              {contact.status === "online" && (
                                                  <div className="absolute right-0 bottom-0 h-2.5 w-2.5 rounded-full border-2 border-[#0F1015] bg-green-500"></div>
                                              )}
                                          </div>

                                          <div className="min-w-0 flex-1">
                                              <div className="mb-0.5 flex items-baseline justify-between">
                                                  <h3 className="truncate text-sm font-semibold text-white">
                                                      {contact.name ||
                                                          contact.user?.name}
                                                  </h3>
                                                  <span className="text-[10px] text-slate-500">
                                                      {contact.last_message_time ||
                                                          contact.time ||
                                                          ""}
                                                  </span>
                                              </div>
                                              <p className="truncate text-xs text-slate-400 transition-colors group-hover:text-slate-300">
                                                  {contact.last_message ||
                                                      "No messages yet"}
                                              </p>
                                          </div>

                                          {(contact.unread_count > 0 ||
                                              contact.unread > 0) && (
                                              <div className="ml-2 shrink-0 self-center">
                                                  <span className="inline-flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-[10px] font-bold text-white shadow-lg shadow-blue-600/30">
                                                      {contact.unread_count ||
                                                          contact.unread}
                                                  </span>
                                              </div>
                                          )}
                                      </div>
                                  </div>
                              ))}
                </div>
            </div>

            {/* Chat Area */}
            <div className="relative z-10 flex flex-1 flex-col bg-[#0B0C10]">
                {activeChat?.user ? (
                    <>
                        {/* Header */}
                        <div className="flex h-16 items-center justify-between border-b border-white/5 bg-[#0B0C10]/80 px-6 backdrop-blur-xl">
                            <div className="flex items-center gap-4">
                                <div className="relative">
                                    <img
                                        src={activeChat.user.avatar}
                                        className="h-9 w-9 rounded-full object-cover ring-2 ring-white/10"
                                        alt=""
                                    />
                                    <div className="absolute right-0 bottom-0 h-2.5 w-2.5 rounded-full border-2 border-[#0B0C10] bg-green-500"></div>
                                </div>
                                <div>
                                    <h3 className="flex items-center gap-2 text-sm font-bold text-white">
                                        {activeChat.user.name}
                                        <span className="rounded-md border border-white/5 bg-white/5 px-2 py-0.5 text-[10px] font-normal tracking-wide text-slate-400 uppercase">
                                            Client
                                        </span>
                                    </h3>
                                    <p className="mt-0.5 flex items-center gap-1.5 text-xs text-green-400">
                                        <span className="h-1 w-1 rounded-full bg-green-400"></span>
                                        {activeChat.user.status}
                                    </p>
                                </div>
                            </div>

                            {/* Header Actions */}
                            <div className="flex items-center gap-2">
                                <button
                                    onClick={() => {
                                        setSelectionMode(!selectionMode);
                                        setSelectedMessages(new Set());
                                    }}
                                    className={`rounded-lg p-2 transition-colors ${selectionMode ? "bg-blue-400/10 text-blue-400" : "text-slate-400 hover:bg-white/5 hover:text-white"}`}
                                    title="Select Messages"
                                >
                                    <svg
                                        className="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </button>

                                <div className="group/menu relative">
                                    <button className="rounded-lg p-2 text-slate-400 transition-colors hover:bg-white/5 hover:text-white">
                                        <svg
                                            className="h-5 w-5"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                                            />
                                        </svg>
                                    </button>

                                    {/* Dropdown Menu */}
                                    <div className="absolute top-full right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-white/10 bg-[#1A1B21] shadow-xl group-hover/menu:block">
                                        <button
                                            onClick={clearChat}
                                            className="flex w-full items-center gap-2 px-4 py-3 text-left text-sm text-red-400 transition-colors hover:bg-white/5 hover:text-red-300"
                                        >
                                            <svg
                                                className="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                />
                                            </svg>
                                            Clear Chat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Messages */}
                        <div className="flex-1 space-y-6 overflow-y-auto bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')] p-6">
                            <div className="flex justify-center py-4">
                                <span className="rounded-full border border-white/5 bg-white/5 px-3 py-1 text-[10px] font-medium text-slate-500">
                                    Start of conversation
                                </span>
                            </div>

                            {messages.map((message, idx) => (
                                <div
                                    key={message.id || idx}
                                    className={`group flex items-end gap-3 ${message.sender === "me" ? "justify-end" : "justify-start"} ${selectionMode ? "cursor-pointer" : ""}`}
                                    onClick={() =>
                                        selectionMode &&
                                        toggleSelection(message.id)
                                    }
                                >
                                    {/* Selection Circle */}
                                    {selectionMode && (
                                        <div
                                            className={`flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-all ${
                                                selectedMessages.has(message.id)
                                                    ? "border-blue-500 bg-blue-500"
                                                    : "border-slate-600 group-hover:border-slate-400"
                                            }`}
                                        >
                                            {selectedMessages.has(
                                                message.id,
                                            ) && (
                                                <svg
                                                    className="h-3 w-3 text-white"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="3"
                                                        d="M5 13l4 4L19 7"
                                                    />
                                                </svg>
                                            )}
                                        </div>
                                    )}
                                    {message.sender === "them" && (
                                        <img
                                            src={activeChat.user.avatar}
                                            className="mt-1 mr-3 mb-1 h-8 w-8 self-end rounded-full opacity-70"
                                            alt=""
                                        />
                                    )}

                                    <div className="max-w-[70%]">
                                        {/* Image Message Style */}
                                        {message.attachment &&
                                        (message.attachment.type === "image" || 
                                         /\.(jpg|jpeg|png|gif|webp|bmp|svg)$/i.test(message.attachment.name)) ? (
                                            <div className="flex flex-col">
                                                <div className="group/image relative">
                                                    <img
                                                        src={
                                                            message.attachment
                                                                .url
                                                        }
                                                        alt={
                                                            message.attachment
                                                                .name
                                                        }
                                                        className={`h-auto max-w-[280px] cursor-pointer rounded-2xl shadow-sm transition-opacity hover:opacity-95 ${
                                                            message.content
                                                                ? "rounded-b-none"
                                                                : ""
                                                        }`}
                                                        onClick={(e) => {
                                                            if (selectionMode)
                                                                return; // Let parent handle selection
                                                            e.stopPropagation();
                                                            // Open lightbox
                                                            const lightbox =
                                                                document.createElement(
                                                                    "div",
                                                                );
                                                            lightbox.className =
                                                                "fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-in fade-in duration-200";
                                                            lightbox.innerHTML = `
                                                                <div class="relative max-w-5xl w-full max-h-screen flex flex-col items-center">
                                                                    <img src="${message.attachment.url}" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" />
                                                                    <button class="absolute -top-12 right-0 text-white/70 hover:text-white transition-colors p-2">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                    </button>
                                                                </div>
                                                            `;
                                                            lightbox.querySelector(
                                                                "button",
                                                            ).onclick = () =>
                                                                lightbox.remove();
                                                            lightbox.onclick = (
                                                                e,
                                                            ) => {
                                                                if (
                                                                    e.target ===
                                                                    lightbox
                                                                )
                                                                    lightbox.remove();
                                                            };
                                                            document.body.appendChild(
                                                                lightbox,
                                                            );
                                                        }}
                                                    />
                                                </div>
                                                {/* Caption if present */}
                                                {message.content && (
                                                    <div
                                                        className={`px-4 py-3 text-sm leading-relaxed ${
                                                            message.sender ===
                                                            "me"
                                                                ? "rounded-tr-none rounded-b-2xl bg-blue-600 text-white"
                                                                : "rounded-tl-none rounded-b-2xl border border-t-0 border-white/5 bg-[#1F2029] text-slate-200"
                                                        }`}
                                                    >
                                                        {message.content}
                                                    </div>
                                                )}
                                            </div>
                                        ) : (
                                            /* Standard Text/File Message Style */
                                            <div
                                                className={`relative rounded-2xl px-5 py-3 text-sm leading-relaxed shadow-md ${
                                                    message.sender === "me"
                                                        ? "rounded-br-sm bg-blue-600 text-white"
                                                        : "rounded-bl-sm border border-white/5 bg-[#1F2029] text-slate-200"
                                                }`}
                                            >
                                                {message.attachment &&
                                                    message.attachment.type !==
                                                        "image" && 
                                                    !/\.(jpg|jpeg|png|gif|webp|bmp|svg)\s*$/i.test(message.attachment.name) && (
                                                        <div className="group/file mb-3 flex items-center gap-3 rounded-xl border border-white/5 bg-white/10 p-3 transition-colors hover:bg-white/20">
                                                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-xl">
                                                                📄
                                                            </div>
                                                            <div className="min-w-0 flex-1">
                                                                <p className="truncate text-sm font-medium opacity-90">
                                                                    {
                                                                        message
                                                                            .attachment
                                                                            .name
                                                                    }
                                                                </p>
                                                                <p className="text-xs opacity-60">
                                                                    {(
                                                                        message
                                                                            .attachment
                                                                            .size /
                                                                        1024
                                                                    ).toFixed(
                                                                        1,
                                                                    )}{" "}
                                                                    KB
                                                                </p>
                                                            </div>
                                                            <a
                                                                href={
                                                                    message
                                                                        .attachment
                                                                        .url
                                                                }
                                                                download={
                                                                    message
                                                                        .attachment
                                                                        .name
                                                                }
                                                                className="rounded-full p-2 opacity-0 transition-colors group-hover/file:opacity-100 hover:bg-white/20"
                                                                title="Download"
                                                            >
                                                                <svg
                                                                    className="h-4 w-4"
                                                                    fill="none"
                                                                    stroke="currentColor"
                                                                    viewBox="0 0 24 24"
                                                                >
                                                                    <path
                                                                        strokeLinecap="round"
                                                                        strokeLinejoin="round"
                                                                        strokeWidth="2"
                                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                                    />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    )}
                                                {message.content && (
                                                    <div className="wrap-break-word whitespace-pre-wrap">
                                                        {message.content}
                                                    </div>
                                                )}
                                            </div>
                                        )}

                                        <div
                                            className={`mt-1 flex items-center gap-1.5 ${message.sender === "me" ? "justify-end" : "justify-start"} opacity-0 transition-opacity duration-200 group-hover:opacity-100`}
                                        >
                                            <p className="text-[10px] text-slate-500">
                                                {message.time}
                                            </p>
                                        </div>
                                    </div>

                                    {message.sender === "me" && (
                                        <img
                                            src={
                                                window.admin?.avatar ||
                                                `https://ui-avatars.com/api/?name=${encodeURIComponent(window.admin?.name || "Admin")}&background=0D8ABC&color=fff`
                                            }
                                            className="mt-1 mb-1 ml-3 h-8 w-8 self-end rounded-full border-2 border-[#0B0C10]"
                                            alt="Admin"
                                        />
                                    )}
                                </div>
                            ))}
                            <div ref={messagesEndRef} />
                        </div>

                        {/* Input or Action Bar */}
                        <div className="border-t border-white/5 bg-[#0B0C10] p-5">
                            {selectionMode ? (
                                <div className="flex items-center justify-between px-4 py-2">
                                    <span className="text-sm text-slate-400">
                                        {selectedMessages.size} selected
                                    </span>
                                    <div className="flex items-center gap-4">
                                        <button
                                            onClick={copySelectedMessages}
                                            disabled={
                                                selectedMessages.size === 0
                                            }
                                            className="px-4 py-2 text-sm font-medium text-blue-400 transition-colors hover:text-blue-300 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            Copy
                                        </button>
                                        <button
                                            onClick={deleteSelectedMessages}
                                            disabled={
                                                selectedMessages.size === 0
                                            }
                                            className="px-4 py-2 text-sm font-medium text-red-400 transition-colors hover:text-red-300 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            ) : (
                                <form
                                    onSubmit={sendMessage}
                                    className="relative"
                                >
                                    <div className="relative flex items-center rounded-xl border border-white/10 bg-[#16171D] shadow-lg transition-all focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50">
                                        {/* Attachment button with modal */}
                                        <div className="relative">
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    setFileModalOpen(
                                                        !fileModalOpen,
                                                    )
                                                }
                                                className="p-3 text-slate-500 transition-colors hover:text-white"
                                            >
                                                <svg
                                                    className="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                                    />
                                                </svg>
                                            </button>

                                            {/* File Upload Modal */}
                                            {fileModalOpen && (
                                                <>
                                                    {/* Backdrop */}
                                                    <div
                                                        className="fixed inset-0 z-40"
                                                        onClick={() =>
                                                            setFileModalOpen(
                                                                false,
                                                            )
                                                        }
                                                    ></div>
                                                    {/* Modal positioned directly above button */}
                                                    <div className="absolute bottom-full left-0 z-50 mb-3 w-64 rounded-2xl border border-white/10 bg-[#1A1B21] shadow-2xl">
                                                        {/* Header */}
                                                        <div className="border-b border-white/10 px-4 py-3">
                                                            <h3 className="text-sm font-semibold text-white">
                                                                Share
                                                            </h3>
                                                        </div>

                                                        {/* Options */}
                                                        <div className="py-2">
                                                            <div
                                                                onClick={() =>
                                                                    triggerFileInput(
                                                                        ".pdf,.doc,.docx",
                                                                    )
                                                                }
                                                                className="flex w-full cursor-pointer items-center gap-4 px-4 py-3 transition-all hover:bg-white/5"
                                                            >
                                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500/10 text-blue-400">
                                                                    <svg
                                                                        className="h-5 w-5"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                    >
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            strokeWidth="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                                        />
                                                                    </svg>
                                                                </div>
                                                                <div className="text-left">
                                                                    <p className="text-sm font-medium text-white">
                                                                        Document
                                                                    </p>
                                                                    <p className="text-xs text-slate-500">
                                                                        Share
                                                                        files
                                                                    </p>
                                                                </div>
                                                            </div>

                                                            <div
                                                                onClick={() =>
                                                                    triggerFileInput(
                                                                        "image/*,video/*",
                                                                    )
                                                                }
                                                                className="flex w-full cursor-pointer items-center gap-4 px-4 py-3 transition-all hover:bg-white/5"
                                                            >
                                                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-purple-500/10 text-purple-400">
                                                                    <svg
                                                                        className="h-5 w-5"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                    >
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            strokeWidth="2"
                                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                                        />
                                                                    </svg>
                                                                </div>
                                                                <div className="text-left">
                                                                    <p className="text-sm font-medium text-white">
                                                                        Photos &
                                                                        Videos
                                                                    </p>
                                                                    <p className="text-xs text-slate-500">
                                                                        Share
                                                                        media
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {/* Hidden file input */}
                                                        <input
                                                            ref={fileInputRef}
                                                            type="file"
                                                            onChange={
                                                                handleFileSelect
                                                            }
                                                            className="hidden"
                                                        />
                                                    </div>
                                                </>
                                            )}
                                        </div>

                                        <input
                                            type="text"
                                            value={messageInput}
                                            onChange={(e) =>
                                                setMessageInput(e.target.value)
                                            }
                                            placeholder="Type a message..."
                                            className="flex-1 border-none bg-transparent px-4 text-white placeholder-slate-500 focus:ring-0"
                                            autoComplete="off"
                                        />

                                        {/* Actions */}
                                        <div className="flex items-center gap-2 pr-2">
                                            {/* Emoji button */}
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    setEmojiPickerOpen(
                                                        !emojiPickerOpen,
                                                    )
                                                }
                                                className="relative p-2 text-slate-500 transition-colors hover:text-white"
                                            >
                                                <svg
                                                    className="h-5 w-5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    />
                                                </svg>
                                                {/* Emoji Picker */}
                                                {emojiPickerOpen && (
                                                    <div className="absolute right-0 bottom-full z-50 mb-2 w-96 rounded-xl border border-white/10 bg-[#1A1B21] shadow-2xl">
                                                        {/* Header with search */}
                                                        <div className="border-b border-white/10 p-4">
                                                            <div className="mb-3 flex items-center justify-between">
                                                                <p className="text-sm font-semibold text-white">
                                                                    Emoji
                                                                </p>
                                                                <button
                                                                    onClick={(
                                                                        e,
                                                                    ) => {
                                                                        e.stopPropagation();
                                                                        setEmojiPickerOpen(
                                                                            false,
                                                                        );
                                                                    }}
                                                                    className="rounded p-1 text-slate-500 transition-all hover:bg-white/5 hover:text-white"
                                                                >
                                                                    <svg
                                                                        className="h-4 w-4"
                                                                        fill="none"
                                                                        viewBox="0 0 24 24"
                                                                        stroke="currentColor"
                                                                    >
                                                                        <path
                                                                            strokeLinecap="round"
                                                                            strokeLinejoin="round"
                                                                            strokeWidth="2"
                                                                            d="M6 18L18 6M6 6l12 12"
                                                                        />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                            {/* Search input */}
                                                            <input
                                                                type="text"
                                                                placeholder="Search emojis..."
                                                                value={
                                                                    emojiSearch
                                                                }
                                                                onChange={(e) =>
                                                                    setEmojiSearch(
                                                                        e.target
                                                                            .value,
                                                                    )
                                                                }
                                                                className="w-full rounded-lg border border-white/10 bg-[#0F1015] px-3 py-2 text-sm text-white placeholder-slate-400 focus:border-blue-500/50 focus:outline-none"
                                                            />
                                                        </div>

                                                        {/* Category tabs */}
                                                        <div className="flex border-b border-white/5">
                                                            {Object.entries(
                                                                emojiData,
                                                            ).map(
                                                                ([
                                                                    key,
                                                                    category,
                                                                ]) => (
                                                                    <button
                                                                        key={
                                                                            key
                                                                        }
                                                                        onClick={() =>
                                                                            setEmojiCategory(
                                                                                key,
                                                                            )
                                                                        }
                                                                        className={`flex-1 px-3 py-2 text-xs font-medium transition-all ${
                                                                            emojiCategory ===
                                                                            key
                                                                                ? "border-b-2 border-blue-400 text-blue-400"
                                                                                : "text-slate-400 hover:text-white"
                                                                        }`}
                                                                    >
                                                                        {
                                                                            category.name
                                                                        }
                                                                    </button>
                                                                ),
                                                            )}
                                                        </div>

                                                        {/* Emoji grid */}
                                                        <div className="max-h-64 overflow-y-auto p-4">
                                                            <div className="grid grid-cols-8 gap-2">
                                                                {(emojiCategory ===
                                                                "all"
                                                                    ? Object.values(
                                                                          emojiData,
                                                                      ).flatMap(
                                                                          (
                                                                              cat,
                                                                          ) =>
                                                                              cat.emojis,
                                                                      )
                                                                    : emojiData[
                                                                          emojiCategory
                                                                      ]
                                                                          ?.emojis ||
                                                                      []
                                                                )
                                                                    .filter(
                                                                        (
                                                                            emoji,
                                                                        ) =>
                                                                            emojiSearch ===
                                                                                "" ||
                                                                            emoji.includes(
                                                                                emojiSearch.toLowerCase(),
                                                                            ),
                                                                    )
                                                                    .map(
                                                                        (
                                                                            emoji,
                                                                            index,
                                                                        ) => (
                                                                            <button
                                                                                key={`${emojiCategory}-${index}`}
                                                                                onClick={(
                                                                                    e,
                                                                                ) => {
                                                                                    e.stopPropagation();
                                                                                    setMessageInput(
                                                                                        (
                                                                                            prev,
                                                                                        ) =>
                                                                                            prev +
                                                                                            emoji,
                                                                                    );
                                                                                    setEmojiPickerOpen(
                                                                                        false,
                                                                                    );
                                                                                    setEmojiSearch(
                                                                                        "",
                                                                                    );
                                                                                }}
                                                                                className="rounded-lg p-2 text-2xl transition-all hover:scale-110 hover:bg-white/10 active:scale-95"
                                                                            >
                                                                                {
                                                                                    emoji
                                                                                }
                                                                            </button>
                                                                        ),
                                                                    )}
                                                            </div>
                                                            {(emojiCategory ===
                                                            "all"
                                                                ? Object.values(
                                                                      emojiData,
                                                                  ).flatMap(
                                                                      (cat) =>
                                                                          cat.emojis,
                                                                  )
                                                                : emojiData[
                                                                      emojiCategory
                                                                  ]?.emojis ||
                                                                  []
                                                            ).filter(
                                                                (emoji) =>
                                                                    emojiSearch ===
                                                                        "" ||
                                                                    emoji.includes(
                                                                        emojiSearch.toLowerCase(),
                                                                    ),
                                                            ).length === 0 && (
                                                                <div className="py-8 text-center text-slate-400">
                                                                    No emojis
                                                                    found
                                                                </div>
                                                            )}
                                                        </div>
                                                    </div>
                                                )}
                                            </button>
                                            {/* Send button */}
                                            <button
                                                type="submit"
                                                className="rounded-lg bg-blue-600 p-2 text-white shadow-lg shadow-blue-600/20 transition-all hover:scale-105 hover:bg-blue-500"
                                            >
                                                <svg
                                                    className="ml-0.5 h-5 w-5 rotate-90 transform"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                                    />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            )}
                        </div>
                        {/* Confirmation Modal */}
                        {confirmModal.isOpen && (
                            <div className="fixed inset-0 z-60 flex items-center justify-center p-4">
                                <div
                                    className="absolute inset-0 bg-black/80 backdrop-blur-sm"
                                    onClick={() =>
                                        setConfirmModal((prev) => ({
                                            ...prev,
                                            isOpen: false,
                                        }))
                                    }
                                ></div>
                                <div className="animate-in fade-in zoom-in-95 relative w-full max-w-sm rounded-2xl border border-white/10 bg-[#1A1B21] p-6 shadow-2xl duration-200">
                                    <h3 className="mb-2 text-lg font-bold text-white">
                                        {confirmModal.title}
                                    </h3>
                                    <p className="mb-6 text-sm leading-relaxed text-slate-400">
                                        {confirmModal.message}
                                    </p>
                                    <div className="flex items-center gap-3">
                                        <button
                                            onClick={() =>
                                                setConfirmModal((prev) => ({
                                                    ...prev,
                                                    isOpen: false,
                                                }))
                                            }
                                            className="flex-1 rounded-xl bg-white/5 px-4 py-2.5 text-sm font-medium text-slate-300 transition-colors hover:bg-white/10"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            onClick={confirmModal.onConfirm}
                                            className={`flex-1 rounded-xl px-4 py-2.5 text-sm font-medium shadow-lg transition-colors ${
                                                confirmModal.isDanger
                                                    ? "bg-red-500 text-white shadow-red-500/20 hover:bg-red-600"
                                                    : "bg-blue-600 text-white shadow-blue-600/20 hover:bg-blue-500"
                                            }`}
                                        >
                                            {confirmModal.isDanger
                                                ? "Delete"
                                                : "Confirm"}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </>
                ) : (
                    <div className="flex flex-1 flex-col items-center justify-center p-8 text-center">
                        <div className="relative mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-white/5">
                            <svg
                                className="h-10 w-10 text-slate-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                />
                            </svg>
                        </div>
                        <h2 className="text-xl font-bold text-white">
                            Admin Command Center
                        </h2>
                        <p className="mt-2 max-w-sm text-slate-500">
                            Select a conversation from the inbox to manage
                            support tickets and user inquiries.
                        </p>
                    </div>
                )}
            </div>

            {/* File Preview Modal */}
            {filePreviewOpen && selectedFile && (
                <>
                    {console.log(
                        "Rendering file preview modal for:",
                        selectedFile.name,
                    )}
                    {/* Backdrop */}
                    <div className="fixed inset-0 z-100 flex items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
                        <div className="w-full max-w-sm overflow-hidden rounded-2xl border border-white/10 bg-[#1A1B21] shadow-2xl">
                            {/* Image Preview - Takes most of the space */}
                            {selectedFile.type.startsWith("image/") && (
                                <div className="relative">
                                    <img
                                        src={imagePreviewUrl}
                                        alt={selectedFile.name}
                                        className="h-64 w-full object-cover"
                                    />
                                    {/* Close button overlay */}
                                    <button
                                        onClick={() => {
                                            setFilePreviewOpen(false);
                                            setSelectedFile(null);
                                            setFileCaption("");
                                            setImagePreviewUrl(null);
                                        }}
                                        className="absolute top-3 right-3 flex h-8 w-8 items-center justify-center rounded-full bg-black/50 text-white transition-all hover:bg-black/70"
                                    >
                                        <svg
                                            className="h-4 w-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            )}

                            {/* File Info (for non-images) */}
                            {selectedFile.type.startsWith("image/") ? null : (
                                <div className="border-b border-white/5 p-4">
                                    <div className="flex items-center gap-3">
                                        <div className="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-blue-600/20">
                                            <svg
                                                className="h-6 w-6 text-blue-400"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <p className="truncate text-sm font-medium text-white">
                                                {selectedFile.name}
                                            </p>
                                            <p className="text-xs text-slate-400">
                                                {(
                                                    selectedFile.size / 1024
                                                ).toFixed(1)}{" "}
                                                KB
                                            </p>
                                        </div>
                                        <button
                                            onClick={() => {
                                                setFilePreviewOpen(false);
                                                setSelectedFile(null);
                                                setFileCaption("");
                                            }}
                                            className="flex h-8 w-8 items-center justify-center rounded-full text-slate-500 transition-all hover:bg-white/5 hover:text-white"
                                        >
                                            <svg
                                                className="h-4 w-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* Caption Input */}
                            <div className="p-4">
                                <div className="relative">
                                    <input
                                        type="text"
                                        value={fileCaption}
                                        onChange={(e) =>
                                            setFileCaption(e.target.value)
                                        }
                                        placeholder="Add a caption..."
                                        className="w-full rounded-xl border border-white/10 bg-[#0F1015] px-4 py-3 text-white placeholder-slate-400 focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/20 focus:outline-none"
                                        maxLength={100}
                                        autoFocus
                                    />
                                    {fileCaption.length > 0 && (
                                        <div className="absolute top-1/2 right-3 -translate-y-1/2 text-xs text-slate-500">
                                            {fileCaption.length}/100
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Action Buttons - WhatsApp style */}
                            <div className="flex items-center justify-between border-t border-white/5 p-4">
                                <div className="flex items-center gap-2 text-slate-400">
                                    <svg
                                        className="h-4 w-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                    <span className="text-sm">
                                        {activeChat?.user?.name || "User"}
                                    </span>
                                </div>

                                <div className="flex gap-2">
                                    <button
                                        onClick={() => {
                                            setFilePreviewOpen(false);
                                            setSelectedFile(null);
                                            setFileCaption("");
                                            setImagePreviewUrl(null);
                                        }}
                                        className="rounded-lg px-4 py-2 text-sm font-medium text-slate-400 transition-all hover:bg-white/5 hover:text-white"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        onClick={sendFileMessage}
                                        disabled={uploading}
                                        className="flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-lg shadow-blue-600/20 transition-all hover:bg-blue-500 disabled:cursor-not-allowed disabled:bg-blue-600/50"
                                    >
                                        {uploading ? (
                                            <>
                                                <div className="h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white"></div>
                                                <span>Sending...</span>
                                            </>
                                        ) : (
                                            <>
                                                <svg
                                                    className="h-4 w-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth="2"
                                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                                    />
                                                </svg>
                                                <span>Send</span>
                                            </>
                                        )}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </>
            )}
        </div>
    );
}
