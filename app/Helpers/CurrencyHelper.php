<?php

namespace App\Helpers;

class CurrencyHelper
{
    /**
     * Convert currency string to numeric value
     * "Rp 5.000.000" -> 5000000
     * "5,000,000" -> 5000000
     */
    public static function toNumeric(string $value): int|float
    {
        // Remove all non-numeric characters except decimal point
        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return (float) $cleaned;
    }

    /**
     * Convert numeric value to currency format
     * 5000000 -> "Rp 5.000.000"
     */
    public static function formatIDR(int|float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    /**
     * Extract numeric from "Rp X.XXX" format
     */
    public static function extract(string $value): int|float
    {
        return (float) preg_replace('/[^0-9.]/', '', $value);
    }
}
