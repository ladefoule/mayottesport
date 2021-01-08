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
            ['name' => 'membre', 'guard_name' => 'web', 'niveau' => 10],
            ['name' => 'premium', 'guard_name' => 'web', 'niveau' => 20],
            ['name' => 'admin', 'guard_name' => 'web', 'niveau' => 30],
            ['name' => 'superadmin', 'guard_name' => 'web', 'niveau' => 40]
        ];
        foreach ($roles as $role) {
            App\Role::create($role);
        }
    }
}
