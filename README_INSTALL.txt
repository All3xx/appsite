Appsantier patch v1.0.0 (Filament everywhere, no Livewire in phase 1)

What this patch is
- A starter foundation for AppSantier that you copy over your existing Laravel 12 project created in Herd.
- Includes DB schema (migrations), models, middleware, seeders, install command, and minimal Filament panels/resources:
  - Master panel: /master (manage tenants, global permissions)
  - Tenant panel: /{tenant_slug}/admin (manage roles, users, employees, sites, time entries)

Important notes
- This is a patch (no vendor folder). You must run composer install/require as shown below.
- Auth: login is by username (no diacritics). Email is optional.
- DB: schema is clean and uses migrations (no SQL vendor specific).

Install steps (local in Herd)
1) Copy/extract all files from this patch into your Laravel project root (for example: C:\Users\vanza\Herd\appsite).
2) Require packages:
   composer require filament/filament:"^5.0" -W
3) Register AppSantierServiceProvider in bootstrap/providers.php (Laravel 11/12 style):
   Add this line to the returned array:
   App\Providers\AppSantierServiceProvider::class,
   If your project uses config/app.php providers, add it there instead.
4) Run migrations:
   php artisan migrate
5) Run install command (creates master user, demo tenant, demo admin):
   php artisan appsantier:install

Default URLs
- Master panel: http://appsite.test/master
- Tenant panel: http://appsite.test/{tenant_slug}/admin

Default credentials after install
- Master: username master, password Master123!
- Demo tenant slug: demo
- Demo tenant admin: username admin, password Admin123!

Change passwords immediately after first login.

If you hit an error
- Send me the full terminal output and the first stack trace page, and I will give you a small patch zip (incremental version).
