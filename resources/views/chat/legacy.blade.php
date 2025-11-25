<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>AI Chat - Slice</title>
        @vite(["resources/css/app.css"])
    </head>
    <body class="bg-gray-50 dark:bg-gray-900">
        <div class="mx-auto max-w-4xl">
            <!-- Header -->
            <div
                class="flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800"
            >
                <div class="flex items-center space-x-4">
                    <a
                        href="/dashboard"
                        class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
                    >
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 dark:text-white">AI Assistant</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Get instant help</p>
                    </div>
                </div>

                <!-- Upgrade Badge -->
                <div class="rounded-full bg-blue-500 px-4 py-2">
                    <span class="text-sm font-semibold text-white">Free Plan</span>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="h-[calc(100vh-180px)] overflow-y-auto bg-white p-6 dark:bg-gray-800">
                <!-- Welcome Message -->
                <div class="mb-6 flex justify-start">
                    <div class="max-w-md rounded-2xl bg-gray-100 px-6 py-4 dark:bg-gray-700">
                        <p class="text-gray-900 dark:text-white">
                            Hello! I'm your AI assistant. How can I help you today?
                        </p>
                    </div>
                </div>

                <!-- Paywall Message -->
                <div class="my-8 text-center">
                    <div
                        class="mx-auto mb-4 inline-flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30"
                    >
                        <svg
                            class="h-8 w-8 text-blue-600 dark:text-blue-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>
                    </div>
                    <h3 class="mb-2 text-xl font-bold text-gray-900 dark:text-white">Upgrade to Premium</h3>
                    <p class="mb-6 text-gray-600 dark:text-gray-400">
                        Unlock unlimited AI chat conversations and priority support
                    </p>
                    <button
                        class="rounded-xl bg-blue-500 px-8 py-3 font-semibold text-white transition-all hover:bg-blue-600 active:scale-95"
                    >
                        View Plans
                    </button>
                </div>
            </div>

            <!-- Input Area (Disabled) -->
            <div class="border-t border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center space-x-3">
                    <input
                        type="text"
                        disabled
                        placeholder="Upgrade to send messages..."
                        class="flex-1 rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 dark:border-gray-600 dark:bg-gray-700"
                    />
                    <button
                        disabled
                        class="rounded-xl bg-gray-300 px-6 py-3 font-semibold text-gray-500 dark:bg-gray-600"
                    >
                        Send
                    </button>
                </div>
            </div>
        </div>
    </body>
</html>
