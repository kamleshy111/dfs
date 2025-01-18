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

if (!function_exists('alarmTypeDescription')) {

    function alarmTypeDescription($alarmType = 0)
    {
       $errorList = [
            '618' => 'Fatigue Driving Alarm Level One Start',
            '619' => 'Fatigue Driving Alarm Level Two Start',
            '622' => 'Smoking Alarm Level One Start',
            '623' => 'Smoking Alarm Level Two Start',
            '624' => 'Distracted Driving Alarm Level One Start',
            '625' => 'Distracted Driving Alarm Level Two Start',
            '626' => 'Driver Abnormal Alarm Level One Start',
            '627' => 'Driver Abnormal Alarm Level Two Start',
            '628' => 'Automatic Capture Event Level One Start',
            '629' => 'Automatic Capture Event Level Two Start',
            '630' => 'Driver Change Event Level One Start',
            '631' => 'Driver Change Event Level Two Start',
            '632' => 'Tire Pressure Alarm Start',
        ];

        return $errorList[$alarmType] ?? '';
    }
}


