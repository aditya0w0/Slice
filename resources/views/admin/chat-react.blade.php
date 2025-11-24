<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Admin Chat - Slice</title>
        @viteReactRefresh
        @vite(["resources/css/app.css", "resources/js/admin-chat.jsx"])
    </head>
    <body>
        <div id="admin-chat-root"></div>
        <script>
            window.adminUser = {
                name: '{{ Auth::user()->name }}',
                avatar: '{{ Auth::user()->profile_photo ? asset("storage/" . Auth::user()->profile_photo) : "https://ui-avatars.com/api/?name=" . urlencode(Auth::user()->name) . "&background=0D8ABC&color=fff" }}',
            };
        </script>
    </body>
</html>
