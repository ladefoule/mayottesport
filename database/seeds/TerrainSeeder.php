<?php

use Illuminate\Database\Seeder;

class TerrainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 10 terrains
        for ($i = 1; $i <= 20; $i++) {
            App\Terrain::create([
                'nom' => 'Terrain ' . $i,
                'ville_id' => $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
