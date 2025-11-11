/**
 * Session Security - Prevent Back Button Exploits
 *
 * GitHub-style session security: No timeout, focus on preventing
 * back button cache exploits and validating cookie integrity.
 */

(function () {
    'use strict';

    // Check if user is on an authenticated page
    const isAuthPage = document.body.dataset.authRequired === 'true';

    if (isAuthPage) {
        // Prevent back button cache
        window.history.forward();

        // Disable back button functionality
        window.onunload = function () { null };

        // Check session validity on page focus
        document.addEventListener('visibilitychange', function () {
            if (!document.hidden) {
                // Page became visible - validate session
                validateSession();
            }
        });

        // Validate session on page load
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                // Page loaded from cache (back button)
                validateSession();
            }
        });

        // Prevent browser back button
        if (window.performance) {
            if (performance.navigation.type === 2) {
                // Page accessed via back button
                window.location.replace(window.location.href);
            }
        }

        // Check if page was loaded from cache
        window.addEventListener('load', function () {
            if (performance && performance.getEntriesByType('navigation').length > 0) {
                const navEntry = performance.getEntriesByType('navigation')[0];
                if (navEntry.type === 'back_forward') {
                    // Loaded from cache via back button
                    validateSession();
                }
            }
        });
    }

    /**
     * Validate session by checking if user is still authenticated
     */
    function validateSession() {
        fetch('/api/session/validate', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok || response.status === 401) {
                    // Session invalid - redirect to login
                    window.location.replace('/login');
                }
            })
            .catch(() => {
                // Network error or session invalid
                window.location.replace('/login');
            });
    }

})();
