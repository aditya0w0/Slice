<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Dashboard - Slice</title>
        @viteReactRefresh
        @vite(["resources/css/app.css", "resources/js/dashboard.jsx"])
        <style>
            /* Amazon-style truck animation - FIXED to not overflow */
            @keyframes amazon-truck-move {
                0% {
                    left: 90px;
                }
                100% {
                    left: calc(100% - 170px);
                }
            }

            .amazon-truck-move {
                animation: amazon-truck-move 5s ease-in-out infinite;
            }

            .amazon-truck-fixed {
                animation: amazon-truck-move 4s ease-in-out infinite;
            }

            /* Wheel spinning animation */
            @keyframes wheel-spin {
                0% {
                    stroke-dasharray: 0 50;
                    stroke-dashoffset: 0;
                }
                100% {
                    stroke-dasharray: 50 50;
                    stroke-dashoffset: -100;
                }
            }

            .wheel-spin {
                animation: wheel-spin 0.5s linear infinite;
            }

            /* Speed lines animation */
            @keyframes speed-lines {
                0% {
                    opacity: 0;
                    transform: translateX(0);
                }
                50% {
                    opacity: 0.5;
                }
                100% {
                    opacity: 0;
                    transform: translateX(-20px);
                }
            }

            .speed-lines div {
                animation: speed-lines 0.8s ease-out infinite;
            }

            .speed-lines div:nth-child(2) {
                animation-delay: 0.15s;
            }

            .speed-lines div:nth-child(3) {
                animation-delay: 0.3s;
            }
        </style>
    </head>
    <body>
        <div
            id="dashboard-root"
            data-is-delivered="{{ $isDelivered ?? false ? "true" : "false" }}"
            data-order-status="{{ $activeOrder->status ?? "none" }}"
            data-device-name="{{ $activeOrder->device_name ?? "Your Device" }}"
            data-order-id="{{ $activeOrder?->id }}"
            data-user-name="{{ $user->name }}"
            data-user-balance="{{ $user->balance }}"
            data-is-trusted="{{ $isTrusted ? "true" : "false" }}"
        ></div>
    </body>
</html>
