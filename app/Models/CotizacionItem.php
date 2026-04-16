<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CotizacionItem extends Model
{
    protected $fillable = [
        'cotizacion_id',
        'itemable_id',
        'itemable_type',
        'nombre',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function cotizacion(): BelongsTo
    {
        return $this->belongsTo(Cotizacion::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }
}
