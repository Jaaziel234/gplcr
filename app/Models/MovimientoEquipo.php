<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimientoEquipo extends Model
{
    use HasFactory;
    protected $fillable = ['id_sucursal', 'numerocontrol_salida', 'numerocontrol_entrada', 'nombre_emisor', 'nombre_receptor', 'nombre_motorista', 'motivo', 'nombre_destino', 'DestinoSucursal','estado_actual' ];

    // Definir la relación con MovimientoEquipoDetalles
    public function movimientoEquipoDetalles()
    {
        return $this->hasMany(MovimientoEquipoDetalles::class, 'id_movimiento', 'id');
    }

    // Otra relación para obtener los equipos directamente
    public function equipos()
    {
        return $this->hasManyThrough(Equipo::class, MovimientoEquipoDetalles::class, 'id_movimiento', 'id', 'id', 'id_equipo');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, 'DestinoSucursal');
    }

    // app/Models/MovimientoEquipo.php

    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
}
