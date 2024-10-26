<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoEquipoDetalles extends Model
{
    use HasFactory;
   
    protected $table = 'movimiento_equiposdetalles'; // Especifica el nombre de la tabla

    protected $fillable = ['id_movimiento', 'id_equipo','EstadoNombreAsignado','EstadoFechaAsignado','EstadoNombreReparado','EstadoFechaReparado','EstadoNombrePrueba','EstadoFechaPrueba'];

    // Define the relationship with MovimientoEquipo
    public function movimiento()
    {
        return $this->belongsTo(MovimientoEquipo::class, 'id_movimiento');
    }

    // Definición de la relación con el modelo Equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }

    public function movimientoSalida()
{
    return $this->hasOne(MovimientoEquipoSalida::class, 'id_equipo', 'id_equipo')
                ->orderBy('created_at', 'desc');
}

}