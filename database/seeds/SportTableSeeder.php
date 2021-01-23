<?php

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::insert("
        INSERT INTO `sports` (`id`, `nom`, `slug`, `home_position`, `created_at`, `updated_at`) VALUES
        (1, 'Football', 'football', 1, '2021-01-23 18:22:52', '2021-01-23 18:22:52'),
        (2, 'Volleyball', 'volleyball', 2, '2021-01-23 18:22:52', '2021-01-23 18:22:52'),
        (3, 'Handball', 'handball', 3, '2021-01-23 18:22:52', '2021-01-23 18:22:52'),
        (4, 'Basketball', 'basketball', 4, '2021-01-23 18:22:52', '2021-01-23 18:22:52'),
        (5, 'Rugby', 'rugby', 5, '2021-01-23 18:22:52', '2021-01-23 18:22:52');");
        
        // $sports = array("Football", "Volleyball", "Handball", "Basketball", "Rugby");
        // $i = 1;
        // foreach ($sports as $sport) {
        //     App\Sport::create([
        //         'nom' => $sport,
        //         'slug' => Str::slug($sport),
        //         'home_position' => $i++,
        //         'created_at' => now(),
        //     ]);
        // }
    }
}
