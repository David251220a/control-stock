<?php

namespace Database\Seeders;

use App\Models\TipoIngreso;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoIngresoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TipoIngreso::create([
            'descripcion' => 'FACTURA'
        ]);
    }
}
