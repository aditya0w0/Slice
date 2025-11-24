import React, { useState, useEffect, useRef } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ScrollArea } from "@/components/ui/scroll-area";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import {
    Send,
    Paperclip,
    Search,
    MoreVertical,
    Menu,
    Smile,
} from "lucide-react";

export default function AdminChat() {
    const [contacts, setContacts] = useState([]);
    const [activeChat, setActiveChat] = useState(null);
    const [messages, setMessages] = useState([]);
    const [messageInput, setMessageInput] = useState("");
    const [loading, setLoading] = useState(true);
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const messagesEndRef = useRef(null);

    // Debug: Track component renders
    console.log(
        "🔄 AdminChat component rendering, messages count:",
        messages.length,
    );

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        fetchChatData();
    }, []);

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    // Debug: Track messages state changes
    useEffect(() => {
        console.log("📊 Messages state changed:", messages);
    }, [messages]);

    useEffect(() => {
        if (!activeChat?.user?.id) return;

        console.log(
            "🎧 Setting up WebSocket listener for user:",
            activeChat.user.id,
        );
        console.log("📡 Subscribing to channel: chat." + activeChat.user.id);

        // Listen for new messages via WebSocket
        const channel = window.Echo.private(`chat.${activeChat.user.id}`)
            .listen(".message.sent", (e) => {
                console.log(
                    "📨 New message received via WebSocket on channel chat." +
                        activeChat.user.id +
                        ":",
                    e,
                );
                console.log("Current messages count:", messages.length);
                setMessages((prevMessages) => {
                    // Check if message already exists to prevent duplicates
                    const messageExists = prevMessages.some(
                        (msg) => msg.id === e.id,
                    );
                    if (messageExists) {
                        console.log(
                            "⚠️ Message already exists, skipping:",
                            e.id,
                        );
                        return prevMessages;
                    }
                    const newMessages = [...prevMessages, e];
                    console.log(
                        "New messages count will be:",
                        newMessages.length,
                    );
                    console.log("New message added:", e);
                    return newMessages;
                });
                console.log("✅ Messages state updated");
            })
            .error((error) => {
                console.error(
                    "❌ Channel subscription error for chat." +
                        activeChat.user.id +
                        ":",
                    error,
                );
            });

        console.log("✅ Subscribed to channel: chat." + activeChat.user.id);

        // Log subscription success
        channel.subscribed(() => {
            console.log(
                "🎉 Successfully subscribed to channel: chat." +
                    activeChat.user.id,
            );
        });

        return () => {
            console.log(
                "🔇 Removing WebSocket listener for user:",
                activeChat.user.id,
            );
            channel.stopListening(".message.sent");
        };
    }, [activeChat?.user?.id]);

    const fetchChatData = async () => {
        try {
            const response = await fetch("/api/admin/chat/data");
            const data = await response.json();
            setContacts(data.contacts);
            setActiveChat(data.activeChat);
            setMessages(data.activeChat.messages);
            if (data.activeChat.messages.length > 0) {
                lastMessageIdRef.current =
                    data.activeChat.messages[
                        data.activeChat.messages.length - 1
                    ].id;
            }
            setLoading(false);
        } catch (error) {
            console.error("Failed to fetch chat data:", error);
            setLoading(false);
        }
    };

    const switchConversation = async (userId) => {
        try {
            const response = await fetch(
                `/api/admin/chat/conversation/${userId}`,
            );
            const data = await response.json();
            setActiveChat(data.activeChat);
            setMessages(data.activeChat.messages);

            // Update contacts to mark active
            setContacts(
                contacts.map((c) => ({ ...c, active: c.id === userId })),
            );
        } catch (error) {
            console.error("Failed to switch conversation:", error);
        }
    };

    const sendMessage = async (e) => {
        e.preventDefault();
        if (!messageInput.trim() || !activeChat?.user?.id) return;

        const messageText = messageInput;
        setMessageInput("");

        try {
            const response = await fetch("/admin/chat/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({
                    user_id: activeChat.user.id,
                    message: messageText,
                }),
            });

            const data = await response.json();
            if (!data.success) {
                console.error("Failed to send message");
            }
        } catch (error) {
            console.error("Failed to send message:", error);
        }
    };

    if (loading) {
        return (
            <div className="flex h-screen items-center justify-center bg-[#0B0C10]">
                <div className="text-white">Loading...</div>
            </div>
        );
    }

    return (
        <div className="flex h-screen overflow-hidden bg-[#0B0C10] font-sans">
            {/* Sidebar */}
            <div
                className={`${sidebarOpen ? "w-80" : "w-16"} flex flex-col border-r border-white/5 bg-[#0F1015] transition-all duration-300`}
            >
                {/* Sidebar Header */}
                <div className="flex items-center justify-between border-b border-white/5 p-4">
                    <Button
                        variant="ghost"
                        size="icon"
                        onClick={() => setSidebarOpen(!sidebarOpen)}
                        className="text-slate-400 hover:text-white"
                    >
                        <Menu className="h-5 w-5" />
                    </Button>
                    {sidebarOpen && (
                        <h2 className="text-xl font-bold text-white">
                            Support
                        </h2>
                    )}
                </div>

                {/* Conversations List */}
                <ScrollArea className="flex-1">
                    {contacts.map((contact) => (
                        <div
                            key={contact.id}
                            onClick={() => switchConversation(contact.id)}
                            className={`flex cursor-pointer items-center gap-3 border-l-2 px-4 py-3 transition-colors hover:bg-white/5 ${
                                contact.active
                                    ? "border-blue-500 bg-white/5"
                                    : "border-transparent"
                            }`}
                        >
                            <div className="relative shrink-0">
                                <Avatar className="h-12 w-12 border-2 border-white/10">
                                    <AvatarImage src={contact.avatar} />
                                    <AvatarFallback>
                                        {contact.name[0]}
                                    </AvatarFallback>
                                </Avatar>
                            </div>
                            {sidebarOpen && (
                                <div className="min-w-0 flex-1">
                                    <div className="mb-1 flex items-center justify-between">
                                        <h3 className="truncate text-sm font-semibold text-white">
                                            {contact.name}
                                        </h3>
                                        <span className="text-xs text-slate-500">
                                            {contact.time}
                                        </span>
                                    </div>
                                    <p className="truncate text-xs text-slate-400">
                                        {contact.last_message}
                                    </p>
                                </div>
                            )}
                            {sidebarOpen && contact.unread > 0 && (
                                <span className="shrink-0 rounded-full bg-blue-600 px-2 py-0.5 text-xs font-semibold text-white">
                                    {contact.unread}
                                </span>
                            )}
                        </div>
                    ))}
                </ScrollArea>

                {/* Back to Dashboard */}
                <div className="border-t border-white/5 p-4">
                    <Button
                        variant="ghost"
                        className="w-full justify-start text-slate-400 hover:text-white"
                        onClick={() =>
                            (window.location.href = "/admin/dashboard")
                        }
                    >
                        {sidebarOpen ? "Back to Dashboard" : "←"}
                    </Button>
                </div>
            </div>

            {/* Chat Area */}
            <div className="flex flex-1 flex-col bg-[#0B0C10]">
                {/* Chat Header */}
                <div className="flex h-16 items-center justify-between border-b border-white/5 bg-[#0F1015] px-6">
                    <div className="flex items-center gap-3">
                        <Avatar className="h-10 w-10 border-2 border-white/10">
                            <AvatarImage src={activeChat?.user?.avatar} />
                            <AvatarFallback>
                                {activeChat?.user?.name?.[0]}
                            </AvatarFallback>
                        </Avatar>
                        <div>
                            <h3 className="text-sm font-semibold text-white">
                                {activeChat?.user?.name}
                            </h3>
                            <p className="text-xs text-slate-500">
                                {activeChat?.user?.status}
                            </p>
                        </div>
                    </div>

                    <div className="flex items-center gap-2">
                        <Button
                            variant="ghost"
                            size="icon"
                            className="text-slate-400 hover:text-white"
                        >
                            <Search className="h-5 w-5" />
                        </Button>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="text-slate-400 hover:text-white"
                        >
                            <MoreVertical className="h-5 w-5" />
                        </Button>
                    </div>
                </div>

                {/* Messages Area */}
                <ScrollArea
                    className="flex-1 p-6"
                    style={{
                        background:
                            "linear-gradient(180deg, #0B0C10 0%, #0F1015 100%)",
                    }}
                >
                    {messages.length === 0 ? (
                        <div className="flex h-full flex-col items-center justify-center text-center">
                            <div className="mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-white/5">
                                <Send className="h-10 w-10 text-slate-500" />
                            </div>
                            <h3 className="mb-2 text-lg font-semibold text-white">
                                No messages yet
                            </h3>
                            <p className="max-w-sm text-sm text-slate-500">
                                Start the conversation by sending a message to
                                the user.
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            <div className="my-4 flex justify-center">
                                <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-500">
                                    Today
                                </span>
                            </div>

                            {/* Debug: Log messages before rendering */}
                            {console.log("📝 Rendering messages:", messages)}

                            {messages.map((message) => (
                                <div
                                    key={message.id}
                                    className={`flex ${message.sender === "me" ? "justify-end" : "justify-start"} group animate-fade-in-up`}
                                >
                                    {message.sender === "them" && (
                                        <Avatar className="mt-1 mr-3 mb-1 h-8 w-8 self-end rounded-full border-2 border-[#0B0C10]">
                                            <AvatarImage
                                                src={activeChat?.user?.avatar}
                                            />
                                            <AvatarFallback>
                                                {activeChat?.user?.name?.[0]}
                                            </AvatarFallback>
                                        </Avatar>
                                    )}

                                    <div className="max-w-[70%]">
                                        <div
                                            className={`relative rounded-2xl px-5 py-3 text-sm leading-relaxed shadow-md ${
                                                message.sender === "me"
                                                    ? "rounded-br-sm bg-blue-600 text-white"
                                                    : "rounded-bl-sm border border-white/5 bg-[#1F2029] text-slate-200"
                                            }`}
                                        >
                                            {message.content}
                                        </div>
                                        <div
                                            className={`mt-1 flex items-center gap-1.5 ${
                                                message.sender === "me"
                                                    ? "justify-end"
                                                    : "justify-start"
                                            } opacity-0 transition-opacity duration-200 group-hover:opacity-100`}
                                        >
                                            <p className="text-[10px] text-slate-500">
                                                {message.time}
                                            </p>
                                        </div>
                                    </div>

                                    {message.sender === "me" && (
                                        <Avatar className="mt-1 mb-1 ml-3 h-8 w-8 self-end rounded-full border-2 border-[#0B0C10]">
                                            <AvatarImage
                                                src={window.adminUser?.avatar}
                                            />
                                            <AvatarFallback>
                                                {window.adminUser?.name?.[0]}
                                            </AvatarFallback>
                                        </Avatar>
                                    )}
                                </div>
                            ))}
                            <div ref={messagesEndRef} />
                        </div>
                    )}
                </ScrollArea>

                {/* Input Zone */}
                <div className="border-t border-white/5 bg-[#0B0C10] p-5">
                    <form onSubmit={sendMessage} className="relative">
                        <div className="relative flex items-center rounded-xl border border-white/10 bg-[#16171D] shadow-lg transition-all focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50">
                            <Button
                                type="button"
                                variant="ghost"
                                size="icon"
                                className="text-slate-500 hover:text-white"
                            >
                                <Paperclip className="h-5 w-5" />
                            </Button>

                            <Input
                                value={messageInput}
                                onChange={(e) =>
                                    setMessageInput(e.target.value)
                                }
                                placeholder="Type a message..."
                                className="flex-1 border-none bg-transparent text-sm text-white placeholder-slate-500 focus-visible:ring-0 focus-visible:ring-offset-0"
                            />

                            <div className="flex items-center gap-2 pr-2">
                                <Button
                                    type="button"
                                    variant="ghost"
                                    size="icon"
                                    className="text-slate-500 hover:text-white"
                                >
                                    <Smile className="h-5 w-5" />
                                </Button>
                                <Button
                                    type="submit"
                                    size="icon"
                                    className="bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500"
                                >
                                    <Send className="h-5 w-5" />
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
