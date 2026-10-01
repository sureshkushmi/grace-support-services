<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();

            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');

            // Not used in Phase 1, but built now so we don't touch this
            // table again when Phase 2 (staff documents) is added.
            $table->enum('employment_status', ['full_time', 'part_time', 'casual'])
                ->default('full_time');
            $table->date('worker_screening_expiry')->nullable();
            $table->date('police_check_expiry')->nullable();
            $table->date('first_aid_expiry')->nullable();
            $table->date('drivers_license_expiry')->nullable();

            $table->boolean('status')->default(true); // active / inactive
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
