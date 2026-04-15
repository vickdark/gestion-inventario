<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaEquipo extends Model
{
    protected $table = 'categoria_equipos';
    protected $fillable = ['nombre', 'slug', 'descripcion'];

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'categoria_id');
    }
}
