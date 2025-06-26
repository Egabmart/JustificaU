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
            $table->string('clase')->after('student_id');
            $table->string('grupo')->after('clase');
            $table->time('hora_inicio')->after('grupo'); // <-- AÑADIDO
            $table->time('hora_fin')->after('hora_inicio'); // <-- AÑADIDO
            $table->string('constancia_path')->nullable()->after('status'); // Para guardar la ruta del archivo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('justificacions', function (Blueprint $table) {
            $table->dropColumn(['clase', 'grupo', 'hora', 'constancia_path']);
        });
    }
};