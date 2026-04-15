<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio_dia', 'precio_hora', 'imagen', 'activo'];

    public function equipos()
    {
        return $this->belongsToMany(Equipo::class, 'equipo_paquete')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
