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
    ArrowLeft,
    Smile,
} from "lucide-react";

export default function Chat() {
    const [conversations, setConversations] = useState([]);
    const [activeChat, setActiveChat] = useState(null);
    const [messages, setMessages] = useState([]);
    const [messageInput, setMessageInput] = useState("");
    const [loading, setLoading] = useState(true);
    const messagesEndRef = useRef(null);

    const scrollToBottom = () => {
        messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
    };

    useEffect(() => {
        fetchChatData();
    }, []);

    useEffect(() => {
        scrollToBottom();
    }, [messages]);

    useEffect(() => {
        const currentUserId = window.user?.id;

        if (!currentUserId) {
            console.error("❌ No user ID available for WebSocket");
            return;
        }

        console.log(
            "🎧 Setting up WebSocket listener for user:",
            currentUserId,
        );
        console.log("📡 Subscribing to channel: chat." + currentUserId);

        const channel = window.Echo.private(`chat.${currentUserId}`)
            .listen(".message.sent", (e) => {
                console.log("📨 New message received via WebSocket:", e);
                console.log("📎 Message ID:", e.id);
                console.log("📎 Message content:", e.content);
                console.log("📎 Current messages count:", messages.length);

                // Transform sender_type to sender (from user perspective)
                const transformedMessage = {
                    ...e,
                    sender: e.sender_type === 'admin' ? 'them' : 'me'
                };

                setMessages((prevMessages) => {
                    console.log("🔄 Updating messages state, previous count:", prevMessages.length);
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
                    const newMessages = [...prevMessages, transformedMessage];
                    console.log("✅ Added new message, new count:", newMessages.length);
                    return newMessages;
                });
            })
            .listen(".message.deleted", (e) => {
                console.log("🗑️ Message deleted via WebSocket:", e);
                setMessages((prevMessages) => {
                    const newMessages = prevMessages.filter(
                        (msg) => !e.ids.some(id => id == msg.id)
                    );
                    console.log("✅ Removed deleted messages, new count:", newMessages.length);
                    return newMessages;
                });
            })
            .error((error) => {
                console.error("❌ Channel subscription error:", error);
            });

        console.log("✅ Subscribed to channel: chat." + currentUserId);

        return () => {
            console.log("🔇 Removing WebSocket listener");
            channel.stopListening(".message.sent");
            channel.stopListening(".message.deleted");
        };
    }, []);


    const fetchChatData = async () => {
        try {
            const response = await fetch("/api/chat/data");
            const data = await response.json();
            console.log("📊 Fetched chat data:", data);
            setConversations(data.conversations);
            setActiveChat(data.activeChat);
            setMessages(data.activeChat.messages);
            setLoading(false);
        } catch (error) {
            console.error("Failed to fetch chat data:", error);
            setLoading(false);
        }
    };


    const sendMessage = async (e) => {
        e.preventDefault();
        if (!messageInput.trim()) return;

        const messageText = messageInput;
        setMessageInput("");

        try {
            const response = await fetch("/chat/send", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ message: messageText }),
            });

            const data = await response.json();
            if (data.success) {
                // Message will be added via WebSocket
                console.log("✅ Message sent successfully");
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
            <div className="flex w-80 flex-col border-r border-white/5 bg-[#0F1015]">
                {/* Sidebar Header */}
                <div className="border-b border-white/5 p-4">
                    <div className="mb-4 flex items-center justify-between">
                        <h2 className="text-xl font-bold text-white">
                            Messages
                        </h2>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="text-slate-400 hover:text-white"
                        >
                            <MoreVertical className="h-5 w-5" />
                        </Button>
                    </div>

                    {/* Search */}
                    <div className="relative">
                        <Search className="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-500" />
                        <Input
                            placeholder="Search conversations..."
                            className="w-full border-white/10 bg-[#16171D] pl-10 text-white placeholder-slate-500"
                        />
                    </div>
                </div>

                {/* Conversations List */}
                <ScrollArea className="flex-1">
                    {conversations.map((conversation) => (
                        <div
                            key={conversation.user.id}
                            className={`flex cursor-pointer items-center gap-3 border-l-2 px-4 py-3 transition-colors hover:bg-white/5 ${
                                conversation.active
                                    ? "border-blue-500 bg-white/5"
                                    : "border-transparent"
                            }`}
                        >
                            <div className="relative shrink-0">
                                <Avatar className="h-12 w-12 border-2 border-white/10">
                                    <AvatarImage
                                        src={conversation.user.avatar}
                                    />
                                    <AvatarFallback>
                                        {conversation.user.name[0]}
                                    </AvatarFallback>
                                </Avatar>
                                {conversation.user.online && (
                                    <span className="absolute right-0 bottom-0 h-3.5 w-3.5 rounded-full border-2 border-[#0F1015] bg-green-500" />
                                )}
                            </div>
                            <div className="min-w-0 flex-1">
                                <div className="mb-1 flex items-center justify-between">
                                    <h3 className="truncate text-sm font-semibold text-white">
                                        {conversation.user.name}
                                    </h3>
                                    <span className="text-xs text-slate-500">
                                        {conversation.last_message_time}
                                    </span>
                                </div>
                                <p className="truncate text-xs text-slate-400">
                                    {conversation.last_message}
                                </p>
                            </div>
                            {conversation.unread_count > 0 && (
                                <span className="shrink-0 rounded-full bg-blue-600 px-2 py-0.5 text-xs font-semibold text-white">
                                    {conversation.unread_count}
                                </span>
                            )}
                        </div>
                    ))}
                </ScrollArea>

                {/* User Profile Footer */}
                <div className="border-t border-white/5 p-4">
                    <div className="flex items-center gap-3">
                        <Avatar className="h-10 w-10 border-2 border-white/10">
                            <AvatarImage src={window.user?.avatar} />
                            <AvatarFallback>
                                {window.user?.name?.[0]}
                            </AvatarFallback>
                        </Avatar>
                        <div className="min-w-0 flex-1">
                            <h3 className="truncate text-sm font-semibold text-white">
                                {window.user?.name}
                            </h3>
                            <p className="text-xs text-slate-400">Online</p>
                        </div>
                        <Button
                            variant="ghost"
                            size="icon"
                            className="text-slate-400 hover:text-white"
                            onClick={() =>
                                (window.location.href = "/dashboard")
                            }
                        >
                            <ArrowLeft className="h-5 w-5" />
                        </Button>
                    </div>
                </div>
            </div>

            {/* Chat Area */}
            <div className="flex flex-1 flex-col bg-[#0B0C10]">
                {/* Chat Header */}
                <div className="flex h-16 items-center justify-between border-b border-white/5 bg-[#0F1015] px-6">
                    <div className="flex items-center gap-3">
                        <div className="relative">
                            <Avatar className="h-10 w-10 border-2 border-white/10">
                                <AvatarImage src={activeChat?.user?.avatar} />
                                <AvatarFallback>
                                    {activeChat?.user?.name?.[0]}
                                </AvatarFallback>
                            </Avatar>
                            {activeChat?.user?.online && (
                                <span className="absolute right-0 bottom-0 h-3 w-3 rounded-full border-2 border-[#0F1015] bg-green-500" />
                            )}
                        </div>
                        <div>
                            <h3 className="text-sm font-semibold text-white">
                                {activeChat?.user?.name}
                            </h3>
                            <p
                                className={`text-xs ${activeChat?.user?.online ? "text-green-500" : "text-slate-500"}`}
                            >
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
                                Start a conversation
                            </h3>
                            <p className="max-w-sm text-sm text-slate-500">
                                Send a message to our support team. We're here
                                to help with any questions or issues you may
                                have.
                            </p>
                        </div>
                    ) : (
                        <div className="space-y-4">
                            {/* Date Separator */}
                            <div className="my-4 flex justify-center">
                                <span className="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs text-slate-500">
                                    Today
                                </span>
                            </div>

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
                                            {message.attachment && message.attachment.type === 'image' && (
                                                <div className="mb-3">
                                                    <img
                                                        src={message.attachment.url}
                                                        alt={message.attachment.name}
                                                        className="max-w-full h-auto rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                        onClick={() => {
                                                            // Open lightbox
                                                            const lightbox = document.createElement('div');
                                                            lightbox.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                                                            lightbox.innerHTML = `
                                                                <div class="max-w-4xl max-h-screen p-4">
                                                                    <img src="${message.attachment.url}" class="max-w-full max-h-full object-contain" />
                                                                    <button class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300" onclick="this.parentElement.parentElement.remove()">&times;</button>
                                                                </div>
                                                            `;
                                                            document.body.appendChild(lightbox);
                                                        }}
                                                    />
                                                </div>
                                            )}
                                            {message.attachment && message.attachment.type !== 'image' && (
                                                <div className="mb-3 flex items-center gap-3 p-3 bg-white/5 rounded-lg">
                                                    <div className="text-2xl">📄</div>
                                                    <div className="flex-1 min-w-0">
                                                        <p className="text-sm font-medium truncate">{message.attachment.name}</p>
                                                        <p className="text-xs text-slate-400">{(message.attachment.size / 1024).toFixed(1)} KB</p>
                                                    </div>
                                                    <a
                                                        href={message.attachment.url}
                                                        download={message.attachment.name}
                                                        className="text-blue-400 hover:text-blue-300 text-sm"
                                                    >
                                                        Download
                                                    </a>
                                                </div>
                                            )}
                                            {message.content && <div>{message.content}</div>}
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
                                                src={window.user?.avatar}
                                            />
                                            <AvatarFallback>
                                                {window.user?.name?.[0]}
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
                                placeholder="Type a message to support..."
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
                                    <Send className="h-5 w-5 rotate-0" />
                                </Button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
}
