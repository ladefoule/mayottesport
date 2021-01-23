<?php

use Illuminate\Database\Seeder;

class BaremeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 1 barème
        $baremes = ['Football - Victoire à 3pts'];
        foreach ($baremes as $bareme) {
            App\Bareme::create([
                'nom' => $bareme,
                'sport_id' => 1,
                'victoire' => 3,
                'nul' => 1,
                'defaite' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

        // On insère 1 barème
        $baremes = ['Volley - Classique'];
        foreach ($baremes as $bareme) {
            App\Bareme::create([
                'nom' => $bareme,
                'sport_id' => 2,
                // 'victoire' => 2,
                // 'nul' => 1,
                // 'defaite' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

        // On insère 1 barème
        $baremes = ['Handball - Classique'];
        foreach ($baremes as $bareme) {
            App\Bareme::create([
                'nom' => $bareme,
                'sport_id' => 3,
                'victoire' => 2,
                'nul' => 1,
                'defaite' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

        // On insère 1 barème
        $baremes = ['Basketball - Classique'];
        foreach ($baremes as $bareme) {
            App\Bareme::create([
                'nom' => $bareme,
                'sport_id' => 4,
                'victoire' => 2,
                'nul' => 1,
                'defaite' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }

        // On insère 1 barème
        $baremes = ['Rugby - Classique'];
        foreach ($baremes as $bareme) {
            App\Bareme::create([
                'nom' => $bareme,
                'sport_id' => 5,
                'victoire' => 2,
                'nul' => 1,
                'defaite' => 0,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }
    }
}
