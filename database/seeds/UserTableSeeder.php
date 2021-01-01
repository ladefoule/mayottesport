<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Utilisateur Membre
        App\User::create([
            'name' => 'Membre',
            'pseudo' => 'membre',
            'email' => 'membre@membre.fr',
            'role_id' => 1,
            'region_id' => 1,
            'password' => Hash::make('PVR7tP2CqXPgiJsRgE88'),
            'email_verified_at' => now(),
            'created_at' => now(),
            // 'updated_at' => now()
        ]);

        // Utilisateur Premium
        App\User::create([
            'name' => 'Premium',
            'pseudo' => 'premium',
            'email' => 'premium@premium.fr',
            'role_id' => 2,
            'region_id' => 1,
            'password' => Hash::make('UlV1dW94clekWktkAn3v'),
            'email_verified_at' => now(),
            'created_at' => now(),
            // 'updated_at' => now()
        ]);

        // Utilisateur Admin
        App\User::create([
            'name' => 'Admin',
            'pseudo' => 'admin',
            'email' => 'admin@admin.fr',
            'role_id' => 3,
            'region_id' => 1,
            'password' => Hash::make('X4oqY5dOK2AiK0NRqDVS'),
            'email_verified_at' => now(),
            'created_at' => now(),
            // 'updated_at' => now()
        ]);

        // Utilisateur Superadmin
        App\User::create([
            'name' => 'Superadmin',
            'pseudo' => 'superadmin',
            'email' => 'superadmin@superadmin.fr',
            'role_id' => 4,
            'region_id' => 1,
            'password' => Hash::make('ypENj8bCyAIR7jH5aDBH'),
            'email_verified_at' => now(),
            'created_at' => now(),
            // 'updated_at' => now()
        ]);
    }
}
