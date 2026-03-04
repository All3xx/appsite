<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements FilamentUser
{

 /** @use HasFactory<\Database\Factories\UserFactory> */

 use HasFactory, Notifiable, TwoFactorAuthenticatable;

 /**

 * The attributes that are mass assignable.

 *

 * @var list<string>

 */

 protected $fillable = [

 'name',

 'email',

 'password',

 // AppSantier

 'username',

 'tenant_id',

 'role_id',

 'is_master',

 ];

 /**

 * The attributes that should be hidden for serialization.

 *

 * @var list<string>

 */

 protected $hidden = [

 'password',

 'two_factor_secret',

 'two_factor_recovery_codes',

 'remember_token',

 ];

 /**

 * Get the attributes that should be cast.

 *

 * @return array<string, string>

 */

 protected function casts(): array

 {

 return [

 'email_verified_at' => 'datetime',

 'password' => 'hashed',

 // AppSantier

 'is_master' => 'boolean',

 'tenant_id' => 'integer',

 'role_id' => 'integer',

 ];

 }

 public function tenant(): BelongsTo

 {
 return $this->belongsTo(Tenant::class);

 }

 public function role(): BelongsTo

 {

 return $this->belongsTo(Role::class);

 }

 public function employee(): HasOne

 {

 return $this->hasOne(Employee::class);

 }

 public function canAccessPanel(Panel $panel): bool
 {
  if (app()->environment('local')) {
   return true;
  }

  if ($this->is_master) {
   return true;
  }

  $roleName = $this->role?->name;
  if (is_string($roleName) && $roleName !== '') {
   $roleName = Str::lower(trim($roleName));
   return in_array($roleName, ['admin', 'superadmin', 'owner', 'manager'], true);
  }

  return false;
 }

 /**

 * Get the user's initials.

 */

 public function initials(): string

 {

 return Str::of($this->name)

 ->explode(' ')

 ->take(2)

 ->map(fn ($word) => Str::substr($word, 0, 1))

 ->implode('');

 }

}
