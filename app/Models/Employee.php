<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Employee extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'employee_code',
        'first_name',
        'last_name',
        'phone',
        'email',
        'cnp',
        'hire_date',
        'active',
        'hourly_rate',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'active' => 'boolean',
        'hourly_rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(EmployeeRate::class);
    }

    public function fullName(): string
    {
        return trim($this->last_name . ' ' . $this->first_name);
    }
}
