<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('estimateReadingTime')) {
    /**
     * Generate complete route based on user role.
     *
     * @param string $text The text that should be estimated it's reading time.
     * @param int $wpm The word per minute value.
     * @return string The estimated reading time.
     */
    function estimateReadingTime(string $text, int $wpm = 200): string
    {
        // Replace \t and \n with spaces
        $cleanContent = preg_replace('/[\t\n\s]+/', ' ', $text);
        // Replace punctuation marks with spaces
        $cleanContent = preg_replace('/[.,!?:،;]/u', '', $cleanContent);
        // Remove HTML tags and excessive white spaces
        $cleanContent = strip_tags($cleanContent);
        // Count words
        $wordCount = str_word_count($cleanContent);
        // Calculate reading time
        $readingTime = ceil($wordCount / $wpm);

        return $readingTime;
    }
}

if (!function_exists('replaceEnDigitsWithFaDigits')) {
    /**
     * Replace english digit in a serntence with farsi digits.
     *
     * @param string $text The text that should be changed its en value.
     * @return string The text with farsi digits.
     */
    function replaceEnDigitsWithFaDigits(string $text): string
    {
        $englishDigits = range(0, 9);
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

        return str_replace($englishDigits, $persianDigits, $text);
    }
}

if (!function_exists('removeNullFromArray')) {
    /**
     * Remove null values from array
     *
     * @param array $array The array that should be removed its null value
     * @return array The array that its null value was removed.
     */
    function removeNullFromArray(array $array): array
    {
        return array_filter($array, fn($value) => $value !== null);
    }
}

if (!function_exists('generateRandomCode')) {

    /**
     * Generate random token
     */
    function generateRandomCode($minDigits = 5, $maxDigits = 8)
    {
        $minValue = pow(10, $minDigits);
        $maxValue = pow(10, $maxDigits) - 1;

        return random_int($minValue, $maxValue);
    }
}

if (!function_exists('convertToIrMobileFormat')) {

    /**
     * convert given iranian mobile number to international iranian phone number
     */
    function convertToIrMobileFormat($mobileNumber)
    {
        if (preg_match('/^(\+989\d{9})$/', $mobileNumber)) {
            return $mobileNumber;
        }

        if (preg_match('/^(09\d{9})$/', $mobileNumber)) {
            return "+98" . substr($mobileNumber, 1);
        }

        if (preg_match('/^(9\d{9})$/', $mobileNumber)) {
            return "+98" . $mobileNumber;
        }

        return $mobileNumber;
    }
}

if (!function_exists('getAsset')) {

    /**
     * retrieve the asset path
     */
    function getAsset($path)
    {
        if (strpos($path, "https://") === 0) {
            return $path;
        }

        return Storage::url($path);
    }
}

if (!function_exists('array_flatten')) {
    /**
     * retrieve the flattened array
     */
    function array_flatten(array $array)
    {
        $return = array();
        array_walk_recursive($array, function ($a) use (&$return) {
            $return[] = $a;
        });
        return $return;
    }
}