Implementation Plan: File Upload & Image Support
Overview
Add comprehensive file upload functionality with emoji API, Apple-style file preview, and image message support for the admin chat system.

Proposed Changes
Frontend - Emoji Picker
[MODIFY] 

package.json
Add emoji-picker-element package (lightweight web component)
Run npm install emoji-picker-element
[MODIFY] 

resources/js/pages/AdminChat.jsx
Replace hardcoded emoji array with emoji-picker-element
Add search and category support
Keep Apple-style dark theme
Frontend - File Upload
[MODIFY] 

resources/js/pages/AdminChat.jsx
Add hidden <input type="file"> with ref
Add state for selected file and preview
Connect file modal buttons to trigger file input
Add file preview modal (Apple-style)
Show image thumbnail, file name, size
Add caption input field
Send file with message via FormData
Backend - File Upload
[MODIFY] 

app/Http/Controllers/Admin/AdminChatController.php
Add uploadFile method
Validate: max 10MB, types: jpg, png, gif, pdf, doc, docx
Store in storage/app/public/chat-attachments/{userId}/
Generate unique filename with timestamp
Return file URL and metadata
[MODIFY] 

routes/web.php
Add POST /api/admin/chat/upload route
[MODIFY] 

app/Events/MessageSent.php
Add attachment field (URL, type, name, size)
Broadcast attachment data with message
Frontend - Image Display
[MODIFY] 

resources/js/pages/AdminChat.jsx
Detect message.attachment in message rendering
Display images inline in message bubble
Add lightbox modal for full-size image view
Show file icon for non-image attachments
Add download link for files
Database Migration
[NEW] database/migrations/xxxx_add_attachment_to_messages.php
Add attachment_url (nullable string)
Add attachment_type (nullable string: image, document, video)
Add attachment_name (nullable string)
Add attachment_size (nullable integer)
Verification Plan
Manual Testing
Click attachment icon → file modal appears
Click "Photos" → file picker opens (images only)
Select image → preview modal shows
Add caption → send message
Image appears in chat bubble
Click image → lightbox opens
Other user receives image in real-time
Test with PDF → shows file icon + download link
Edge Cases
File too large (>10MB) → error message
Invalid file type → error message
Network failure during upload → retry option
