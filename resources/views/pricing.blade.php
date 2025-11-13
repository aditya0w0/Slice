<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade Your Plan - Slice</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 text-white">
    <div class="flex min-h-screen items-center justify-center px-4 py-12">
        <div class="w-full max-w-6xl">
            <!-- Header -->
            <div class="mb-12 text-center">
                <h1 class="mb-4 text-4xl font-bold">Upgrade your plan</h1>

                <!-- Toggle -->
                <div class="mx-auto mt-8 inline-flex rounded-full bg-gray-800 p-1">
                    <button onclick="switchPlan('personal')" id="btn-personal" class="rounded-full bg-gray-700 px-6 py-2 text-sm font-medium transition-all">Personal</button>
                    <button onclick="switchPlan('business')" id="btn-business" class="rounded-full px-6 py-2 text-sm font-medium text-gray-400 transition-all">Business</button>
                </div>
            </div>

            <!-- Personal Plans -->
            <div id="personal-plans" class="grid grid-cols-1 gap-6 md:grid-cols-3">

                <!-- Free Plan -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Free</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">0</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">Perfect for trying out</p>

                    <div class="mb-6 rounded-lg bg-gray-800 py-2 text-center text-sm text-gray-400">
                        Your current plan
                    </div>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">5 AI requests per day</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Basic response speed</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">No file uploads</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Standard support</span>
                        </div>
                    </div>
                </div>

                <!-- Plus Plan -->
                <div class="flex flex-col rounded-2xl border border-blue-500/50 bg-gradient-to-br from-blue-900/20 to-blue-800/10 p-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-2xl font-bold">Plus</h3>
                        <span class="rounded-full bg-blue-500 px-3 py-1 text-xs font-semibold">POPULAR</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">149.000</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">For power users</p>

                    <button class="mb-6 w-full rounded-full bg-blue-600 py-3 text-center font-semibold transition hover:bg-blue-700">
                        Upgrade to Plus
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Unlimited AI requests</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Priority response speed</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">File uploads (PDF, images, docs)</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Advanced conversation memory</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Priority support</span>
                        </div>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Pro</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">499.000</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">For professionals</p>

                    <button class="mb-6 w-full rounded-full border border-gray-700 bg-white py-3 text-center font-semibold text-gray-900 transition hover:bg-gray-100">
                        Get Pro
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Everything in Plus</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Extended context window (4x longer)</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">API access for integrations</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Custom AI assistants</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Early access to new features</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Dedicated support channel</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Business Plans -->
            <div id="business-plans" class="hidden grid-cols-1 gap-6 md:grid-cols-3">

                <!-- Business Starter -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Starter</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">1.2M</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">Up to 5 team members</p>

                    <button class="mb-6 w-full rounded-full border border-gray-700 bg-white py-3 text-center font-semibold text-gray-900 transition hover:bg-gray-100">
                        Contact Sales
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">5 Pro accounts included</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Team workspace</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Shared conversation history</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Admin dashboard</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Priority email support</span>
                        </div>
                    </div>
                </div>

                <!-- Business Team -->
                <div class="flex flex-col rounded-2xl border border-blue-500/50 bg-gradient-to-br from-blue-900/20 to-blue-800/10 p-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-2xl font-bold">Team</h3>
                        <span class="rounded-full bg-blue-500 px-3 py-1 text-xs font-semibold">POPULAR</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">3.5M</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">Up to 20 team members</p>

                    <button class="mb-6 w-full rounded-full bg-blue-600 py-3 text-center font-semibold transition hover:bg-blue-700">
                        Contact Sales
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Everything in Starter</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">20 Pro accounts included</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Advanced team analytics</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">SSO & advanced security</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Dedicated account manager</span>
                        </div>
                    </div>
                </div>

                <!-- Business Enterprise -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Enterprise</h3>
                    <div class="mb-1">
                        <span class="text-5xl font-bold">Custom</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">&nbsp;</p>
                    <p class="mb-6 text-sm text-gray-400">Unlimited team members</p>

                    <button class="mb-6 w-full rounded-full border border-gray-700 bg-white py-3 text-center font-semibold text-gray-900 transition hover:bg-gray-100">
                        Contact Sales
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Everything in Team</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Unlimited Pro accounts</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Custom model fine-tuning</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">On-premise deployment option</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">SLA & 24/7 support</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Custom contract terms</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <div class="mb-3 text-sm text-gray-400">
                    Have questions? <a href="/support" class="text-blue-400 underline hover:no-underline">Contact our sales team</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchPlan(type) {
            const personalPlans = document.getElementById('personal-plans');
            const businessPlans = document.getElementById('business-plans');
            const btnPersonal = document.getElementById('btn-personal');
            const btnBusiness = document.getElementById('btn-business');

            if (type === 'personal') {
                personalPlans.classList.remove('hidden');
                personalPlans.classList.add('grid');
                businessPlans.classList.remove('grid');
                businessPlans.classList.add('hidden');

                btnPersonal.classList.add('bg-gray-700');
                btnPersonal.classList.remove('text-gray-400');
                btnBusiness.classList.remove('bg-gray-700');
                btnBusiness.classList.add('text-gray-400');
            } else {
                businessPlans.classList.remove('hidden');
                businessPlans.classList.add('grid');
                personalPlans.classList.remove('grid');
                personalPlans.classList.add('hidden');

                btnBusiness.classList.add('bg-gray-700');
                btnBusiness.classList.remove('text-gray-400');
                btnPersonal.classList.remove('bg-gray-700');
                btnPersonal.classList.add('text-gray-400');
            }
        }
    </script>
</body>
</html>

                <!-- Free Plan -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Free</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">0</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month</p>
                    <p class="mb-6 text-sm text-gray-400">Intelligence for everyday tasks</p>

                    <div class="mb-6 rounded-lg bg-gray-800 py-2 text-center text-sm text-gray-400">
                        Your current plan
                    </div>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Access to SliceAI-Lite</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Limited file uploads</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Limited and slower image generation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Limited memory and context</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Limited deep research</span>
                        </div>
                    </div>
                </div>

                <!-- Go Plan -->
                <div class="flex flex-col rounded-2xl border border-indigo-500/50 bg-gradient-to-br from-indigo-900/20 to-indigo-800/10 p-6">
                    <div class="mb-2 flex items-center justify-between">
                        <h3 class="text-2xl font-bold">Go</h3>
                        <span class="rounded-full bg-indigo-500 px-3 py-1 text-xs font-semibold">NEW</span>
                    </div>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">75.000</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month (inclusive of VAT)</p>
                    <p class="mb-6 text-sm text-gray-400">More access to popular features</p>

                    <button class="mb-6 w-full rounded-full bg-indigo-600 py-3 text-center font-semibold transition hover:bg-indigo-700">
                        Upgrade to Go
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded Access to SliceAI-Standard</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded messaging and uploads</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded and faster image creation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Longer memory and context</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
                            </svg>
                            <span class="text-sm text-gray-500">Limited deep research</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Projects, tasks, custom assistants</span>
                        </div>
                    </div>
                </div>

                <!-- Plus Plan -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Plus</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">349.000</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month (inclusive of VAT)</p>
                    <p class="mb-6 text-sm text-gray-400">More access to advanced intelligence</p>

                    <button class="mb-6 w-full rounded-full border border-gray-700 bg-white py-3 text-center font-semibold text-gray-900 transition hover:bg-gray-100">
                        Get Plus
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">SliceAI-Pro with advanced reasoning</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded messaging and uploads</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded and faster image creation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded memory and context</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded deep research and agent mode</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Projects, tasks, custom assistants</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Premium video generation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Code assistant agent</span>
                        </div>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="flex flex-col rounded-2xl border border-gray-800 bg-gray-900 p-6">
                    <h3 class="mb-2 text-2xl font-bold">Pro</h3>
                    <div class="mb-1">
                        <span class="text-sm text-gray-400">Rp</span>
                        <span class="text-5xl font-bold">3.499.000</span>
                    </div>
                    <p class="mb-1 text-xs text-gray-400">IDR / month (inclusive of VAT)</p>
                    <p class="mb-6 text-sm text-gray-400">Full access to the best of SliceAI</p>

                    <button class="mb-6 w-full rounded-full border border-gray-700 bg-white py-3 text-center font-semibold text-gray-900 transition hover:bg-gray-100">
                        Get Pro
                    </button>

                    <div class="flex-1 space-y-3">
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">SliceAI-Ultra with pro reasoning</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Unlimited messages and uploads</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Unlimited and faster image creation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Maximum memory and context</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Maximum deep research and agent mode</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded projects, tasks, and custom assistants</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded premium video generation</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Expanded code assistant agent</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Early access to cutting-edge features</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <div class="mt-12 text-center">
                <div class="mb-3 flex items-center justify-center gap-2 text-gray-400">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-sm">Need more capabilities for your business?</span>
                </div>
                <a href="#" class="text-sm text-white underline hover:no-underline">See Slice Enterprise</a>
            </div>
        </div>
    </div>
</body>
</html>
