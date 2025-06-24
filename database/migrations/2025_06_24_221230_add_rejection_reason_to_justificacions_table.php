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
        Schema::table('justificacions', function (Blueprint $table) {
            // Añadimos una columna para el motivo del rechazo, que puede ser nula.
            $table->text('rejection_reason')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justificacions', function (Blueprint $table) {
            $table->dropColumn('rejection_reason');
        });
    }
};