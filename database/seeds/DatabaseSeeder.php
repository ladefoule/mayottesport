<?php

require 'UsersTableSeeder.php';

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Disable all mass assignment restrictions
        Model::unguard();

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

        $idFootball = App\Sport::firstWhere('nom', 'like', 'football')->id;
        // $idVolleyball = Sport::firstWhere('nom', 'like', 'volleyball');
        // $idHandball = Sport::firstWhere('nom', 'like', 'handball');
        // $idBasketball = Sport::firstWhere('nom', 'like', 'basketball');
        // $idRugby = Sport::firstWhere('nom', 'like', 'rugby');

        // On insère les différentes catégories d'user
        $roles = array('membre' => 10, 'premium' => 20, 'admin' => 30, 'superadmin' => 40);
        foreach ($roles as $role => $niveau) {
            App\Role::create([
                'nom' => $role,
                'niveau' => $niveau
            ]);
        }

        // On insère les regions
        $regions = array("Mayotte", "Métropole", "Autre");
        foreach ($regions as $region) {
            App\Region::create([
                'nom' => $region,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère les équipes de Football
        $equipes = ['AS Sada', 'ASC Abeilles', 'FC Mtsapéré', 'AS Rosador', 'UCS Sada', 'FCO Tsingoni', 'Miracle du sud', 'US Ouangani', 'FC Labattoir', 'Foudre 2000', 'AS Neige', 'ASC Kawéni', 'AS Jumeaux', 'RC Barakani', 'FC Koropa', 'USCJ Koungou', 'Enfants de Mayotte', 'Tchanga SC', 'Étincelles Hamjago', 'AS Racine du nord', 'L\'Espérance d\'Iloni', 'AJ Kani-kéli', 'FC Chiconi', 'FC Kani-bé', 'Bandrélé foot', 'ASJ Moinatrindri', 'Choungui FC', 'Flamme Hajangoua', 'ACS Moinagna', 'RCES Poroani', 'ASCEE Nyambadao', 'AS Papillon d\'honneur', 'AS Mbouini', 'Pamandzi SC', 'FC Dembéni', 'AS Ongojou', 'AS Racine du nord (f)', 'AS Jumelles', 'Equipe féminine du Baobab', 'USC Labattoir (f)', 'AJ Kani-kéli (f)', 'AS Neige (f)', 'Espoir de Mtsapéré', 'Guiné Club', 'Diables noirs', 'Enfant du port', 'Tonnerre du nord', 'AJ Mtsahara', 'Mtsanga 2000', 'FC Tsararano', 'VCO Vahibé', 'US Mtsagamboua', 'Trévani SC', 'USC Labattoir', 'VSS Hagnoundrou', 'AS Kahani', 'USCP Antéou', 'FC Tsoundzou', 'ASJ Handréma', 'TCO Mamoudzou', 'AS Bandraboua', 'FC Sohoa', 'ASCEE Nyambadao (f)', 'Espérance d\'Iloni (f)', 'Bandrélé foot (f)', 'Tchanga SC (f)', 'AS Sada (f)', 'AS Papillon d\'honneur (f)', 'UCS Sada (f)', 'US Kavani', 'Pamandzi SC (f)', 'Éclair du sud', 'US Mtsamoudou', 'USJ Tsararano', 'FC Passamainty', 'Voulvavi sports', 'US Mtsangamboua', 'Olympique de Tsoundzou', 'CS Mramadoudou', 'Étoile Hapandzo', 'Olympique de Miréréni', 'FC Mtsapéré (f)', 'Ecole de foot du nord (f)', 'FC Mtsapéré 2', 'FC Koropa 2', 'USCJ Koungou 2', 'ASDE Kawéni', 'Inter-Koungou', 'Arc En Ciel', 'AS Chababi Lamir', 'Étoile Pamandzi', 'Missile rouge', 'ASCJ Alakarabu', 'FC Shingabwé', 'ASC Abeilles 2', 'ACSJ Mliha', 'ASC Wahadi', 'Enfants de Mayotte 2', 'AJ Mtsahara 2', 'Étincelles Hamjago 2', 'ASJ Moinatrindri 2', 'Chirongui FC 2', 'Lance missile', 'AS Dravani', 'CJ Mronabéja', 'Miréréni SC 2', 'Bandrélé foot 2', 'Feu du centre', 'FC Chiconi (f)', 'CS Mramadoudou (f)', 'FC Koropa (f)', 'Etoile Hapandzo (f)', 'Olympique de Miréréni (f)', 'AS Rosador 2', 'Tornade club', 'VCO Vahibé 2', 'FCO Tsingoni 2', 'Mtsanga 2000 2', 'FC Shingabwé 2', 'USC Kangani', 'AS Vahibé', 'FSC Sohoa 2', 'AS Ndranavi', 'FC Chiconi 2', 'UCS Sada 2', 'VSS Hagnoundrou 2', 'Miréréni SC', 'ASC Sodifram', 'AS Total', 'AS EMCA', 'Maire Mamoudzou', 'Mairie Dz-Labattoir', 'Entente CPSM', 'Equipe SIM', 'OGC TILT OIDF', 'FC Taximen', 'CHM Foot', 'AS Police', 'ACSE DEAL976', 'Ouragan club', 'FC Coconi', 'FC Maboungani', 'Équipe Corpo SIM', 'Mayotte Air Service', 'AS BAMA Service', 'US Avranches', 'Diables noirs 2', 'Maharavou sport', 'Miracle du sud 2', 'FCS Hagnoundrou', 'FC Mtsakandro', 'Voulvavi sports 2', 'Mtsamboro FC', 'US de Bandrélé', 'US Kavani (f)', 'FC Labattoir (f)', 'Foudre 2000 (f)', 'Olympique de Sada (f)', 'TFC Tsoundzou II', 'EF Papillon bleu', 'Ecole de foot du nord', 'AS Colas', 'Asma Agriculture', 'Entente Neige/Antéou', 'Voltigeurs de Châteaubriant', 'Tchanga SC 2', 'ASJ Handréma 2', 'Foudre 2000 2', 'ASCE Miréréni', 'FC Kahani', 'AS Sada 2', 'Maharavou sports 2', 'RC Tsimkoura', 'Dévils de Pamandzi (f)', 'ASC Kawéni (f)', 'Miracle du sud (f)', 'EFF Hamjago (f)', 'ASO Espoir de Chiconi (f)', 'AS Jumeaux 2', 'École de foot de Mdz', 'Entente Barakani/Coconi', 'École de foot de Kawéni', 'Entente Tchanga/Wahadi 2', 'Entente Abeilles/E.F. du nord', 'Entente Neige/Mramadoudou', 'Esp. C du Conseil G.', 'PAF SC', 'Rodez AF', 'Enfant du port 2', 'FC Ylang de Koungou', 'AS Comète', 'FC Mtsakandro 2', 'Mahabou SC', 'US Ouangani 2', 'ASC Kawéni 2', 'ASO Espoir de Chiconi', 'Makoulatsa FC', 'US de Bandrélé 2', 'ASJ Handréma (f)', 'ASC Abeilles (f)', 'FC Mtsakandro (f)', 'AS Tama', 'École de foot Wana Simba', 'Entente Tchanga/Fco', 'Entente Étincelles/mliha', 'Entente Bleu/ndranavi', 'Entente Neige/makoulatsa', 'Lance missile (f)',];
        foreach ($equipes as $nomEquipe) {
            App\Equipe::create([
                'nom' => $nomEquipe,
                // 'equipe_detail' => $nomEquipe,
                'sport_id' => $idFootball
            ]);
        };

        // On insère les villes de Mayotte
        $villes = ['Kawéni', 'Mtsapéré', 'Kavani', 'Passamainty', 'Mamoudzou', 'Vahibé', 'Tsoundzou', 'Majicavo-Koropa', 'Koungou', 'Longoni', 'Trévani', 'Majicavo-Lamir', 'Kangani', 'Labattoir', 'Dzaoudzi', 'Tsararano', 'Dembeni', 'Iloni', 'Ajangoua', 'Ongojou', 'Dzoumogné', 'Bandraboua', 'Handrema', 'Bouyouni', 'Mtsangamboua', 'Combani', 'Tsingoni', 'Mroualé', 'Pamandzi', 'Sada', 'Manjagou', 'Bandrele', 'Mtsamoudou', 'Nyambadao', 'Dapani', 'Hamouro', 'Bambo-Est', 'Ouangani', 'Barakani', 'Kahani', 'Coconi', 'Poroani', 'Tsimkoura', 'Chirongui', 'Miréréni', 'Mramadoudou', 'Malamani', 'Chiconi', 'Sohoa', 'Mtsamboro', 'Mtsahara', 'Hamjago', 'MTsangamouji', 'Chembényoumba', 'Mliha', 'Bouéni', 'Mzouazia', 'Moinatrindi', 'Hagnoundrou', 'Bambo-Ouest', 'Mbouanatsa', 'Kani-Kéli', 'Choungui', 'Kanibé', 'Mbouini', 'Mronabéja', 'Passy-Kéli', 'Acoua', 'Mtsangadoua'];
        foreach ($villes as $ville) {
            App\Ville::create([
                'nom' => $ville,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        };

        // On insère 10 terrains
        for ($i = 1; $i <= 20; $i++) {
            App\Terrain::create([
                'nom' => 'Terrain ' . $i,
                'ville_id' => $i,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère 2 championnats
        $championnats = ['Régional 1', 'Régional 2'];
        foreach ($championnats as $championnat) {
            App\Championnat::create([
                'nom' => $championnat,
                'sport_id' => $idFootball,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère 1 barème
        $champBaremes = ['Football - Victoire à 3pts'];
        foreach ($champBaremes as $champBareme) {
            App\ChampBareme::create([
                'nom' => $champBareme,
                'sport_id' => $idFootball,
                'victoire' => 3,
                'nul' => 1,
                'defaite' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère 1 saison du championnat Régional 1
        App\ChampSaison::create([
            'annee_debut' => date('Y'),
            'annee_fin' => date('Y')+1,
            'nb_journees' => 22,
            'champ_bareme_id' => 1,
            'championnat_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // On insère 22 journées de la saison 1
        for ($i = 1; $i <= 22; $i++) {
            App\ChampJournee::create([
                'numero' => $i,
                'date' => date('Y-m-d'),
                'champ_saison_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // On insère les 12 équipes qui participent à la saison
        // $equipesId = ['29','27','26','97','2','1','4','138','13','162','96','45'];
        // foreach ($equipesId as $equipeId) {
        //     App\ChampSaisonEquipe::create([
        //         'champ_saison_id' => 1,
        //         'equipe_id' => $equipeId
        //     ]);
        // }

        require 'app/scripts/import-calendrier.php';
        require 'app/scripts/gestion-crud-bdd.php';

        // Re enable all mass assignment restrictions
        Model::reguard();
    }
}
