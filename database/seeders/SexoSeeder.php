<?php

namespace Database\Seeders;

use App\Models\Sexo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $valores = [
            'SIN ESPECIFICAR',
            'MASCULINO',
            'FEMENINO',
        ];

        foreach ($valores as $item) {
            Sexo::firstOrCreate([
                'descripcion' => $item,
            ]);
        }
    }
}
