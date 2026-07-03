<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'admin']);
        // $admin = Role::find(1);

        $permission = Permission::create(['name' => 'rol.index', 'descripcion' => 'Rol Index'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.create', 'descripcion' => 'Rol Crear'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.edit', 'descripcion' => 'Rol Editar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.show', 'descripcion' => 'Rol Ver'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.update', 'descripcion' => 'Rol Actualizar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.store', 'descripcion' => 'Rol Guardar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'rol.delete', 'descripcion' => 'Rol Elimnar'])->syncRoles($admin);

        $permission = Permission::create(['name' => 'usuario.index', 'descripcion' => 'Usuario Index'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.create', 'descripcion' => 'Usuario Crear'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.edit', 'descripcion' => 'Usuario Editar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.show', 'descripcion' => 'Usuario Ver'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.update', 'descripcion' => 'Usuario Actualizar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.store', 'descripcion' => 'Usuario Guardar'])->syncRoles($admin);
        $permission = Permission::create(['name' => 'usuario.delete', 'descripcion' => 'Usuario Elimnar'])->syncRoles($admin);
    }

}
