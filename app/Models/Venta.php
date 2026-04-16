<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Usuarios\Usuario;

class Venta extends Model
{
    protected $fillable = [
        'cotizacion_id',
        'cliente_id',
        'numero_factura',
        'fecha_venta',
        'fecha_evento_inicio',
        'fecha_evento_fin',
        'tipo_alquiler',
        'subtotal',
        'impuesto',
        'total',
        'estado_pago',
        'metodo_pago',
        'notas',
        'created_by'
    ];

    protected $casts = [
        'fecha_venta' => 'date',
        'fecha_evento_inicio' => 'datetime',
        'fecha_evento_fin' => 'datetime',
    ];

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(VentaItem::class);
    }

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'created_by');
    }
}
