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
        Schema::create('grabacion_canals', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Cámara 1, Master, etc.
            $table->integer('canal_index')->unique(); // Identificador de la DeckLink (0, 1, 2, 3)
            $table->string('estado')->default('detenido'); // detenido, grabando, error
            $table->string('archivo_actual')->nullable(); // Ruta o nombre del archivo que se está generando
            $table->string('resolucion')->default('1080p5994'); // Formato de video SDI
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grabacion_canals');
    }
};
