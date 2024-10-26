<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPivot extends Model
{
    use HasFactory;
    
    protected $table = 'user_pivot';

    protected $fillable = [
        'id_usuario',
        'id_sucursal',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }
    
}
