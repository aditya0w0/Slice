import React, { useState, useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

export default function AdminChat() {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [profileOpen, setProfileOpen] = useState(false);
    const [newChatOpen, setNewChatOpen] = useState(false);
    const [fileModalOpen, setFileModalOpen] = useState(false);
    const [emojiPickerOpen, setEmojiPickerOpen] = useState(false);
    const [filterOpen, setFilterOpen] = useState(false);
    const [filePreviewOpen, setFilePreviewOpen] = useState(false);
    const [selectedFile, setSelectedFile] = useState(null);
    const [fileCaption, setFileCaption] = useState("");
    const [imagePreviewUrl, setImagePreviewUrl] = useState(null);
    const [contactFilter, setContactFilter] = useState('all'); // all, unread, favorites
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
        title: '',
        message: '',
        onConfirm: null,
        isDanger: false
    });
    const messagesEndRef = useRef(null);
    const fileInputRef = useRef(null);
    const menuRef = useRef(null);

    // Custom emoji data with categories
    const emojiData = {
        smileys: {
            name: "Smileys",
            emojis: ["😀", "😂", "🥰", "😍", "🤗", "😉", "😎", "🤔", "😢", "😭", "😤", "😡", "🥺", "😴", "🤤", "😈", "👻", "🤖", "👽", "💩"]
        },
        gestures: {
            name: "Gestures",
            emojis: ["👍", "👎", "👌", "✌️", "🤞", "🤘", "🤙", "👏", "🙌", "🤝", "🙏", "✊", "🤛", "🤜", "🤚", "🖐️", "✋", "🖖", "👋", "🤟"]
        },
        hearts: {
            name: "Hearts",
            emojis: ["❤️", "💛", "💚", "💙", "💜", "🖤", "🤍", "🤎", "💔", "❤️‍🔥", "❤️‍🩹", "💕", "💞", "💓", "💗", "💖", "💘", "💝", "💟", "♥️"]
        },
        food: {
            name: "Food",
            emojis: ["🍎", "🍊", "🍋", "🍌", "🍉", "🍇", "🍓", "🫐", "🍈", "🍒", "🍑", "🥭", "🍍", "🥥", "🥝", "🍅", "🍆", "🥑", "🥦", "🥬"]
        },
        activities: {
            name: "Activities",
            emojis: ["⚽", "🏀", "🏈", "⚾", "🎾", "🏐", "🏉", "🥏", "🎱", "🪀", "🏓", "🏸", "🏒", "🏑", "🥍", "🏏", "🪃", "🥅", "⛳", "🪁"]
        },
        objects: {
            name: "Objects",
            emojis: ["💻", "🖥️", "🖱️", "⌨️", "🖨️", "📱", "📲", "💾", "💿", "📀", "📺", "📷", "📸", "📹", "🎥", "📽️", "🎞️", "📞", "☎️", "📟"]
        },
        symbols: {
            name: "Symbols",
            emojis: ["❤️", "💯", "🔥", "⭐", "✨", "💫", "🎉", "🎊", "🎈", "🎁", "🏆", "🥇", "🥈", "🥉", "🏅", "🎖️", "🏵️", "🎗️", "🎀", "🎗️"]
        }
    };

    console.log("🔄 AdminChat rendering, contacts:", contacts.length);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        fetchChatData();
    }, []);

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    // Close dropdown when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (profileOpen && !event.target.closest('.relative')) {
                setProfileOpen(false);
            }
        };
        document.addEventListener('click', handleClickOutside);
        return () => document.removeEventListener('click', handleClickOutside);
    }, [profileOpen]);

    // Close menu when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (menuRef.current && !menuRef.current.contains(event.target)) {
                setMenuOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    // WebSocket listener
    useEffect(() => {
        if (!activeChat?.user?.id) return;

        console.log("🎧 Admin WebSocket listener for user:", activeChat.user.id);
        
        const channel = window.Echo.private(`chat.${activeChat.user.id}`)
            .listen(".message.sent", (e) => {
                console.log("📨 Admin received message:", e);
                
                // Transform sender_type to sender (from admin perspective)
                const transformedMessage = {
                    ...e,
                    sender: e.sender_type === 'admin' ? 'me' : 'them'
                };
                
                setMessages((prev) => {
                    if (prev.some(msg => msg.id === e.id)) return prev;
                    return [...prev, transformedMessage];
                });
                scrollToBottom();
            })
            .listen('.message.deleted', (e) => {
                // Remove deleted messages
                setMessages(prev => prev.filter(m => !e.ids.some(id => id == m.id)));
                // Also remove from selection if selected
                setSelectedMessages(prev => {
                    const newSelected = new Set(prev);
                    e.ids.forEach(id => newSelected.delete(id));
                    return newSelected;
                });
            });

        return () => {
            channel.stopListening(".message.sent");
            channel.stopListening(".message.deleted");
        };
    }, [activeChat?.user?.id]);

    const fetchChatData = async () => {
        try {
            console.log("📡 Fetching admin chat data from /api/admin/chat/data");
            const response = await fetch("/api/admin/chat/data");
            console.log("📥 Response status:", response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log("📊 Admin chat data received:", data);
            console.log("📊 Contacts:", data.contacts);
            console.log("📊 Active chat:", data.activeChat);
            
            setContacts(data.contacts || []);
            setActiveChat(data.activeChat);
            setMessages(data.activeChat?.messages || []);
            
            console.log("🧑 activeChat.user:", data.activeChat?.user);
            console.log("👤 window.admin:", window.admin);
            
            setLoading(false);
        } catch (error) {
            console.error("❌ Failed to fetch admin chat data:", error);
            console.error("❌ Error details:", error.message);
            setLoading(false);
        }
    };

    const switchConversation = async (userId) => {
        try {
            const response = await fetch(`/api/admin/chat/conversation/${userId}`);
            const data = await response.json();
            setActiveChat(data.activeChat);
            setMessages(data.activeChat.messages);
            setContacts(contacts.map(c => ({ ...c, active: c.id === userId })));
        } catch (error) {
            console.error("Failed to switch conversation:", error);
        }
    };

    const fetchAllUsers = async () => {
        try {
            console.log("📡 Fetching all users...");
            const response = await fetch("/api/admin/users");
            if (response.ok) {
                const users = await response.json();
                console.log("👥 Users fetched:", users.length);
                setAllUsers(users);
            }
        } catch (error) {
            console.error("Failed to fetch users:", error);
        }
    };

    const startNewChat = (user) => {
        // Create a new chat with this user
        setActiveChat({
            user: {
                id: user.id,
                name: user.name,
                avatar: user.profile_photo 
                    ? `${window.location.origin}/storage/${user.profile_photo}`
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`,
                status: 'Online'
            },
            messages: []
        });
        setMessages([]);
        setNewChatOpen(false);
        setSearchTerm("");
        
        // Add to contacts if not already there
        if (!contacts.some(c => c.id === user.id)) {
            setContacts([{
                id: user.id,
                name: user.name,
                avatar: user.profile_photo 
                    ? `${window.location.origin}/storage/${user.profile_photo}`
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}`,
                last_message: "Start a conversation",
                time: "now",
                unread: 0,
                status: 'online',
                active: true
            }, ...contacts]);
        }
    };

    const handleFileSelect = (e) => {
        const file = e.target.files[0];
        if (!file) return;

        console.log('File selected:', file.name, file.type, file.size);

        if (file.size > 10 * 1024 * 1024) {
            alert('File size must be less than 10MB');
            return;
        }

        setSelectedFile(file);

        // Create data URL for image preview (CSP compliant)
        if (file.type.startsWith('image/')) {
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

        console.log('File preview modal should be open now');
    };

    const triggerFileInput = (accept) => {
        console.log('Triggering file input with accept:', accept);
        if (fileInputRef.current) {
            fileInputRef.current.accept = accept;
            fileInputRef.current.click();
            console.log('File input clicked');
        } else {
            console.log('File input ref not found');
        }
    };

    const sendFileMessage = async () => {
        if (!selectedFile || !activeChat?.user?.id) return;

        setUploading(true);
        const formData = new FormData();
        formData.append('file', selectedFile);
        formData.append('user_id', activeChat.user.id);
        formData.append('message', fileCaption);

        try {
            const response = await fetch('/api/admin/chat/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData,
            });

            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                throw new Error('Server returned non-JSON response');
            }

            const data = await response.json();

            if (response.ok && data.success) {
                setMessages([...messages, data.message]);
                setFilePreviewOpen(false);
                setSelectedFile(null);
                setFileCaption("");
                setImagePreviewUrl(null);
            } else {
                // Handle API error responses
                let errorMessage = data.message || 'Failed to upload file';
                
                // Specific handling for 422 (Validation Error) which often means file too large in this context
                if (response.status === 422 && errorMessage.includes('The file failed to upload')) {
                     errorMessage = "File too large (max 2MB). GIF rejected.";
                }
                
                console.error('Upload failed:', errorMessage);
                alert(errorMessage);
            }
        } catch (error) {
            console.error('File upload failed:', error);
            if (error.message.includes('JSON')) {
                alert('Server error: Please check your connection and try again');
            } else {
                alert('Failed to upload file: ' + error.message);
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
            .filter(m => selectedMessages.has(m.id) && m.content)
            .map(m => m.content)
            .join('\n\n');
            
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
            title: 'Delete Messages?',
            message: `Are you sure you want to delete ${selectedMessages.size} messages? This action cannot be undone.`,
            isDanger: true,
            onConfirm: async () => {
                try {
                    const response = await fetch('/api/admin/chat/messages/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({
                            message_ids: Array.from(selectedMessages)
                        })
                    });

                    if (response.ok) {
                        // Optimistic update is handled by realtime event, but we can do it here too for instant feedback
                        setMessages(messages.filter(m => !selectedMessages.has(m.id)));
                        setSelectionMode(false);
                        setSelectedMessages(new Set());
                    }
                } catch (error) {
                    console.error('Failed to delete messages:', error);
                    alert('Failed to delete messages');
                }
                setConfirmModal(prev => ({ ...prev, isOpen: false }));
            }
        });
    };

    const clearChat = async () => {
        if (!activeChat?.user?.id) return;
        
        setConfirmModal({
            isOpen: true,
            title: 'Clear Conversation?',
            message: 'Are you sure you want to delete this entire conversation? This cannot be undone.',
            isDanger: true,
            onConfirm: async () => {
                try {
                    const response = await fetch(`/api/admin/chat/conversation/${activeChat.user.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        }
                    });

                    if (response.ok) {
                        setMessages([]);
                        setSelectionMode(false);
                        setSelectedMessages(new Set());
                        setMenuOpen(false);
                    }
                } catch (error) {
                    console.error('Failed to clear chat:', error);
                    alert('Failed to clear chat');
                }
                setConfirmModal(prev => ({ ...prev, isOpen: false }));
            }
        });
    };


    const sendMessage = async (e) => {
        e.preventDefault();
        if (!messageInput.trim() || !activeChat?.user?.id) return;

        const message = messageInput.trim();
        setMessageInput("");

        try {
            const response = await fetch("/admin/chat/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    user_id: activeChat.user.id,
                    message: message,
                }),
            });

            const data = await response.json();
            if (data.success) {
                setMessages([...messages, data.message]);
            }
        } catch (error) {
            console.error("Error sending message:", error);
        }
    };

    if (loading) {
        return (
            <div className="h-screen bg-[#0B0C10] flex items-center justify-center">
                <div className="text-white">Loading...</div>
            </div>
        );
    }

    return (
        <div className="h-screen bg-[#0B0C10] flex overflow-hidden font-sans selection:bg-blue-500/30 selection:text-blue-100">
            {/* Admin Sidebar */}
            <div className={`${sidebarOpen ? 'w-64' : 'w-20'} shrink-0 bg-[#121217] border-r border-white/5 flex flex-col transition-all duration-300 ease-in-out relative z-30`}>
                {/* Logo */}
                <div className="h-16 flex items-center justify-between px-4 border-b border-white/5">
                    <div className="flex items-center gap-3 overflow-hidden whitespace-nowrap">
                        <div className="shrink-0 w-10 h-10 rounded-xl bg-linear-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg className="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        {sidebarOpen && (
                            <span className="text-lg font-bold text-white tracking-tight">
                                Slice<span className="text-blue-500">.</span>
                            </span>
                        )}
                    </div>

                    {/* Toggle Button */}
                    <button
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        className="absolute -right-3 top-20 bg-blue-600 text-white rounded-full p-1 shadow-lg hover:bg-blue-500 transition-colors z-50 border border-[#0B0C10]"
                    >
                        <svg className={`w-3 h-3 transition-transform duration-300 ${!sidebarOpen ? 'rotate-180' : ''}`} fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                </div>

                {/* Nav Links */}
                <nav className="flex-1 overflow-y-auto py-6 px-3 space-y-1" style={{
                    scrollbarWidth: 'thin',
                    scrollbarColor: 'rgba(255,255,255,0.1) transparent'
                }}>
                    {sidebarOpen && (
                        <div className="px-3 mb-2 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Operations
                        </div>
                    )}

                    <a href="/admin/dashboard" className="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                        <svg className="w-5 h-5 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        {sidebarOpen && <span className="text-sm font-medium whitespace-nowrap">Overview</span>}
                    </a>

                    {/* Inbox Active */}
                    <div className="group flex items-center gap-3 px-3 py-2.5 rounded-xl bg-blue-600/10 text-white border border-blue-600/20 relative">
                        <div className="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-blue-500 rounded-r-full"></div>
                        <svg className="w-5 h-5 text-blue-400 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                        {sidebarOpen && <span className="text-sm font-medium whitespace-nowrap">Inbox</span>}
                    </div>

                    {/* Users */}
                    <a href="/admin/users" className="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                        <svg className="w-5 h-5 group-hover:text-purple-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {sidebarOpen && <span className="text-sm font-medium whitespace-nowrap">Users</span>}
                    </a>

                    {/* Broadcasts */}
                    <a href="/admin/notifications" className="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all">
                        <svg className="w-5 h-5 group-hover:text-yellow-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        {sidebarOpen && <span className="text-sm font-medium whitespace-nowrap">Broadcasts</span>}
                    </a>
                </nav>

                {/* Admin Profile */}
                <div className="p-4 border-t border-white/5">
                    <div className="relative">
                        <button
                            onClick={() => setProfileOpen(!profileOpen)}
                            className="flex items-center gap-3 w-full p-2 rounded-xl hover:bg-white/5 transition-colors group"
                        >
                            <img
                                src={window.admin?.avatar || 'https://ui-avatars.com/api/?name=Admin'}
                                className="w-8 h-8 rounded-full ring-2 ring-white/10 group-hover:ring-white/30 transition-all"
                                alt=""
                            />
                            {sidebarOpen && (
                                <>
                                    <div className="flex-1 min-w-0 text-left">
                                        <p className="text-sm font-medium text-white truncate">{window.admin?.name || 'Admin'}</p>
                                        <p className="text-xs text-slate-500 truncate">Administrator</p>
                                    </div>
                                    <svg className="w-4 h-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" />
                                    </svg>
                                </>
                            )}
                        </button>

                        {profileOpen && (
                            <div 
                                className={`absolute bottom-full mb-2 bg-[#1A1B21] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50 ${
                                    sidebarOpen ? 'left-0 w-full' : 'left-full ml-2 w-48'
                                }`}
                                onClick={(e) => e.stopPropagation()}
                            >
                                <form method="POST" action="/logout">
                                    <input type="hidden" name="_token" value={document.querySelector('meta[name="csrf-token"]')?.content} />
                                    <button type="submit" className="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors">
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
            <div className="w-80 bg-[#0F1015] border-r border-white/5 flex flex-col z-20">
                <div className="h-16 px-6 border-b border-white/5 flex items-center justify-between bg-[#0F1015]">
                    <h2 className="text-lg font-bold text-white">Inbox</h2>
                    <div className="flex gap-2">
                        {/* Filter button */}
                        <button 
                            onClick={() => setFilterOpen(!filterOpen)}
                            className="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors relative"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {/* Filter Dropdown */}
                            {filterOpen && (
                                <div className="absolute top-full right-0 mt-2 w-48 bg-[#1A1B21] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                                    <button
                                        onClick={() => { setContactFilter('all'); setFilterOpen(false); }}
                                        className={`w-full px-4 py-3 text-sm text-left transition-colors ${contactFilter === 'all' ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:bg-white/5'}`}
                                    >
                                        All Chats
                                    </button>
                                    <button
                                        onClick={() => { setContactFilter('unread'); setFilterOpen(false); }}
                                        className={`w-full px-4 py-3 text-sm text-left transition-colors ${contactFilter === 'unread' ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:bg-white/5'}`}
                                    >
                                        Unread Only
                                    </button>
                                    <button
                                        onClick={() => { setContactFilter('favorites'); setFilterOpen(false); }}
                                        className={`w-full px-4 py-3 text-sm text-left transition-colors ${contactFilter === 'favorites' ? 'bg-blue-600/20 text-blue-400' : 'text-slate-300 hover:bg-white/5'}`}
                                    >
                                        Favorites
                                    </button>
                                </div>
                            )}
                        </button>
                        {/* Add button */}
                        <button 
                            onClick={() => {
                                setNewChatOpen(true);
                                fetchAllUsers();
                            }}
                            className="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors"
                        >
                            <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                </div>

                {/* Search */}
                <div className="px-4 py-3">
                    <div className="relative group">
                        <svg className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none w-10 h-full text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input
                            type="text"
                            placeholder="Search messages..."
                            className="w-full bg-[#1A1B21] border border-white/5 rounded-lg py-2 pl-10 pr-4 text-sm text-slate-300 placeholder-slate-600 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/50 transition-all"
                        />
                    </div>
                </div>

                {/* New Chat Modal - AirDrop Style */}
                {newChatOpen && (
                    <div className="absolute inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                        <div className="bg-[#1A1B21]/95 border border-white/20 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[85vh] flex flex-col backdrop-blur-xl">
                            {/* Modal Header */}
                            <div className="px-6 py-5 border-b border-white/10 flex items-center justify-between">
                                <h3 className="text-xl font-bold text-white">Start New Chat</h3>
                                <button
                                    onClick={() => {
                                        setNewChatOpen(false);
                                        setSearchTerm("");
                                    }}
                                    className="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all"
                                >
                                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            {/* Search */}
                            <div className="px-6 py-4 border-b border-white/10">
                                <div className="relative">
                                    <svg className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input
                                        type="text"
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        placeholder="Search users..."
                                        className="w-full bg-[#0F1015] border border-white/10 rounded-xl py-3 pl-10 pr-4 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500/50 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                        autoFocus
                                    />
                                </div>
                            </div>

                            {/* User Grid - AirDrop Style */}
                            <div className="flex-1 overflow-y-auto p-6">
                                <div className="grid grid-cols-4 gap-6">
                                    {allUsers
                                        .filter(user => 
                                            user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                                            user.email.toLowerCase().includes(searchTerm.toLowerCase())
                                        )
                                        .map(user => (
                                            <button
                                                key={user.id}
                                                onClick={() => startNewChat(user)}
                                                className="flex flex-col items-center gap-3 p-4 rounded-2xl hover:bg-white/5 transition-all group"
                                            >
                                                <div className="relative">
                                                    <img
                                                        src={user.profile_photo 
                                                            ? `${window.location.origin}/storage/${user.profile_photo}`
                                                            : `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&size=128`}
                                                        className="w-20 h-20 rounded-full ring-2 ring-white/10 group-hover:ring-blue-500/50 transition-all"
                                                        alt=""
                                                    />
                                                    <div className="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-[#1A1B21]"></div>
                                                </div>
                                                <div className="text-center w-full">
                                                    <p className="text-sm font-medium text-white truncate">{user.name}</p>
                                                    <p className="text-xs text-slate-500 truncate mt-0.5">{user.email.split('@')[0]}</p>
                                                </div>
                                            </button>
                                        ))}
                                </div>
                                
                                {allUsers.filter(user => 
                                    user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                                    user.email.toLowerCase().includes(searchTerm.toLowerCase())
                                ).length === 0 && (
                                    <div className="flex flex-col items-center justify-center h-64 text-slate-500">
                                        <svg className="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <p className="text-sm">No users found</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                )}


                {/* Contacts List */}
                <div className="flex-1 overflow-y-auto">
                    {contacts.length === 0 ? (
                        <div className="flex flex-col items-center justify-center h-64 text-slate-500 px-6 text-center">
                            <div className="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mb-3">
                                <svg className="w-6 h-6 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p className="text-sm">No active tickets</p>
                        </div>
                    ) : (
                        contacts.map((contact) => (
                            <div
                                key={contact.id}
                                onClick={() => switchConversation(contact.id)}
                                className={`group px-4 py-3 border-b border-white/5 hover:bg-white/2 cursor-pointer transition-all ${contact.active ? 'border-l-2 border-l-blue-500 bg-blue-500/5' : 'border-l-2 border-l-transparent'}`}
                            >
                                <div className="flex items-start gap-3">
                                    <div className="relative shrink-0">
                                        <img
                                            src={contact.avatar}
                                            alt=""
                                            className="w-10 h-10 rounded-full object-cover ring-2 ring-white/5 group-hover:ring-white/10 transition-all"
                                        />
                                        {contact.status === 'online' && (
                                            <div className="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-[#0F1015]"></div>
                                        )}
                                    </div>

                                    <div className="flex-1 min-w-0">
                                        <div className="flex justify-between items-baseline mb-0.5">
                                            <h3 className="text-sm font-semibold text-white truncate">{contact.name}</h3>
                                            <span className="text-[10px] text-slate-500">{contact.time}</span>
                                        </div>
                                        <p className="text-xs text-slate-400 truncate group-hover:text-slate-300 transition-colors">
                                            {contact.last_message}
                                        </p>
                                    </div>

                                    {contact.unread > 0 && (
                                        <div className="shrink-0 self-center ml-2">
                                            <span className="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-600 text-[10px] font-bold text-white shadow-lg shadow-blue-600/30">
                                                {contact.unread}
                                            </span>
                                        </div>
                                    )}
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>

            {/* Chat Area */}
            <div className="flex-1 flex flex-col bg-[#0B0C10] relative z-10">
                {activeChat?.user?.id ? (
                    <>
                        {/* Header */}
                        <div className="h-16 px-6 border-b border-white/5 flex items-center justify-between bg-[#0B0C10]/80 backdrop-blur-xl">
                            <div className="flex items-center gap-4">
                                <div className="relative">
                                    <img
                                        src={activeChat.user.avatar}
                                        className="w-9 h-9 rounded-full object-cover ring-2 ring-white/10"
                                        alt=""
                                    />
                                    <div className="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-[#0B0C10]"></div>
                                </div>
                                <div>
                                    <h3 className="text-sm font-bold text-white flex items-center gap-2">
                                        {activeChat.user.name}
                                        <span className="px-2 py-0.5 rounded-md bg-white/5 border border-white/5 text-[10px] text-slate-400 font-normal uppercase tracking-wide">
                                            Client
                                        </span>
                                    </h3>
                                    <p className="text-xs text-green-400 flex items-center gap-1.5 mt-0.5">
                                        <span className="w-1 h-1 rounded-full bg-green-400"></span>
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
                                    className={`p-2 rounded-lg transition-colors ${selectionMode ? 'text-blue-400 bg-blue-400/10' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
                                    title="Select Messages"
                                >
                                    <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                                
                                <div className="relative group/menu">
                                    <button className="p-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                                        <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                        </svg>
                                    </button>
                                    
                                    {/* Dropdown Menu */}
                                    <div className="absolute right-0 top-full mt-2 w-48 bg-[#1A1B21] border border-white/10 rounded-xl shadow-xl overflow-hidden hidden group-hover/menu:block z-50">
                                        <button
                                            onClick={clearChat}
                                            className="w-full px-4 py-3 text-sm text-left text-red-400 hover:bg-white/5 hover:text-red-300 transition-colors flex items-center gap-2"
                                        >
                                            <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Clear Chat
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Messages */}
                        <div className="flex-1 overflow-y-auto p-6 space-y-6 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiLz48L3N2Zz4=')]">
                            <div className="flex justify-center py-4">
                                <span className="px-3 py-1 rounded-full bg-white/5 border border-white/5 text-[10px] font-medium text-slate-500">
                                    Start of conversation
                                </span>
                            </div>

                            {messages.map((message, idx) => (
                                <div 
                                    key={idx} 
                                    className={`flex items-end gap-3 group ${message.sender === 'me' ? 'justify-end' : 'justify-start'} ${selectionMode ? 'cursor-pointer' : ''}`}
                                    onClick={() => selectionMode && toggleSelection(message.id)}
                                >
                                    {/* Selection Circle */}
                                    {selectionMode && (
                                        <div className={`shrink-0 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all ${
                                            selectedMessages.has(message.id) 
                                                ? 'bg-blue-500 border-blue-500' 
                                                : 'border-slate-600 group-hover:border-slate-400'
                                        }`}>
                                            {selectedMessages.has(message.id) && (
                                                <svg className="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            )}
                                        </div>
                                    )}
                                    {message.sender === 'them' && (
                                        <img
                                            src={activeChat.user.avatar}
                                            className="w-8 h-8 rounded-full mr-3 mt-1 opacity-70 self-end mb-1"
                                            alt=""
                                        />
                                    )}

                                    <div className="max-w-[70%]">
                                        {/* Image Message Style */}
                                        {message.attachment && message.attachment.type === 'image' ? (
                                            <div className="flex flex-col">
                                                <div className="relative group/image">
                                                    <img
                                                        src={message.attachment.url}
                                                        alt={message.attachment.name}
                                                        className={`max-w-[280px] h-auto rounded-2xl cursor-pointer hover:opacity-95 transition-opacity shadow-sm ${
                                                            message.content ? 'rounded-b-none' : ''
                                                        }`}
                                                        onClick={(e) => {
                                                            if (selectionMode) return; // Let parent handle selection
                                                            e.stopPropagation();
                                                            // Open lightbox
                                                            const lightbox = document.createElement('div');
                                                            lightbox.className = 'fixed inset-0 bg-black/90 backdrop-blur-sm flex items-center justify-center z-50 p-4 animate-in fade-in duration-200';
                                                            lightbox.innerHTML = `
                                                                <div class="relative max-w-5xl w-full max-h-screen flex flex-col items-center">
                                                                    <img src="${message.attachment.url}" class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl" />
                                                                    <button class="absolute -top-12 right-0 text-white/70 hover:text-white transition-colors p-2">
                                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                    </button>
                                                                </div>
                                                            `;
                                                            lightbox.querySelector('button').onclick = () => lightbox.remove();
                                                            lightbox.onclick = (e) => {
                                                                if (e.target === lightbox) lightbox.remove();
                                                            };
                                                            document.body.appendChild(lightbox);
                                                        }}
                                                    />
                                                </div>
                                                {/* Caption if present */}
                                                {message.content && (
                                                    <div className={`px-4 py-3 text-sm leading-relaxed ${
                                                        message.sender === 'me' 
                                                            ? 'bg-blue-600 text-white rounded-b-2xl rounded-tr-none' 
                                                            : 'bg-[#1F2029] text-slate-200 border border-t-0 border-white/5 rounded-b-2xl rounded-tl-none'
                                                    }`}>
                                                        {message.content}
                                                    </div>
                                                )}
                                            </div>
                                        ) : (
                                            /* Standard Text/File Message Style */
                                            <div className={`relative px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-md ${
                                                message.sender === 'me' 
                                                    ? 'rounded-br-sm bg-blue-600 text-white' 
                                                    : 'rounded-bl-sm border border-white/5 bg-[#1F2029] text-slate-200'
                                            }`}>
                                                {message.attachment && message.attachment.type !== 'image' && (
                                                    <div className="mb-3 flex items-center gap-3 p-3 bg-white/10 rounded-xl border border-white/5 hover:bg-white/20 transition-colors group/file">
                                                        <div className="w-10 h-10 rounded-lg bg-white/10 flex items-center justify-center text-xl">
                                                            📄
                                                        </div>
                                                        <div className="flex-1 min-w-0">
                                                            <p className="text-sm font-medium truncate opacity-90">{message.attachment.name}</p>
                                                            <p className="text-xs opacity-60">{(message.attachment.size / 1024).toFixed(1)} KB</p>
                                                        </div>
                                                        <a
                                                            href={message.attachment.url}
                                                            download={message.attachment.name}
                                                            className="p-2 rounded-full hover:bg-white/20 transition-colors opacity-0 group-hover/file:opacity-100"
                                                            title="Download"
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                            </svg>
                                                        </a>
                                                    </div>
                                                )}
                                                {message.content && <div className="whitespace-pre-wrap break-words">{message.content}</div>}
                                            </div>
                                        )}
                                        
                                        <div className={`flex items-center gap-1.5 mt-1 ${message.sender === 'me' ? 'justify-end' : 'justify-start'} opacity-0 group-hover:opacity-100 transition-opacity duration-200`}>
                                            <p className="text-[10px] text-slate-500">{message.time}</p>
                                        </div>
                                    </div>

                                    {message.sender === 'me' && (
                                        <img
                                            src={window.admin?.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(window.admin?.name || 'Admin')}&background=0D8ABC&color=fff`}
                                            className="w-8 h-8 rounded-full ml-3 mt-1 border-2 border-[#0B0C10] self-end mb-1"
                                            alt="Admin"
                                        />
                                    )}
                                </div>
                            ))}
                            <div ref={messagesEndRef} />
                        </div>

                        {/* Input or Action Bar */}
                        <div className="p-5 bg-[#0B0C10] border-t border-white/5">
                            {selectionMode ? (
                                <div className="flex items-center justify-between px-4 py-2">
                                    <span className="text-sm text-slate-400">
                                        {selectedMessages.size} selected
                                    </span>
                                    <div className="flex items-center gap-4">
                                        <button
                                            onClick={copySelectedMessages}
                                            disabled={selectedMessages.size === 0}
                                            className="px-4 py-2 text-sm font-medium text-blue-400 hover:text-blue-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                        >
                                            Copy
                                        </button>
                                        <button
                                            onClick={deleteSelectedMessages}
                                            disabled={selectedMessages.size === 0}
                                            className="px-4 py-2 text-sm font-medium text-red-400 hover:text-red-300 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            ) : (
                                <form onSubmit={sendMessage} className="relative">
                                <div className="relative flex items-center bg-[#16171D] border border-white/10 rounded-xl shadow-lg focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50 transition-all">
                                    {/* Attachment button with modal */}
                                    <div className="relative">
                                        <button
                                            type="button"
                                            onClick={() => setFileModalOpen(!fileModalOpen)}
                                            className="p-3 text-slate-500 hover:text-white transition-colors"
                                        >
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </button>

                                        {/* File Upload Modal */}
                                        {fileModalOpen && (
                                            <>
                                                {/* Backdrop */}
                                                <div className="fixed inset-0 z-40" onClick={() => setFileModalOpen(false)}></div>
                                                {/* Modal positioned directly above button */}
                                                <div className="absolute bottom-full left-0 mb-3 bg-[#1A1B21] border border-white/10 rounded-2xl shadow-2xl w-64 z-50">
                                                    {/* Header */}
                                                    <div className="px-4 py-3 border-b border-white/10">
                                                        <h3 className="text-sm font-semibold text-white">Share</h3>
                                                    </div>

                                                    {/* Options */}
                                                    <div className="py-2">
                                                        <div 
                                                            onClick={() => triggerFileInput('.pdf,.doc,.docx')}
                                                            className="w-full flex items-center gap-4 px-4 py-3 hover:bg-white/5 transition-all cursor-pointer"
                                                        >
                                                            <div className="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400">
                                                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                            </div>
                                                            <div className="text-left">
                                                                <p className="text-sm font-medium text-white">Document</p>
                                                                <p className="text-xs text-slate-500">Share files</p>
                                                            </div>
                                                        </div>

                                                        <div 
                                                            onClick={() => triggerFileInput('image/*,video/*')}
                                                            className="w-full flex items-center gap-4 px-4 py-3 hover:bg-white/5 transition-all cursor-pointer"
                                                        >
                                                            <div className="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400">
                                                                <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                            <div className="text-left">
                                                                <p className="text-sm font-medium text-white">Photos & Videos</p>
                                                                <p className="text-xs text-slate-500">Share media</p>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {/* Hidden file input */}
                                                    <input
                                                        ref={fileInputRef}
                                                        type="file"
                                                        onChange={handleFileSelect}
                                                        className="hidden"
                                                    />
                                                </div>
                                            </>
                                        )}
                                    </div>

                                    <input
                                        type="text"
                                        value={messageInput}
                                        onChange={(e) => setMessageInput(e.target.value)}
                                        placeholder="Type a message..."
                                        className="flex-1 bg-transparent border-none focus:ring-0 text-white placeholder-slate-500 px-4"
                                        autoComplete="off"
                                    />

                                    {/* Actions */}
                                    <div className="flex items-center gap-2 pr-2">
                                        {/* Emoji button */}
                                        <button
                                            type="button"
                                            onClick={() => setEmojiPickerOpen(!emojiPickerOpen)}
                                            className="p-2 text-slate-500 hover:text-white transition-colors relative"
                                        >
                                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            {/* Emoji Picker */}
                                            {emojiPickerOpen && (
                                                <div className="absolute bottom-full right-0 mb-2 w-96 bg-[#1A1B21] border border-white/10 rounded-xl shadow-2xl z-50">
                                                    {/* Header with search */}
                                                    <div className="p-4 border-b border-white/10">
                                                        <div className="flex items-center justify-between mb-3">
                                                            <p className="text-sm font-semibold text-white">Emoji</p>
                                                            <button
                                                                onClick={(e) => {
                                                                    e.stopPropagation();
                                                                    setEmojiPickerOpen(false);
                                                                }}
                                                                className="text-slate-500 hover:text-white p-1 hover:bg-white/5 rounded transition-all"
                                                            >
                                                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                        {/* Search input */}
                                                        <input
                                                            type="text"
                                                            placeholder="Search emojis..."
                                                            value={emojiSearch}
                                                            onChange={(e) => setEmojiSearch(e.target.value)}
                                                            className="w-full px-3 py-2 bg-[#0F1015] border border-white/10 rounded-lg text-white text-sm placeholder-slate-400 focus:outline-none focus:border-blue-500/50"
                                                        />
                                                    </div>

                                                    {/* Category tabs */}
                                                    <div className="flex border-b border-white/5">
                                                        {Object.entries(emojiData).map(([key, category]) => (
                                                            <button
                                                                key={key}
                                                                onClick={() => setEmojiCategory(key)}
                                                                className={`flex-1 py-2 px-3 text-xs font-medium transition-all ${
                                                                    emojiCategory === key
                                                                        ? 'text-blue-400 border-b-2 border-blue-400'
                                                                        : 'text-slate-400 hover:text-white'
                                                                }`}
                                                            >
                                                                {category.name}
                                                            </button>
                                                        ))}
                                                    </div>

                                                    {/* Emoji grid */}
                                                    <div className="max-h-64 overflow-y-auto p-4">
                                                        <div className="grid grid-cols-8 gap-2">
                                                            {(emojiCategory === 'all' 
                                                                ? Object.values(emojiData).flatMap(cat => cat.emojis)
                                                                : emojiData[emojiCategory]?.emojis || []
                                                            )
                                                            .filter(emoji => 
                                                                emojiSearch === '' || 
                                                                emoji.includes(emojiSearch.toLowerCase())
                                                            )
                                                            .map((emoji, index) => (
                                                                <button
                                                                    key={`${emojiCategory}-${index}`}
                                                                    onClick={(e) => {
                                                                        e.stopPropagation();
                                                                        setMessageInput(prev => prev + emoji);
                                                                        setEmojiPickerOpen(false);
                                                                        setEmojiSearch('');
                                                                    }}
                                                                    className="text-2xl hover:bg-white/10 rounded-lg p-2 transition-all hover:scale-110 active:scale-95"
                                                                >
                                                                    {emoji}
                                                                </button>
                                                            ))}
                                                        </div>
                                                        {(emojiCategory === 'all' 
                                                            ? Object.values(emojiData).flatMap(cat => cat.emojis)
                                                            : emojiData[emojiCategory]?.emojis || []
                                                        ).filter(emoji => 
                                                            emojiSearch === '' || 
                                                            emoji.includes(emojiSearch.toLowerCase())
                                                        ).length === 0 && (
                                                            <div className="text-center py-8 text-slate-400">
                                                                No emojis found
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            )}
                                        </button>
                                        {/* Send button */}
                                        <button
                                            type="submit"
                                            className="p-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg transition-all shadow-lg shadow-blue-600/20 hover:scale-105"
                                        >
                                            <svg className="w-5 h-5 transform rotate-90 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            )}
                        </div>
                        {/* Confirmation Modal */}
                        {confirmModal.isOpen && (
                            <div className="fixed inset-0 z-[60] flex items-center justify-center p-4">
                                <div className="absolute inset-0 bg-black/80 backdrop-blur-sm" onClick={() => setConfirmModal(prev => ({ ...prev, isOpen: false }))}></div>
                                <div className="relative bg-[#1A1B21] border border-white/10 rounded-2xl shadow-2xl max-w-sm w-full p-6 animate-in fade-in zoom-in-95 duration-200">
                                    <h3 className="text-lg font-bold text-white mb-2">{confirmModal.title}</h3>
                                    <p className="text-slate-400 text-sm mb-6 leading-relaxed">
                                        {confirmModal.message}
                                    </p>
                                    <div className="flex items-center gap-3">
                                        <button
                                            onClick={() => setConfirmModal(prev => ({ ...prev, isOpen: false }))}
                                            className="flex-1 px-4 py-2.5 rounded-xl bg-white/5 text-slate-300 font-medium hover:bg-white/10 transition-colors text-sm"
                                        >
                                            Cancel
                                        </button>
                                        <button
                                            onClick={confirmModal.onConfirm}
                                            className={`flex-1 px-4 py-2.5 rounded-xl font-medium transition-colors text-sm shadow-lg ${
                                                confirmModal.isDanger 
                                                    ? 'bg-red-500 hover:bg-red-600 text-white shadow-red-500/20' 
                                                    : 'bg-blue-600 hover:bg-blue-500 text-white shadow-blue-600/20'
                                            }`}
                                        >
                                            {confirmModal.isDanger ? 'Delete' : 'Confirm'}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        )}
                    </>
                ) : (
                    <div className="flex-1 flex flex-col items-center justify-center text-center p-8">
                        <div className="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 relative">
                            <svg className="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h2 className="text-xl font-bold text-white">Admin Command Center</h2>
                        <p className="text-slate-500 max-w-sm mt-2">
                            Select a conversation from the inbox to manage support tickets and user inquiries.
                        </p>
                    </div>
                )}
            </div>

            {/* File Preview Modal */}
            {filePreviewOpen && selectedFile && (
                <>
                    {console.log('Rendering file preview modal for:', selectedFile.name)}
                    {/* Backdrop */}
                    <div className="fixed inset-0 bg-black/50 backdrop-blur-sm z-100 flex items-center justify-center p-4">
                        <div className="bg-[#1A1B21] border border-white/10 rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden">
                            {/* Image Preview - Takes most of the space */}
                            {selectedFile.type.startsWith('image/') && (
                                <div className="relative">
                                    <img
                                        src={imagePreviewUrl}
                                        alt={selectedFile.name}
                                        className="w-full h-64 object-cover"
                                    />
                                    {/* Close button overlay */}
                                    <button
                                        onClick={() => {
                                            setFilePreviewOpen(false);
                                            setSelectedFile(null);
                                            setFileCaption("");
                                            setImagePreviewUrl(null);
                                        }}
                                        className="absolute top-3 right-3 w-8 h-8 bg-black/50 rounded-full flex items-center justify-center text-white hover:bg-black/70 transition-all"
                                    >
                                        <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            )}

                            {/* File Info (for non-images) */}
                            {selectedFile.type.startsWith('image/') ? null : (
                                <div className="p-4 border-b border-white/5">
                                    <div className="flex items-center gap-3">
                                        <div className="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center shrink-0">
                                            <svg className="w-6 h-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <p className="text-white font-medium truncate text-sm">{selectedFile.name}</p>
                                            <p className="text-slate-400 text-xs">{(selectedFile.size / 1024).toFixed(1)} KB</p>
                                        </div>
                                        <button
                                            onClick={() => {
                                                setFilePreviewOpen(false);
                                                setSelectedFile(null);
                                                setFileCaption("");
                                            }}
                                            className="w-8 h-8 text-slate-500 hover:text-white hover:bg-white/5 rounded-full flex items-center justify-center transition-all"
                                        >
                                            <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
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
                                        onChange={(e) => setFileCaption(e.target.value)}
                                        placeholder="Add a caption..."
                                        className="w-full px-4 py-3 bg-[#0F1015] border border-white/10 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-blue-500/50 focus:ring-1 focus:ring-blue-500/20"
                                        maxLength={100}
                                        autoFocus
                                    />
                                    {fileCaption.length > 0 && (
                                        <div className="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-500">
                                            {fileCaption.length}/100
                                        </div>
                                    )}
                                </div>
                            </div>

                            {/* Action Buttons - WhatsApp style */}
                            <div className="flex items-center justify-between p-4 border-t border-white/5">
                                <div className="flex items-center gap-2 text-slate-400">
                                    <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span className="text-sm">{activeChat?.user?.name || 'User'}</span>
                                </div>

                                <div className="flex gap-2">
                                    <button
                                        onClick={() => {
                                            setFilePreviewOpen(false);
                                            setSelectedFile(null);
                                            setFileCaption("");
                                            setImagePreviewUrl(null);
                                        }}
                                        className="px-4 py-2 text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-all text-sm font-medium"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        onClick={sendFileMessage}
                                        disabled={uploading}
                                        className="px-4 py-2 bg-blue-600 hover:bg-blue-500 disabled:bg-blue-600/50 disabled:cursor-not-allowed rounded-lg text-white transition-all shadow-lg shadow-blue-600/20 text-sm font-medium flex items-center gap-2"
                                    >
                                        {uploading ? (
                                            <>
                                                <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                                <span>Sending...</span>
                                            </>
                                        ) : (
                                            <>
                                                <svg className="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
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
