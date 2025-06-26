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
        // Añadimos una columna para el nombre del profesor, que puede estar vacía (nullable)
        $table->string('profesor')->nullable()->after('grupo');
    });
}

public function down(): void
{
    Schema::table('justificacions', function (Blueprint $table) {
        $table->dropColumn('profesor');
    });
}
};
