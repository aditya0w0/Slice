<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>CSS Debug Test</title>
    
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-blue-600 mb-4">🔍 CSS Loading Test</h1>
        
        <div class="space-y-4">
            <div class="p-4 bg-green-100 border-l-4 border-green-500">
                <p class="font-semibold">If you see GREEN background and styling, CSS is working! ✅</p>
            </div>
            
            <div class="p-4 bg-red-100 border-l-4 border-red-500">
                <p class="font-semibold">If this looks unstyled (no colors/borders), CSS is NOT loading ❌</p>
            </div>
            
            <div class="mt-8 p-4 border rounded">
                <h2 class="text-xl font-bold mb-2">Diagnostic Info:</h2>
                <div class="font-mono text-sm space-y-1">
                    <p><strong>APP_URL:</strong> {{ config('app.url') }}</p>
                    <p><strong>ASSET_URL:</strong> {{ config('app.asset_url') ?: 'Not set' }}</p>
                    <p><strong>APP_ENV:</strong> {{ config('app.env') }}</p>
                    <p><strong>Current URL:</strong> {{ url()->current() }}</p>
                    <p><strong>Asset Test:</strong> {{ asset('test.css') }}</p>
                </div>
            </div>
            
            <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500">
                <p class="font-semibold">Check browser console (F12) for errors!</p>
            </div>
        </div>
    </div>
    
    <script>
        console.log('=== CSS DEBUG INFO ===');
        console.log('Document loaded successfully');
        console.log('Check Network tab for CSS file requests');
        console.log('Look for any 404 or CORS errors');
    </script>
</body>
</html>
