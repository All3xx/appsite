<?php

// Add to User model:

protected $fillable = [
    // keep existing fillables...
    'name',
    'email',
    'password',

    // appsantier
    'username',
    'tenant_id',
    'role_id',
    'is_master',
];

protected $casts = [
    // keep existing casts...
    'email_verified_at' => 'datetime',
    'is_master' => 'boolean',
];

public function tenant()
{
    return $this->belongsTo(\App\Models\Tenant::class);
}

public function role()
{
    return $this->belongsTo(\App\Models\Role::class);
}

public function employee()
{
    return $this->hasOne(\App\Models\Employee::class);
}
