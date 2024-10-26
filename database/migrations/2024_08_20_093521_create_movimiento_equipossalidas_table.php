<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoEquipossalidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_equipossalidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_movimiento');
            $table->unsignedBigInteger('id_equipo');
            $table->string('nombre_emisor_salida')->nullable();
            $table->string('nombre_receptor_salida')->nullable();
            $table->string('nombre_motorista_salida')->nullable();
            $table->string('motivo_salida')->nullable();
            $table->string('estado_actual_salida')->nullable();
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
        Schema::dropIfExists('movimiento_equipossalidas');
    }
}
