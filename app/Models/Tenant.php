<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'timezone',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
