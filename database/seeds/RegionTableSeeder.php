<?php

use Illuminate\Database\Seeder;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les regions
        $regions = array("Mayotte", "La Réunion", "Métropole", "Autre");
        foreach ($regions as $region) {
            App\Region::create([
                'nom' => $region,
                // 'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }
    }
}
