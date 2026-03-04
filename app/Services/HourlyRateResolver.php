<?php

namespace App\Services;

use App\Models\EmployeeRate;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class HourlyRateResolver
{
    /**
     * @param Collection<int, EmployeeRate> $ratesSortedAscByDate
     */
    public function resolveForDate(Collection $ratesSortedAscByDate, CarbonImmutable $date, float $fallback): float
    {
        $rate = $fallback;

        foreach ($ratesSortedAscByDate as $r) {
            if ($r->effective_from_date->startOfDay()->lessThanOrEqualTo($date->startOfDay())) {
                $rate = (float) $r->hourly_rate;
                continue;
            }
            break;
        }

        return $rate;
    }
}
