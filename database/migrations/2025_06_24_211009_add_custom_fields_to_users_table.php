<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cif')->unique()->after('email')->nullable();
            $table->string('facultad')->after('cif')->nullable();
            $table->string('carrera')->after('facultad')->nullable();
            $table->string('role')->default('estudiante')->after('carrera'); // Roles: 'estudiante' o 'admin'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cif', 'facultad', 'carrera', 'role']);
        });
    }
};