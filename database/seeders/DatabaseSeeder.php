<?php

namespace Database\Seeders;

use App\Models\Ciudad;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Estado;
use App\Models\Secuencia;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $this->call(RoleSeeder::class);

        // User::firstOrCreate([
        //     'name' => 'Admin',
        //     'username' => 'admin',
        //     'email' => 'admin@dev',
        //     'password' => Hash::make('admin123456'),
        // ])->assignRole('admin');

        // Estado::create([
        //     'descripcion' => 'ACTIVO'
        // ]);

        // Estado::create([
        //     'descripcion' => 'INACTIVO'
        // ]);

        // Departamento::create([
        //     'descripcion' => 'CAPITAL'
        // ]);

        // Distrito::create([
        //     'departamento_id' => 1,
        //     'descripcion' => 'ASUNCION (DISTRITO)'
        // ]);

        // Ciudad::create([
        //     'distrito_id' => 1,
        //     'descripcion' => 'ASUNCION (DISTRITO)'
        // ]);

        // Secuencia::create([
        //     'secuencia' => 0
        // ]);

        $this->call([
            // MarcaSeeder::class,
            // TipoTransaccionSeeder::class,
            // EntidadSeeder::class,
            // ActividadEconomicaSeeder::class,
            // FormaCobroSeeder::class,
            // BancoSeeder::class,
            // TipoDocumentoSeeder::class,
            // EstablecimientoSeeder::class,
            // NumeracionSeeder::class,
            // TipoPersonaSeeder::class,
            // SexoSeeder::class,
            // PersonaSeeder::class
            TipoIngresoSeeder::class,
            TipoEgresoSeeder::class
        ]);


    }
}
