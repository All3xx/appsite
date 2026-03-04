<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Services\UsernameNormalizer;
use App\Support\TenantContext;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

final class AppSantierInstallCommand extends Command
{
    protected $signature = 'appsantier:install {--demo=1}';
    protected $description = 'Install appsantier: seed permissions, create master user, and optional demo tenant';

    public function handle(UsernameNormalizer $normalizer): int
    {
        $this->seedPermissions();

        $master = User::query()->where('username', 'master')->first();
        if (!$master) {
            $master = User::query()->create([
                'name' => 'Master',
                'username' => 'master',
                'email' => null,
                'password' => Hash::make('Master123!'),
                'is_master' => true,
                'tenant_id' => null,
                'role_id' => null,
            ]);
            $this->info('Created master user: master / Master123!');
        } else {
            $this->info('Master user already exists.');
        }

        if ((int) $this->option('demo') === 1) {
            $this->createDemoTenant($normalizer);
        }

        $this->info('Done.');
        return self::SUCCESS;
    }

    private function seedPermissions(): void
    {
        $items = [
            ['name' => 'tenants.manage', 'group' => 'Master', 'description' => 'Manage tenants'],
            ['name' => 'permissions.manage', 'group' => 'Master', 'description' => 'Manage global permissions'],

            ['name' => 'roles.manage', 'group' => 'Access', 'description' => 'Manage roles'],
            ['name' => 'users.manage', 'group' => 'Access', 'description' => 'Manage users'],
            ['name' => 'employees.manage', 'group' => 'Employees', 'description' => 'Manage employees'],
            ['name' => 'sites.manage', 'group' => 'Sites', 'description' => 'Manage sites'],
            ['name' => 'assignments.manage', 'group' => 'Sites', 'description' => 'Manage site assignments'],
            ['name' => 'time_entries.view', 'group' => 'Timesheet', 'description' => 'View timesheet'],
            ['name' => 'time_entries.edit', 'group' => 'Timesheet', 'description' => 'Create/update time entries'],
            ['name' => 'reports.export', 'group' => 'Reports', 'description' => 'Export CSV/XLSX reports'],
            ['name' => 'rates.manage', 'group' => 'Employees', 'description' => 'Manage hourly rate history'],

            // Attendance (future: employee self check-in / present)
            ['name' => 'attendance.mark_present', 'group' => 'Attendance', 'description' => 'Mark present for today'],
            ['name' => 'attendance.check_in', 'group' => 'Attendance', 'description' => 'Check in'],
            ['name' => 'attendance.check_out', 'group' => 'Attendance', 'description' => 'Check out'],
        ];

        foreach ($items as $it) {
            Permission::query()->firstOrCreate(
                ['name' => $it['name']],
                [
                    'group' => $it['group'],
                    'description' => $it['description'],
                    'active' => true,
                ]
            );
        }
    }

    private function createDemoTenant(UsernameNormalizer $normalizer): void
    {
        $tenant = Tenant::query()->where('slug', 'demo')->first();
        if (!$tenant) {
            $tenant = Tenant::query()->create([
                'name' => 'Demo',
                'slug' => 'demo',
                'timezone' => 'Europe/Bucharest',
                'active' => true,
            ]);
            $this->info('Created demo tenant: demo');
        }

        // Set tenant context for tenant-scoped models
        app(TenantContext::class)->set($tenant);

        $adminRole = Role::query()->where('name', 'Administrator')->first();
        if (!$adminRole) {
            $adminRole = Role::query()->create([
                'name' => 'Administrator',
                'is_system' => true,
                'priority' => 100,
            ]);
        }

        $allPerms = Permission::query()->where('active', true)->get();
        $adminRole->permissions()->sync($allPerms->pluck('id')->all());

        $admin = User::query()->where('username', 'admin')->first();
        if (!$admin) {
            $admin = User::query()->create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => null,
                'password' => Hash::make('Admin123!'),
                'is_master' => false,
                'tenant_id' => $tenant->id,
                'role_id' => $adminRole->id,
            ]);
            $this->info('Created demo tenant admin: admin / Admin123!');
        }
    }
}
