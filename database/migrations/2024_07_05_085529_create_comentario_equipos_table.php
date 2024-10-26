<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentarioEquiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentario_equipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_movimiento');
           $table->foreign('id_movimiento')->references('id')->on('movimiento_equipos')->onDelete('cascade');
            $table->string('comentario');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentario_equipos');
    }
}
