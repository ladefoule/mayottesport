<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('script.html', function(){ // Page pour tester une fonction ou un script
    return view('script');
});

Route::get('/', function () {return view('accueil');})->name('accueil');
Route::get('/football/{competition}/{annee}/match-{equipeDom}_{equipeExt}_{id}.html', 'FootMatchController@match')->name('champ.foot.match');
Route::get('/football/{competition}/{annee}/classement.html', 'SaisonController@classement')->name('classement');

// Route::get('/football/{competition}/{annee}/classement.html', 'ChampSaisonController@classement')->name('afficher-une-journee');

Auth::routes();

/* MIDDLEWARE AUTH */
Route::group(['middleware'=>'auth'], function () {
    Route::get('/profil', 'UserController@profil')->name('profil');
    Route::post('/comment', 'CommentaireController@store')->name('comment');

    Route::get('/football/championnat/resultat/{id}', 'FootMatchController@resultat')->name('champ.foot.resultat');
    Route::post('/football/championnat/resultat/{id}', 'FootMatchController@resultatPost');

    /* MIDDLEWARE PREMIUM */
    Route::group(['check-permission:premium|admin|superadmin'], function () {
        Route::get('/football/championnat/horaire/match-{id}.html', 'FootMatchController@horaire')->name('champ.foot.horaire');
        Route::post('/football/championnat/horaire/match-{id}.html', 'FootMatchController@horairePost');

        /* MIDDLEWARE ADMIN */
        Route::prefix('/admin')->middleware(['check-permission:admin|superadmin'])->group(function () {
            Route::get('/', function(){return view('admin.calendrier');})->name('administration');

            /* DEBUT PREFIX CRUD */
            Route::prefix('/crud')->group(function () {
                Route::get('/', function(){return redirect()->route('crud.lister', ['table' => 'sports']);})->name('crud');

                /* MIDDLEWARE DE VERIFICATION DU PAREMETRE {table} */
                Route::group(['middleware' => ['verif-table-crud']], function () {
                    Route::get('/{table}/ajouter', 'CrudController@ajouter')->name('crud.ajouter');
                    Route::post('/{table}/ajouter', 'CrudController@ajouterPost');
                    Route::get('/{table}/editer/{id}', 'CrudController@editer')->name('crud.editer');
                    Route::post('/{table}/editer/{id}', 'CrudController@editerPost');
                    Route::get('/{table}/supprimer/{id}', 'CrudController@supprimer')->name('crud.supprimer');
                    Route::post('/{table}/supprimer', 'CrudController@supprimerAjax')->name('crud.supprimer-ajax');
                    Route::get('/{table}/ajax', 'CrudController@listerAjax')->name('crud.lister-ajax');
                    Route::get('/{table}/{id}', 'CrudController@voir')->name('crud.voir');
                    Route::get('/{table}', 'CrudController@lister')->name('crud.lister');
                });

            }); /* FIN PREFIX CRUD */

            /* DEBUT PREFIX AUTRES */
            Route::prefix('/autres')->group(function () {
                Route::get('/', function(){return redirect()->route('champ-journees.multi.choix-saison');})->name('autres');

                /* ----- DEBUT ROUTES JOURNEES ----- */
                    Route::get('/champ-journees/multi/choix-saison', 'JourneesMultiplesController@choixSaison')->name('champ-journees.multi.choix-saison');
                    Route::get('/champ-journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiples')->name('champ-journees.multi.editer');
                    Route::post('/champ-journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiplesPost');
                    Route::get('/champ-journees/multi/saison-{id}', 'JourneesMultiplesController@vueMultiples')->name('champ-journees.multi.voir');
                /* ----- FIN ROUTES JOURNEES ----- */

                /* ----- DEBUT ROUTES MATCHES ----- */
                    Route::get('/champ-matches/foot/ajouter', 'FootMatchController@ajouter')->name('champ-matches.foot.ajouter');
                    Route::post('/champ-matches/foot/ajouter', 'FootMatchController@ajouterPost');
                    Route::get('/champ-matches/foot/editer/{id}', 'FootMatchController@editer')->name('champ-matches.foot.editer');
                    Route::post('/champ-matches/foot/editer/{id}', 'FootMatchController@editerPost');
                    Route::post('/champ-matches/foot/supprimer', 'FootMatchController@supprimer')->name('champ-matches.foot.supprimer');
                    Route::get('/champ-matches/foot/', 'FootMatchController@lister')->name('champ-matches.foot.lister');
                /* ----- FIN ROUTES MATCHES ----- */
            }); /* FIN PREFIX AUTRES */

            /* DEBUT MIDDLEWARE SUPERADMIN */
            Route::prefix('/crud-gestion')->middleware(['check-permission:superadmin'])->group(function () {
                Route::get('/tables', 'CrudAdminController@tables')->name('crud-gestion.tables');
                Route::post('/tables', 'CrudAdminController@tablesPost');
                Route::get('/attributs', 'CrudAdminController@attributs')->name('crud-gestion.attributs');
                Route::post('/attributs/ajax', 'CrudAdminController@attributsAjax')->name('crud-gestion.attributs.ajax');
                Route::get('/parametres', 'CrudAdminController@parametres')->name('crud-gestion.parametres');

                /* ----- DEBUT ROUTES PDF PARSER ----- */
                Route::get('/pdfparser', 'PdfParserController@get')->name('pdfParser');
                Route::post('/pdfparser', 'PdfParserController@post');
                /* ----- FIN ROUTES PDF PARSER ----- */
            }); /* FIN MIDDLEWARE SUPERADMIN */
        }); /* FIN MIDDLWARE ADMIN */
    });/* FIN MIDDLEWARE PREMIUM */
}); /* FIN MIDDLEWARE AUTH */

Route::post('/ajax/champ-journees-url-editer', function () {
    return view('admin.champ-journees.ajax-url-editer');
})->name('champ-journees.ajax-url-editer'); // Récupérer l'url d'édition de journées multiples en AJAX

Route::match(['get', 'post'], '/ajax/{table}', function ($table) {
    return view('ajax.table', ['table' => $table]);
})->name('ajax');
