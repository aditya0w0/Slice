import React, { useState, useEffect, useRef } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ScrollArea } from '@/components/ui/scroll-area';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Send, Paperclip, Search, MoreVertical, ArrowLeft, Smile } from 'lucide-react';

export default function Chat() {
  const [conversations, setConversations] = useState([]);
  const [activeChat, setActiveChat] = useState(null);
  const [messages, setMessages] = useState([]);
  const [messageInput, setMessageInput] = useState('');
  const [loading, setLoading] = useState(true);
  const messagesEndRef = useRef(null);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
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
      console.error('❌ No user ID available for WebSocket');
      return;
    }
    
    console.log('🎧 Setting up WebSocket listener for user:', currentUserId);
    console.log('📡 Subscribing to channel: chat.' + currentUserId);
    
    const channel = window.Echo.private(`chat.${currentUserId}`)
      .listen('.message.sent', (e) => {
        console.log('📨 New message received via WebSocket:', e);
        
        setMessages(prevMessages => {
          const messageExists = prevMessages.some(msg => msg.id === e.id);
          if (messageExists) {
            console.log('⚠️ Message already exists, skipping:', e.id);
            return prevMessages;
          }
          return [...prevMessages, e];
        });
      })
      .error((error) => {
        console.error('❌ Channel subscription error:', error);
      });

    console.log('✅ Subscribed to channel: chat.' + currentUserId);

    return () => {
      console.log('🔇 Removing WebSocket listener');
      channel.stopListening('.message.sent');
    };
  }, []);

  const fetchChatData = async () => {
    try {
      const response = await fetch('/api/chat/data');
      const data = await response.json();
      setConversations(data.conversations);
      setActiveChat(data.activeChat);
      setMessages(data.activeChat.messages);
      setLoading(false);
    } catch (error) {
      console.error('Failed to fetch chat data:', error);
      setLoading(false);
    }
  };

  const sendMessage = async (e) => {
    e.preventDefault();
    if (!messageInput.trim()) return;

    const messageText = messageInput;
    setMessageInput('');

    try {
      const response = await fetch('/chat/send', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: messageText })
      });

      const data = await response.json();
      if (data.success) {
        // Message will be added via WebSocket
        console.log('✅ Message sent successfully');
      }
    } catch (error) {
      console.error('Failed to send message:', error);
    }
  };

  if (loading) {
    return (
      <div className="h-screen flex items-center justify-center bg-[#0B0C10]">
        <div className="text-white">Loading...</div>
      </div>
    );
  }

  return (
    <div className="h-screen bg-[#0B0C10] flex overflow-hidden font-sans">
      {/* Sidebar */}
      <div className="w-80 bg-[#0F1015] border-r border-white/5 flex flex-col">
        {/* Sidebar Header */}
        <div className="p-4 border-b border-white/5">
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-xl font-bold text-white">Messages</h2>
            <Button variant="ghost" size="icon" className="text-slate-400 hover:text-white">
              <MoreVertical className="h-5 w-5" />
            </Button>
          </div>
          
          {/* Search */}
          <div className="relative">
            <Search className="w-5 h-5 text-slate-500 absolute left-3 top-1/2 -translate-y-1/2" />
            <Input 
              placeholder="Search conversations..." 
              className="w-full bg-[#16171D] border-white/10 pl-10 text-white placeholder-slate-500"
            />
          </div>
        </div>

        {/* Conversations List */}
        <ScrollArea className="flex-1">
          {conversations.map((conversation) => (
            <div
              key={conversation.user.id}
              className={`flex items-center gap-3 px-4 py-3 hover:bg-white/5 cursor-pointer transition-colors border-l-2 ${
                conversation.active ? 'border-blue-500 bg-white/5' : 'border-transparent'
              }`}
            >
              <div className="relative shrink-0">
                <Avatar className="w-12 h-12 border-2 border-white/10">
                  <AvatarImage src={conversation.user.avatar} />
                  <AvatarFallback>{conversation.user.name[0]}</AvatarFallback>
                </Avatar>
                {conversation.user.online && (
                  <span className="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 rounded-full border-2 border-[#0F1015]" />
                )}
              </div>
              <div className="flex-1 min-w-0">
                <div className="flex items-center justify-between mb-1">
                  <h3 className="text-sm font-semibold text-white truncate">{conversation.user.name}</h3>
                  <span className="text-xs text-slate-500">{conversation.last_message_time}</span>
                </div>
                <p className="text-xs text-slate-400 truncate">{conversation.last_message}</p>
              </div>
              {conversation.unread_count > 0 && (
                <span className="shrink-0 px-2 py-0.5 bg-blue-600 text-white text-xs font-semibold rounded-full">
                  {conversation.unread_count}
                </span>
              )}
            </div>
          ))}
        </ScrollArea>

        {/* User Profile Footer */}
        <div className="p-4 border-t border-white/5">
          <div className="flex items-center gap-3">
            <Avatar className="w-10 h-10 border-2 border-white/10">
              <AvatarImage src={window.user?.avatar} />
              <AvatarFallback>{window.user?.name?.[0]}</AvatarFallback>
            </Avatar>
            <div className="flex-1 min-w-0">
              <h3 className="text-sm font-semibold text-white truncate">{window.user?.name}</h3>
              <p className="text-xs text-slate-400">Online</p>
            </div>
            <Button 
              variant="ghost" 
              size="icon" 
              className="text-slate-400 hover:text-white"
              onClick={() => window.location.href = '/dashboard'}
            >
              <ArrowLeft className="h-5 w-5" />
            </Button>
          </div>
        </div>
      </div>

      {/* Chat Area */}
      <div className="flex-1 flex flex-col bg-[#0B0C10]">
        {/* Chat Header */}
        <div className="h-16 px-6 bg-[#0F1015] border-b border-white/5 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="relative">
              <Avatar className="w-10 h-10 border-2 border-white/10">
                <AvatarImage src={activeChat?.user?.avatar} />
                <AvatarFallback>{activeChat?.user?.name?.[0]}</AvatarFallback>
              </Avatar>
              {activeChat?.user?.online && (
                <span className="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-[#0F1015]" />
              )}
            </div>
            <div>
              <h3 className="text-sm font-semibold text-white">{activeChat?.user?.name}</h3>
              <p className={`text-xs ${activeChat?.user?.online ? 'text-green-500' : 'text-slate-500'}`}>
                {activeChat?.user?.status}
              </p>
            </div>
          </div>
          
          <div className="flex items-center gap-2">
            <Button variant="ghost" size="icon" className="text-slate-400 hover:text-white">
              <Search className="h-5 w-5" />
            </Button>
            <Button variant="ghost" size="icon" className="text-slate-400 hover:text-white">
              <MoreVertical className="h-5 w-5" />
            </Button>
          </div>
        </div>

        {/* Messages Area */}
        <ScrollArea className="flex-1 p-6" style={{ background: 'linear-gradient(180deg, #0B0C10 0%, #0F1015 100%)' }}>
          {messages.length === 0 ? (
            <div className="flex flex-col items-center justify-center h-full text-center">
              <div className="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-4">
                <Send className="w-10 h-10 text-slate-500" />
              </div>
              <h3 className="text-lg font-semibold text-white mb-2">Start a conversation</h3>
              <p className="text-sm text-slate-500 max-w-sm">
                Send a message to our support team. We're here to help with any questions or issues you may have.
              </p>
            </div>
          ) : (
            <div className="space-y-4">
              {/* Date Separator */}
              <div className="flex justify-center my-4">
                <span className="px-3 py-1 bg-white/5 text-slate-500 text-xs rounded-full border border-white/10">
                  Today
                </span>
              </div>

              {messages.map((message) => (
                <div
                  key={message.id}
                  className={`flex ${message.sender === 'me' ? 'justify-end' : 'justify-start'} group animate-fade-in-up`}
                >
                  {message.sender === 'them' && (
                    <Avatar className="w-8 h-8 rounded-full mr-3 mt-1 border-2 border-[#0B0C10] self-end mb-1">
                      <AvatarImage src={activeChat?.user?.avatar} />
                      <AvatarFallback>{activeChat?.user?.name?.[0]}</AvatarFallback>
                    </Avatar>
                  )}

                  <div className="max-w-[70%]">
                    <div
                      className={`relative px-5 py-3 rounded-2xl text-sm leading-relaxed shadow-md ${
                        message.sender === 'me'
                          ? 'bg-blue-600 text-white rounded-br-sm'
                          : 'bg-[#1F2029] border border-white/5 text-slate-200 rounded-bl-sm'
                      }`}
                    >
                      {message.content}
                    </div>
                    <div
                      className={`flex items-center gap-1.5 mt-1 ${
                        message.sender === 'me' ? 'justify-end' : 'justify-start'
                      } opacity-0 group-hover:opacity-100 transition-opacity duration-200`}
                    >
                      <p className="text-[10px] text-slate-500">{message.time}</p>
                    </div>
                  </div>

                  {message.sender === 'me' && (
                    <Avatar className="w-8 h-8 rounded-full ml-3 mt-1 border-2 border-[#0B0C10] self-end mb-1">
                      <AvatarImage src={window.user?.avatar} />
                      <AvatarFallback>{window.user?.name?.[0]}</AvatarFallback>
                    </Avatar>
                  )}
                </div>
              ))}
              <div ref={messagesEndRef} />
            </div>
          )}
        </ScrollArea>

        {/* Input Zone */}
        <div className="p-5 bg-[#0B0C10] border-t border-white/5">
          <form onSubmit={sendMessage} className="relative">
            <div className="relative flex items-center bg-[#16171D] border border-white/10 rounded-xl shadow-lg focus-within:border-blue-500/50 focus-within:ring-1 focus-within:ring-blue-500/50 transition-all">
              <Button type="button" variant="ghost" size="icon" className="text-slate-500 hover:text-white">
                <Paperclip className="h-5 w-5" />
              </Button>

              <Input
                value={messageInput}
                onChange={(e) => setMessageInput(e.target.value)}
                placeholder="Type a message to support..."
                className="flex-1 bg-transparent border-none text-sm text-white placeholder-slate-500 focus-visible:ring-0 focus-visible:ring-offset-0"
              />

              <div className="flex items-center gap-2 pr-2">
                <Button type="button" variant="ghost" size="icon" className="text-slate-500 hover:text-white">
                  <Smile className="h-5 w-5" />
                </Button>
                <Button
                  type="submit"
                  size="icon"
                  className="bg-blue-600 hover:bg-blue-500 text-white shadow-lg shadow-blue-600/20"
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
