<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Usuarios\Usuario;

class Cotizacion extends Model
{
    protected $table = 'cotizaciones';

    protected $fillable = [
        'cliente_id',
        'numero_cotizacion',
        'fecha_emision',
        'fecha_vencimiento',
        'fecha_evento_inicio',
        'fecha_evento_fin',
        'tipo_alquiler',
        'subtotal',
        'impuesto',
        'total',
        'estado',
        'notas',
        'created_by'
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'fecha_evento_inicio' => 'datetime',
        'fecha_evento_fin' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CotizacionItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class, 'id', 'cotizacion_id');
    }
}
