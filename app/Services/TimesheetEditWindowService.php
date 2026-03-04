<?php

namespace App\Services;

use Carbon\CarbonImmutable;

final class TimesheetEditWindowService
{
    public function canEdit(string $window, CarbonImmutable $workDate, CarbonImmutable $now): bool
    {
        return match ($window) {
            'none' => false,
            'all_time' => true,
            'same_day' => $workDate->isSameDay($now),
            'last_2_days' => $workDate->greaterThanOrEqualTo($now->subDays(2)->startOfDay()),
            'last_month' => $workDate->greaterThanOrEqualTo($now->subMonth()->startOfDay()),
            default => false,
        };
    }
}
