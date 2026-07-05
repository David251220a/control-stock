<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductoVariante extends Model
{
    protected $guarded = [];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
