import './bootstrap';
import '../css/app.css';
import React from 'react';
import { createRoot } from 'react-dom/client';
import Chat from './pages/Chat';

const root = createRoot(document.getElementById('chat-root'));
root.render(<Chat />);
