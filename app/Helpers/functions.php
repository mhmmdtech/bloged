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