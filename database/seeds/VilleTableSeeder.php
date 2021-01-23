<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VilleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert("INSERT INTO `villes` (`id`, `nom`) VALUES
        (69, 'Acoua'),
        (20, 'Ajangoua'),
        (38, 'Bambo-Est'),
        (61, 'Bambo-Ouest'),
        (23, 'Bandraboua'),
        (33, 'Bandrélé'),
        (40, 'Barakani'),
        (57, 'Bouéni'),
        (25, 'Bouyouni'),
        (55, 'Chembényoumba'),
        (49, 'Chiconi'),
        (45, 'Chirongui'),
        (64, 'Choungui'),
        (42, 'Coconi'),
        (27, 'Combani'),
        (36, 'Dapani'),
        (18, 'Dembeni'),
        (16, 'Dzaoudzi'),
        (22, 'Dzoumogné'),
        (60, 'Hagnoundrou'),
        (71, 'Hajangoua'),
        (53, 'Hamjago'),
        (37, 'Hamouro'),
        (24, 'Handrema'),
        (72, 'Hapandzo'),
        (19, 'Iloni'),
        (41, 'Kahani'),
        (14, 'Kangani'),
        (63, 'Kani-Kéli'),
        (65, 'Kanibé'),
        (4, 'Kavani'),
        (2, 'Kawéni'),
        (10, 'Koungou'),
        (15, 'Labattoir'),
        (11, 'Longoni'),
        (9, 'Majicavo-Koropa'),
        (13, 'Majicavo-Lamir'),
        (48, 'Malamani'),
        (6, 'Mamoudzou'),
        (32, 'Mangajou'),
        (62, 'Mbouanatsa'),
        (66, 'Mbouini'),
        (46, 'Miréréni'),
        (56, 'Mliha'),
        (59, 'Moinatrindi'),
        (47, 'Mramadoudou'),
        (29, 'Mroalé'),
        (67, 'Mronabéja'),
        (52, 'Mtsahara'),
        (51, 'Mtsamboro'),
        (34, 'Mtsamoudou'),
        (70, 'Mtsangadoua'),
        (26, 'Mtsangamboua'),
        (54, 'Mtsangamouji'),
        (3, 'Mtsapéré'),
        (58, 'Mzouazia'),
        (1, 'Non renseigné'),
        (35, 'Nyambadao'),
        (21, 'Ongojou'),
        (39, 'Ouangani'),
        (30, 'Pamandzi'),
        (5, 'Passamainty'),
        (68, 'Passy-Kéli'),
        (43, 'Poroani'),
        (31, 'Sada'),
        (50, 'Sohoa'),
        (12, 'Trévani'),
        (17, 'Tsararano'),
        (44, 'Tsimkoura'),
        (28, 'Tsingoni'),
        (8, 'Tsoundzou'),
        (7, 'Vahibé');");
    }
}
