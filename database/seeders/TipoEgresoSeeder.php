<?php

namespace Database\Seeders;

use App\Models\TipoEgreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoEgresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoEgreso::create([
            'descripcion' => 'Orden de Compra'
        ]);

        TipoEgreso::create([
            'descripcion' => 'Agua'
        ]);

        TipoEgreso::create([
            'descripcion' => 'Luz'
        ]);

        TipoEgreso::create([
            'descripcion' => 'Alquiler'
        ]);

        TipoEgreso::create([
            'descripcion' => 'Funcionario'
        ]);
    }
}
