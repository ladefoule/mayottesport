<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les différentes catégories d'user
        $roles = [
            ['name' => 'membre', 'guard_name' => 'web'],
            ['name' => 'premium', 'guard_name' => 'web'],
            ['name' => 'admin', 'guard_name' => 'web'],
            ['name' => 'superadmin', 'guard_name' => 'web']
        ];
        foreach ($roles as $role) {
            App\Role::create($role);
        }
    }
}
