<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $guarded = [];

    public function hijos()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
