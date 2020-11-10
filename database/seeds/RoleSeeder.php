<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les différentes catégories d'user
        $roles = array('membre' => 10, 'premium' => 20, 'admin' => 30, 'superadmin' => 40);
        foreach ($roles as $role => $niveau) {
            App\Role::create([
                'nom' => $role,
                'niveau' => $niveau
            ]);
        }
    }
}
