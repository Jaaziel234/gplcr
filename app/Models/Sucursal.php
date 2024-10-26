<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursales';

    protected $fillable = ['nombre_sucursal', 'direccion'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'id_sucursal', 'id');
    }
}
