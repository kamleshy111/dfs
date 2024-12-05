<?php

if (!function_exists('formatAmount')) {
    /**
     * Convert amount into K/M/B format
     *
     * @param float $amount
     * @param int $precision
     * @return string
     */
    function formatAmount($amount, $precision = 2)
    {
        if ($amount >= 1000000000) {
            return number_format($amount / 1000000000, $precision) . 'B'; // Billion
        } elseif ($amount >= 1000000) {
            return number_format($amount / 1000000, $precision) . 'M'; // Million
        } elseif ($amount >= 1000) {
            return number_format($amount / 1000, $precision) . 'K'; // Thousand
        }

        return number_format($amount, $precision); // Less than 1000
    }
}
