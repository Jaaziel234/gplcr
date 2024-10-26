<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoEquipoSalida extends Model
{
    use HasFactory;

    protected $table = 'movimiento_equipossalidas'; // Especifica el nombre de la tabla

    protected $fillable = ['id_movimiento', 'id_equipo','nombre_emisor_salida','nombre_receptor_salida','nombre_motorista_salida','motivo_salida','estado_actual_salida'];

    // Define la relación con MovimientoEquipo
    public function movimiento()
    {
        return $this->belongsTo(MovimientoEquipo::class, 'id_movimiento');
    }

    // Definición de la relación con el modelo Equipo
    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'id_equipo');
    }
}
