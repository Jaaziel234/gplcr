<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimientoEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimiento_equipos', function (Blueprint $table) {
            $table->id();
             // Agrega la columna id_sucursal antes de definir la clave foránea
            $table->unsignedBigInteger('id_sucursal');
            $table->string('numerocontrol_salida');
            $table->string('numerocontrol_entrada')->nullable();
            $table->string('nombre_emisor');
            $table->string('nombre_receptor');
            $table->string('nombre_motorista');
            $table->string('motivo');
           /*  $table->string('nombre_destino')->nullable(); */
            $table->string('DestinoSucursal')->nullable();
            $table->string('estado_actual')->nullable();
           
            $table->timestamps();
           $table->foreign('id_sucursal')->references('id')->on('sucursales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimiento_equipos');
    }
}
