<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio_dia',
        'precio_hora',
        'cantidad_total',
        'cantidad_disponible',
        'imagen',
        'estado'
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaEquipo::class, 'categoria_id');
    }
}
