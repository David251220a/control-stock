<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $guarded = [];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function detalles()
    {
        return $this->hasMany(FacturaDetalle::class);
    }

    public function forma_pagos()
    {
        return $this->hasMany(FacturaCobro::class);
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class);
    }

    public function tipoTransaccionFactura()
    {
        return $this->belongsTo(TipoTransaccion::class, 'tipo_transaccion_id');
    }

    public function timbrado()
    {
        return $this->belongsTo(Timbrado::class);
    }

}
