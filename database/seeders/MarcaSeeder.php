<?php

namespace Database\Seeders;

use App\Models\Marca;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        $marcas = [
            'SIN MARCA',
            'GENÉRICO',
            'COCA COLA',
            'PEPSI',
            'PULP',
            'MANAOS',
            'LOGITECH',
            'REDRAGON',
            'GENIUS',
            'HP',
            'LENOVO',
            'SAMSUNG',
            'LG',
            'MIDEA',
            'TOKYO',
            'MABE',
            'TRAMONTINA',
            'STANLEY',
            'PRETUL',
            'ATLAS',
        ];

        foreach ($marcas as $marca) {
            Marca::firstOrCreate(
                ['descripcion' => $marca],
                [
                    'estado_id' => 1,
                    'user_id' => 1,
                    'usuario_modificacion' => 1,
                ]
            );
        }
    }
}
