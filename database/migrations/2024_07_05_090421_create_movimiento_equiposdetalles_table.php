<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoEquiposdetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_equiposdetalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_movimiento');
            $table->unsignedBigInteger('id_equipo');
            $table->string('EstadoNombreAsignado')->nullable();
            $table->dateTime('EstadoFechaAsignado')->nullable();
            $table->string('EstadoNombreReparado')->nullable();
            $table->dateTime('EstadoFechaReparado')->nullable();
            $table->string('EstadoNombrePrueba')->nullable();
            $table->dateTime('EstadoFechaPrueba')->nullable();
            
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_movimiento')->references('id')->on('movimiento_equipos')->onDelete('cascade');
            $table->foreign('id_equipo')->references('id')->on('equipos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento_equiposdetalles');
    }
}
