<?php

namespace Database\Seeders;

use App\Models\Banco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bancos = [
            'SIN ESPECIFICAR',
            'B.N.F.',
            'CONTINENTAL',
            'UENO',
            'ITAU',
            'VISION',
            'FAMILIAR',
            'REGIONAL',
            'GNB',
            'SUDAMERIS',
            'ATLAS',
            'INTERFISA',
            'RÍO',
            'AMAMBAY'
        ];

        foreach ($bancos as $item) {
            Banco::firstOrCreate([
                'descripcion' => $item,
                'estado_id' => 1,
                'user_id' => 1,
            ]);
        }
    }
}
