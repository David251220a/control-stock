<?php

namespace Database\Seeders;

use App\Models\Persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Persona::create([
            'tipo_persona_id' => 1,
            'sexo_id' => 1,
            'estado_civil' => 0,
            'documento' => '0',
            'ruc' => '00',
            'nombre' => 'SIN NOMBRE',
            'apellido' => '',
            'fecha_nacimiento' => null,
            'direccion' => '',
            'barrio' => '',
            'celular' => '0',
            'email' => 'noreply@gmail.com',
            'estado_id' => 1,
            'user_id' => 1,
            'usuario_modificacion' => 1,
        ]);
    }
}
