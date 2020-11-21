<?php

use Illuminate\Database\Seeder;

class SportTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les sports
        $sports = array("Football" => 'FB', "Volleyball" => 'VB', "Handball" => 'HB', "Basketball" => 'BB', "Rugby" => 'RB');
        foreach ($sports as $sport => $code) {
            App\Sport::create([
                'nom' => $sport,
                'code' => $code,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
