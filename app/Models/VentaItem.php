<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VentaItem extends Model
{
    protected $fillable = [
        'venta_id',
        'itemable_id',
        'itemable_type',
        'nombre',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }
}
