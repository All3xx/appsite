<?php

namespace App\Services;

use App\Models\TimeEntry;
use App\Models\TimeEntryLog;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class TimesheetService
{
    public function __construct(
        private readonly TimeRoundingService $rounding,
    ) {}

    public function upsertTimeEntry(array $payload, int $actorUserId, string $reason): TimeEntry
    {
        return DB::transaction(function () use ($payload, $actorUserId, $reason) {
            $workMinutes = (int) ($payload['work_minutes'] ?? 0);
            $breakMinutes = (int) ($payload['break_minutes'] ?? 0);

            $step = (int) config('appsantier.rounding_minutes', 10);
            $mode = (string) config('appsantier.rounding_mode', 'nearest');

            $workMinutes = $this->rounding->roundMinutes($workMinutes, $step, $mode);
            $breakMinutes = $this->rounding->roundMinutes($breakMinutes, $step, $mode);

            $key = [
                'employee_id' => (int) $payload['employee_id'],
                'site_id' => (int) $payload['site_id'],
                'work_date' => CarbonImmutable::parse($payload['work_date'])->toDateString(),
            ];

            $entry = TimeEntry::query()->where($key)->first();
            $old = $entry ? $entry->toArray() : null;

            if (!$entry) {
                $entry = new TimeEntry();
                $entry->fill($key);
                $entry->created_by = $actorUserId;
            }

            $entry->work_minutes = $workMinutes;
            $entry->break_minutes = $breakMinutes;
            $entry->notes = (string) ($payload['notes'] ?? '');
            $entry->updated_by = $actorUserId;
            $entry->save();

            TimeEntryLog::query()->create([
                'time_entry_id' => $entry->id,
                'actor_user_id' => $actorUserId,
                'action' => $old ? 'update' : 'create',
                'reason' => $reason,
                'old_values' => $old,
                'new_values' => $entry->fresh()->toArray(),
                'ip' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 255),
            ]);

            return $entry;
        });
    }
}
