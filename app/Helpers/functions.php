<?php

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