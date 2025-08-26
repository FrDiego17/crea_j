<?php
// database/migrations/2024_01_01_000001_create_routes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('start_location');
            $table->string('end_location');
            $table->string('color', 7)->default('#3498db'); // Color hex
            $table->json('route_data')->nullable(); // Datos completos de Google Maps
            $table->text('route_polyline')->nullable(); // Polyline de la ruta
            $table->json('route_bounds')->nullable(); // Bounds del mapa
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('routes');
    }
};