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
                'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }
    }
}
