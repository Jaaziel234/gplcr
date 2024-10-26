<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $fillable = ['id_sucursal', 'escaneo', 'equipo', 'modelo', 'serie', 'observaciones', 'ubicacion_equipo', 'descripcion_equipo', 'created_at', 'updated_at'];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal', 'id');
    }

    public function movimientoDetalles()
    {
        return $this->belongsTo(MovimientoEquipoDetalles::class, 'id_equipo', 'id');
    }
}
