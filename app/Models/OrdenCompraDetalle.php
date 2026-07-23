<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompraDetalle extends Model
{
    protected $guarded = [];

    public function ordenCompra()
    {
        return $this->belongsTo(OrdenCompra::class);
    }

    public function productoVariante()
    {
        return $this->belongsTo(ProductoVariante::class);
    }

    public function getCantidadPendienteAttribute(): int
    {
        return max(
            0,
            $this->cantidad_solicitada
            - $this->cantidad_recibida
            - $this->cantidad_cancelada
        );
    }


}
