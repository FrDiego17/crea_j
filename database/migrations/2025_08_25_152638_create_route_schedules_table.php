<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('route_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')->constrained()->onDelete('cascade');
            $table->enum('day_of_week', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo']);
            $table->time('departure_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['route_id', 'day_of_week', 'departure_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('route_schedules');
    }
};