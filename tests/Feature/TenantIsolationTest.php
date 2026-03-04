<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('blocks tenant panel access for wrong tenant', function () {
    $t1 = Tenant::query()->create(['name' => 'T1', 'slug' => 't1', 'timezone' => 'Europe/Bucharest', 'active' => true]);
    $t2 = Tenant::query()->create(['name' => 'T2', 'slug' => 't2', 'timezone' => 'Europe/Bucharest', 'active' => true]);

    $u = User::query()->create([
        'name' => 'User',
        'username' => 'user1',
        'email' => null,
        'password' => Hash::make('pass1234'),
        'tenant_id' => $t1->id,
        'is_master' => false,
    ]);

    $this->actingAs($u);

    $this->get('/t2/admin')->assertStatus(403);
});
