<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('employee_code', 32);
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->string('phone', 32);
            $table->string('email', 190)->nullable();
            $table->string('cnp', 32)->nullable();
            $table->date('hire_date')->nullable();
            $table->boolean('active')->default(true);
            $table->decimal('hourly_rate', 10, 2);
            $table->timestamps();

            $table->unique(['tenant_id', 'employee_code']);
            $table->index(['tenant_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
