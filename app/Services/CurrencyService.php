<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    private const CACHE_KEY = 'usd_to_idr_rate';
    private const CACHE_DURATION = 3600; // 1 hour
    private const API_URL = 'https://api.exchangerate.host/latest?base=USD&symbols=IDR';

    /**
     * Get the current USD to IDR exchange rate
     *
     * @return float
     */
    public function getUsdToIdrRate(): float
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_DURATION, function () {
            try {
                $response = Http::timeout(10)->get(self::API_URL);

                if ($response->successful()) {
                    $data = $response->json();
                    return (float) ($data['rates']['IDR'] ?? 15000); // fallback to 15000 if API fails
                }

                return 15000; // fallback rate
            } catch (\Exception $e) {
                // Log the error but return fallback rate
                Log::warning('Failed to fetch USD to IDR rate: ' . $e->getMessage());
                return 15000; // fallback rate
            }
        });
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
