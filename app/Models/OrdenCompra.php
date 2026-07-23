<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdenCompra extends Model
{

    protected $guarded = [];

    public const PAGO_NO_PAGADO = 1;
    public const PAGO_PARCIAL = 2;
    public const PAGO_TOTAL = 3;

    public const BORRADOR = 0;
    public const EMITIDA = 1;
    public const RECEPCION_PARCIAL = 2;
    public const RECEPCION_COMPLETADA = 3;
    public const CANCELADA = 4;

    public const CONTADO = 1;
    public const CREDITO = 2;

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function detalles()
    {
        return $this->hasMany(OrdenCompraDetalle::class);
    }

}
