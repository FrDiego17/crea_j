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
        Schema::create('conductores', function (Blueprint $table) {
            $table->engine="InnoDB";
            $table->bigIncrements('id');
            
            $table->unsignedBigInteger('rutas_id');

            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->integer('dui')->unique();
            $table->integer('telefono');
            $table->string('licencia');
            $table->string('TipoVehiculo');
            
            $table->timestamps();

            $table->foreign('rutas_id')->references('id')->on('rutas')->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
