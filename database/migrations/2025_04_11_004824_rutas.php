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
        // Opcional: Previene error si la tabla ya existe
        if (!Schema::hasTable('rutas')) {
            Schema::create('rutas', function (Blueprint $table) {
                $table->id();
                $table->string('origen');
                $table->string('descripcion');
                $table->string('horarios');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rutas');
    }
};
