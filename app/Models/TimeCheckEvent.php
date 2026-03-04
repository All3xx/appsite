<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

final class TimeCheckEvent extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'user_id',
        'site_id',
        'type',
        'occurred_at',
        'lat',
        'lng',
        'device_id',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];
}
