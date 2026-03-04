<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Permission extends Model
{
    protected $fillable = [
        'name',
        'group',
        'description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
