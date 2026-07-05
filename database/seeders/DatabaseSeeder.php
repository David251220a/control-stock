<?php

namespace Database\Seeders;

use App\Models\Estado;
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

        $this->call(RoleSeeder::class);

        User::firstOrCreate([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@dev',
            'password' => Hash::make('admin123456'),
        ])->assignRole('admin');

        Estado::create([
            'descripcion' => 'ACTIVO'
        ]);

        Estado::create([
            'descripcion' => 'INACTIVO'
        ]);

        $this->call([
            MarcaSeeder::class,
        ]);
    }
}
