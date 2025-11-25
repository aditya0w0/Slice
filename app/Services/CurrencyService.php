<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const CACHE_KEY = 'usd_to_idr_rate';
    private const CACHE_DURATION = 3600; // 1 hour

    // Multiple free API endpoints with fallback
    private const API_ENDPOINTS = [
        // Google Finance via aggregator (most reliable)
        'https://api.exchangerate-api.com/v4/latest/USD',
        // Frankfurt European Central Bank data
        'https://api.frankfurter.app/latest?from=USD&to=IDR',
        // Open Exchange Rates (limited free tier)
        'https://open.er-api.com/v6/latest/USD',
    ];

    private const FALLBACK_RATE = 15800; // Updated fallback based on Nov 2025 rates

    /**
     * Get the current USD to IDR exchange rate from multiple sources
     *
     * @return float
     */
    public function getUsdToIdrRate(): float
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            // Try each API endpoint in order
            foreach (self::API_ENDPOINTS as $index => $apiUrl) {
                try {
                    // Disable SSL verification for development (allows self-signed certs)
                    $response = Http::withOptions([
                        'verify' => false, // Disable SSL verification for local dev
                    ])->timeout(5)->get($apiUrl);

                    if ($response->successful()) {
                        $data = $response->json();

                        // Parse response based on API format
                        $rate = $this->parseApiResponse($data, $index);

                        if ($rate > 0 && $rate > 10000 && $rate < 20000) { // Sanity check for IDR
                            Log::info("Currency rate fetched successfully from API #{$index}: {$rate}");
                            return $rate;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning("API #{$index} failed: " . $e->getMessage());
                    continue; // Try next API
                }
            }

            // All APIs failed, use fallback
            Log::warning('All currency APIs failed, using fallback rate: ' . self::FALLBACK_RATE);
            return self::FALLBACK_RATE;
        });
    }

    /**
     * Parse API response based on different API formats
     *
     * @param array $data
     * @param int $apiIndex
     * @return float
     */
    private function parseApiResponse(array $data, int $apiIndex): float
    {
        switch ($apiIndex) {
            case 0: // exchangerate-api.com format
                return (float) ($data['rates']['IDR'] ?? 0);

            case 1: // frankfurter.app format
                return (float) ($data['rates']['IDR'] ?? 0);

            case 2: // open.er-api.com format
                return (float) ($data['rates']['IDR'] ?? 0);

            default:
                return 0;
        }
    }

    /**
     * Convert USD amount to IDR
     *
     * @param float $usdAmount
     * @return float
     */
    public function convertUsdToIdr(float $usdAmount): float
    {
        $rate = $this->getUsdToIdrRate();
        return round($usdAmount * $rate);
    }

    /**
     * Convert IDR amount to USD
     *
     * @param float $idrAmount
     * @return float
     */
    public function convertIdrToUsd(float $idrAmount): float
    {
        $rate = $this->getUsdToIdrRate();
        if ($rate == 0) {
            return 0; // Prevent division by zero
        }
        return round($idrAmount / $rate, 2);
    }

    /**
     * Format IDR amount with proper formatting for large numbers
     *
     * @param float $amount
     * @return string
     */
    public function formatIdr(float $amount): string
    {
        if ($amount >= 1000000000) { // Billion
            return 'Rp ' . number_format($amount / 1000000000, 1, ',', '.') . 'T+';
        } elseif ($amount >= 1000000) { // Million
            return 'Rp ' . number_format($amount / 1000000, 1, ',', '.') . 'M+';
        } elseif ($amount >= 1000) { // Thousand
            return 'Rp ' . number_format($amount / 1000, 0, ',', '.') . 'k+';
        } else {
            return 'Rp ' . number_format($amount, 0, ',', '.');
        }
    }

    /**
     * Convert and format USD to IDR
     *
     * @param float $usdAmount
     * @return string
     */
    public function convertAndFormatUsdToIdr(float $usdAmount): string
    {
        $idrAmount = $this->convertUsdToIdr($usdAmount);
        return $this->formatIdr($idrAmount);
    }

    /**
     * Clear the cached exchange rate
     *
     * @return void
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
