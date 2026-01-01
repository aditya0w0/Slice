<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Smart Vite hot file cleanup - prevents loading from dead dev server
        $this->cleanupStaleViteHotFile();
        
        // Force HTTPS when using ngrok or when X-Forwarded-Proto is set
        if (request()->header('X-Forwarded-Proto') === 'https' || 
            str_contains(request()->header('Host', ''), 'ngrok')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
    
    /**
     * Remove the Vite hot file if dev server isn't actually running.
     * This prevents Laravel from trying to load assets from a dead dev server.
     */
    protected function cleanupStaleViteHotFile(): void
    {
        $hotFile = public_path('hot');
        
        // If hot file doesn't exist, nothing to do
        if (!file_exists($hotFile)) {
            return;
        }
        
        // If we're using ngrok (production-like), always remove hot file
        if (str_contains(request()->header('Host', ''), 'ngrok') || 
            config('app.env') === 'production') {
            @unlink($hotFile);
            return;
        }
        
        // Check if Vite dev server is actually reachable
        $viteUrl = trim(file_get_contents($hotFile));
        
        // Quick check: try to connect to Vite server (with short timeout)
        if (!$this->isViteServerRunning($viteUrl)) {
            @unlink($hotFile);
        }
    }
    
    /**
     * Check if Vite dev server is actually running.
     */
    protected function isViteServerRunning(string $url): bool
    {
        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 0.5, // 500ms timeout - very quick check
                    'ignore_errors' => true,
                ],
            ]);
            
            $result = @file_get_contents($url, false, $context);
            return $result !== false;
        } catch (\Exception $e) {
            return false;
        }
    }
}
