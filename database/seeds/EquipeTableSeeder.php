<?php

use Illuminate\Database\Seeder;

class EquipeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On insère les équipes de Football
        $equipes = ['AS Sada', 'ASC Abeilles', 'FC Mtsapéré', 'AS Rosador', 'UCS Sada', 'FCO Tsingoni', 'Miracle du sud', 'US Ouangani', 'FC Labattoir', 'Foudre 2000', 'AS Neige', 'ASC Kawéni', 'AS Jumeaux', 'RC Barakani', 'FC Koropa', 'USCJ Koungou', 'Enfants de Mayotte', 'Tchanga SC', 'Étincelles Hamjago', 'AS Racine du nord', 'L\'Espérance d\'Iloni', 'AJ Kani-kéli', 'FC Chiconi', 'FC Kani-bé', 'Bandrélé foot', 'ASJ Moinatrindri', 'Choungui FC', 'Flamme Hajangoua', 'ACS Moinagna', 'RCES Poroani', 'ASCEE Nyambadao', 'AS Papillon d\'honneur', 'AS Mbouini', 'Pamandzi SC', 'FC Dembéni', 'AS Ongojou', 'AS Racine du nord (f)', 'AS Jumelles', 'Equipe féminine du Baobab', 'USC Labattoir (f)', 'AJ Kani-kéli (f)', 'AS Neige (f)', 'Espoir de Mtsapéré', 'Guiné Club', 'Diables noirs', 'Enfant du port', 'Tonnerre du nord', 'AJ Mtsahara', 'Mtsanga 2000', 'FC Tsararano', 'VCO Vahibé', 'US Mtsagamboua', 'Trévani SC', 'USC Labattoir', 'VSS Hagnoundrou', 'AS Kahani', 'USCP Antéou', 'FC Tsoundzou', 'ASJ Handréma', 'TCO Mamoudzou', 'AS Bandraboua', 'FC Sohoa', 'ASCEE Nyambadao (f)', 'Espérance d\'Iloni (f)', 'Bandrélé foot (f)', 'Tchanga SC (f)', 'AS Sada (f)', 'AS Papillon d\'honneur (f)', 'UCS Sada (f)', 'US Kavani', 'Pamandzi SC (f)', 'Éclair du sud', 'US Mtsamoudou', 'USJ Tsararano', 'FC Passamainty', 'Voulvavi sports', 'US Mtsangamboua', 'Olympique de Tsoundzou', 'CS Mramadoudou', 'Étoile Hapandzo', 'Olympique de Miréréni', 'FC Mtsapéré (f)', 'Ecole de foot du nord (f)', 'FC Mtsapéré 2', 'FC Koropa 2', 'USCJ Koungou 2', 'ASDE Kawéni', 'Inter-Koungou', 'Arc En Ciel', 'AS Chababi Lamir', 'Étoile Pamandzi', 'Missile rouge', 'ASCJ Alakarabu', 'FC Shingabwé', 'ASC Abeilles 2', 'ACSJ Mliha', 'ASC Wahadi', 'Enfants de Mayotte 2', 'AJ Mtsahara 2', 'Étincelles Hamjago 2', 'ASJ Moinatrindri 2', 'Chirongui FC 2', 'Lance missile', 'AS Dravani', 'CJ Mronabéja', 'Miréréni SC 2', 'Bandrélé foot 2', 'Feu du centre', 'FC Chiconi (f)', 'CS Mramadoudou (f)', 'FC Koropa (f)', 'Etoile Hapandzo (f)', 'Olympique de Miréréni (f)', 'AS Rosador 2', 'Tornade club', 'VCO Vahibé 2', 'FCO Tsingoni 2', 'Mtsanga 2000 2', 'FC Shingabwé 2', 'USC Kangani', 'AS Vahibé', 'FSC Sohoa 2', 'AS Ndranavi', 'FC Chiconi 2', 'UCS Sada 2', 'VSS Hagnoundrou 2', 'Miréréni SC', 'ASC Sodifram', 'AS Total', 'AS EMCA', 'Maire Mamoudzou', 'Mairie Dz-Labattoir', 'Entente CPSM', 'Equipe SIM', 'OGC TILT OIDF', 'FC Taximen', 'CHM Foot', 'AS Police', 'ACSE DEAL976', 'Ouragan club', 'FC Coconi', 'FC Maboungani', 'Équipe Corpo SIM', 'Mayotte Air Service', 'AS BAMA Service', 'US Avranches', 'Diables noirs 2', 'Maharavou sport', 'Miracle du sud 2', 'FCS Hagnoundrou', 'FC Mtsakandro', 'Voulvavi sports 2', 'Mtsamboro FC', 'US de Bandrélé', 'US Kavani (f)', 'FC Labattoir (f)', 'Foudre 2000 (f)', 'Olympique de Sada (f)', 'TFC Tsoundzou II', 'EF Papillon bleu', 'Ecole de foot du nord', 'AS Colas', 'Asma Agriculture', 'Entente Neige/Antéou', 'Voltigeurs de Châteaubriant', 'Tchanga SC 2', 'ASJ Handréma 2', 'Foudre 2000 2', 'ASCE Miréréni', 'FC Kahani', 'AS Sada 2', 'Maharavou sports 2', 'RC Tsimkoura', 'Dévils de Pamandzi (f)', 'ASC Kawéni (f)', 'Miracle du sud (f)', 'EFF Hamjago (f)', 'ASO Espoir de Chiconi (f)', 'AS Jumeaux 2', 'École de foot de Mdz', 'Entente Barakani/Coconi', 'École de foot de Kawéni', 'Entente Tchanga/Wahadi 2', 'Entente Abeilles/E.F. du nord', 'Entente Neige/Mramadoudou', 'Esp. C du Conseil G.', 'PAF SC', 'Rodez AF', 'Enfant du port 2', 'FC Ylang de Koungou', 'AS Comète', 'FC Mtsakandro 2', 'Mahabou SC', 'US Ouangani 2', 'ASC Kawéni 2', 'ASO Espoir de Chiconi', 'Makoulatsa FC', 'US de Bandrélé 2', 'ASJ Handréma (f)', 'ASC Abeilles (f)', 'FC Mtsakandro (f)', 'AS Tama', 'École de foot Wana Simba', 'Entente Tchanga/Fco', 'Entente Étincelles/mliha', 'Entente Bleu/ndranavi', 'Entente Neige/makoulatsa', 'Lance missile (f)',
                    'Paris', 'Marseille', 'Lyon', 'Saint-Etienne', 'Bordeaux', 'Monaco'];
        foreach ($equipes as $nomEquipe) {
            App\Equipe::create([
                'nom' => $nomEquipe,
                'sport_id' => 1,
                'ville_id' => rand(1,69),
                'uniqid' => uniqid()
            ]);
        };

        // On insère les équipes de Basketball
        $equipes = ['Etoile bleue','Fuz\'ellips','TCO','Jeunesse canon','BC Mtsapéré','Jeux d\'Afrique','Vautour','Gladiator','CB Mangajou','Rapide éclair','Golden force','Scolo dunks','Chicago club','BC Iloni','Koropa fusion','Colorado Beetle','Labattoir BC','Rapides Eclairs','ABS Sada','ASBB Dembéni','BC Acoua','Entente Sportive CSSM','Raptorz','BAK7','TCO 2','Etoile bleue 2','Wenka city','BO Ouangani','BC Tsararano','Flash','Vautour 2','ASC Tsimkoura','BC Chiconi','Barakani Coconi','Tonic club','BC Mtsapéré (f)','Vautour (f)','Chicago club (f)','Golden force (f)','Fuz\'ellips (f)','Etoile bleue (f)','BC Iloni (f)','BAK7 (f)','Barakani Coconi (f)','Jeunesse canon (f)'];
        foreach ($equipes as $nomEquipe) {
            App\Equipe::create([
                'nom' => $nomEquipe,
                'sport_id' => 4,
                'ville_id' => rand(1,69),
                'uniqid' => uniqid()
            ]);
        };

        // On insère les équipes de Handball
        $equipes = ["Tchanga HB","ASC Tsingoni","CH Combani","AJH Tsimkoura","HC Kani-Kéli","HC Mtsapéré","HC Acoua","AC Chiconi","HC Labattoir","HC Bandrélé","Alakarabu HB","PC Bouéni","HC Passamainty","Haïma Sada","US Mbouanatsa","FJS Sohoa","HC Chirongui","MS Mtsangadoua","HC Doujani","HCMK Majicavo","Bandraboua HC","HCMK Majicavo 2","Bandraboua HC 2","TH Mamoudzou","AJH Koungou","HC Dembéni","HC Dzoumogné","Rinasport","Moidrou Sport","Poroani HB","CH Combani (f)","AJH Tsimkoura (f)","HC Kani-kéli (f)","ASC Tsingoni (f)","PC Bouéni (f)","Haïma Sada (f)","Tchanga HB (f)","HC Bandrélé (f)","HC Dembéni (f)","AC Chiconi (f)","HC Labattoir (f)","Moidrou sport (f)","TCO Mamoudzou","AJH Tsimkoura 2","Nyambadao HC","ASC Tsingoni 2","HC Passamainty (f)","AJH Tsimkoura 2 (f)","CAM Mtsahara (f)","TCO Mamoudzou (f)","HC Acoua (f)","Rinasport (f)","HC Chirongui (f)","Bandraboua HC (f)","Tsingoni HB","CAM Mtsahara","CH Combani 2","Alakarabu HB (f)","AC Chiconi 2","HC Iloni","HC Mtsapéré 2","HC Dzoumogné 2"];
        foreach ($equipes as $nomEquipe) {
            App\Equipe::create([
                'nom' => $nomEquipe,
                'sport_id' => 3,
                'ville_id' => rand(1,69),
                'uniqid' => uniqid()
            ]);
        };

        // On insère les équipes de Volleyball
        $equipes = ["Zamfi Mtsamboro","VC Mtsapéré","VC Vahibé","Mtsangadoua AV","VC Petite-Terre","VC Barakani","VA Bouéni","SVC Mtsamoudou","VB Mtsangamouji","CV Choungui","VC Kani-bé (f)","JV Mzouazia","VO Sada","CV Choungui (f)","VC Tsingoni","AOCJ Ouangani","6VC Chiconi","Mbouini VC","Passamainty VC","VCN Acoua","Mronabéja VB","Moinatrindri OV","Lareec Mtsamboro","VBC du Nord","AVB Mronabéja","ASCJ Dapani","All Stars Pamandzi","Ouréwaguini Bandrélé","Lunos Kani-kéli","Dembéni","VC Mtsapéré (f)","Mbouini VC (f)","Mronabéja VB (f)","All Stars (f)","VC Tsingoni (f)","VB Mtsangamouji (f)","Zamfi Mtsamboro (f)","Ouangani","VO Sada (f)","VC Vahibé (f)","AOCJ Ouangani (f)"];
        foreach ($equipes as $nomEquipe) {
            App\Equipe::create([
                'nom' => $nomEquipe,
                'sport_id' => 2,
                'ville_id' => rand(1,69),
                'uniqid' => uniqid()
            ]);
        };
    }
}
