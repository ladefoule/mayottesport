<?php

use Illuminate\Support\Str;
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
        $i = 1;
        foreach ($sports as $sport => $code) {
            App\Sport::create([
                'nom' => $sport,
                'slug' => Str::slug($sport),
                'home_position' => $i++,
                'created_at' => now(),
                // 'updated_at' => now()
            ]);
        }
    }
}
