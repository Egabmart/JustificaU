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
        Schema::create('justificacions', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->text('reason');
            $table->date('fecha'); // <-- CAMBIADO
            $table->string('status')->default('Pendiente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('justificacions');
    }
};