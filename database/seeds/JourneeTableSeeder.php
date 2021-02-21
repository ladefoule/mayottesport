<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class JourneeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère 22 journées de la saison 1
        // for ($i = 1; $i <= 22; $i++) {
        //     App\Journee::create([
        //         'numero' => $i,
        //         'date' => new Carbon(now()->addDays($i)),
        //         'saison_id' => 1,
        //         'created_at' => now(),
        //         // 'updated_at' => now()
        //     ]);
        // }
    }
}
