<?php

namespace App\Services;

final class TimeRoundingService
{
    public function roundMinutes(int $minutes, int $step, string $mode): int
    {
        if ($step <= 1) {
            return max(0, $minutes);
        }

        $minutes = max(0, $minutes);
        $q = $minutes / $step;

        if ($mode === 'floor') {
            return (int) floor($q) * $step;
        }

        if ($mode === 'ceil') {
            return (int) ceil($q) * $step;
        }

        // nearest
        return (int) round($q) * $step;
    }
}
