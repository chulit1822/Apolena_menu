<?php
// výběr správné ikony pro typ sněhu

if (!function_exists('lyzeSnowTypeIcon')) {
    function lyzeSnowTypeIcon(?string $type): string
    {
        if (!$type) {
            return '❄️';
        }

        $t = mb_strtolower($type, 'UTF-8');

        // technický / umělý
        if (
            str_contains($t, 'techn')
            || str_contains($t, 'uměl')
            || str_contains($t, 'technisch')
            || str_contains($t, 'artificial')
        ) {
            return '🧊';
        }

        // přírodní
        if (
            str_contains($t, 'přírod')
            || str_contains($t, 'natural')
            || str_contains($t, 'natürlich')
        ) {
            return '❄️';
        }

        // čerstvý / nový
        if (
            str_contains($t, 'čerstv')
            || str_contains($t, 'new')
            || str_contains($t, 'frisch')
        ) {
            return '🌨️';
        }

        // jarní / mokrý
        if (
            str_contains($t, 'jarn')
            || str_contains($t, 'mokr')
            || str_contains($t, 'wet')
            || str_contains($t, 'frühjahr')
        ) {
            return '💧';
        }

        return '❄️';
    }
}
