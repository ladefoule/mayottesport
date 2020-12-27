<?php

use Illuminate\Database\Seeder;

class VilleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les villes de Mayotte
        $villes = ['Kawéni', 'Mtsapéré', 'Kavani', 'Passamainty', 'Mamoudzou', 'Vahibé', 'Tsoundzou', 'Majicavo-Koropa', 'Koungou', 'Longoni', 'Trévani', 'Majicavo-Lamir', 'Kangani', 'Labattoir', 'Dzaoudzi', 'Tsararano', 'Dembeni', 'Iloni', 'Ajangoua', 'Ongojou', 'Dzoumogné', 'Bandraboua', 'Handrema', 'Bouyouni', 'Mtsangamboua', 'Combani', 'Tsingoni', 'Mroualé', 'Pamandzi', 'Sada', 'Manjagou', 'Bandrele', 'Mtsamoudou', 'Nyambadao', 'Dapani', 'Hamouro', 'Bambo-Est', 'Ouangani', 'Barakani', 'Kahani', 'Coconi', 'Poroani', 'Tsimkoura', 'Chirongui', 'Miréréni', 'Mramadoudou', 'Malamani', 'Chiconi', 'Sohoa', 'Mtsamboro', 'Mtsahara', 'Hamjago', 'MTsangamouji', 'Chembényoumba', 'Mliha', 'Bouéni', 'Mzouazia', 'Moinatrindi', 'Hagnoundrou', 'Bambo-Ouest', 'Mbouanatsa', 'Kani-Kéli', 'Choungui', 'Kanibé', 'Mbouini', 'Mronabéja', 'Passy-Kéli', 'Acoua', 'Mtsangadoua'];
        foreach ($villes as $ville) {
            App\Ville::create([
                'nom' => $ville,
                'created_at' => now(),
                // 'updated_at' => now()
            ]);
        };
    }
}
