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
    Route::post('/comment/delete', 'CommentaireController@delete')->name('comment.delete');

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
                    /* MIDDLEWARE DE VERIFICATION SI LA LISTE DES ATTRIBUTS VISIBLES N'EST PAS VIDE */
                    Route::group(['middleware' => ['attribut-visible']], function () {
                        Route::get('/{table}/create', 'CrudController@create')->name('crud.create');
                        Route::get('/{table}/{id}', 'CrudController@show')->name('crud.show');
                        Route::get('/{table}/ajax', 'CrudController@indexAjax')->name('crud.index-ajax');
                        Route::get('/{table}', 'CrudController@index')->name('crud.index');
                        Route::get('/{table}/update/{id}', 'CrudController@update')->name('crud.update');
                    });
                    Route::post('/{table}/create', 'CrudController@createStore');
                    Route::post('/{table}/update/{id}', 'CrudController@updateStore');
                    Route::get('/{table}/delete/{id}', 'CrudController@delete')->name('crud.delete');
                    Route::post('/{table}/delete', 'CrudController@deleteAjax')->name('crud.delete-ajax');
                });

            }); /* FIN PREFIX CRUD */

            /* DEBUT PREFIX AUTRES */
            Route::prefix('/autres')->group(function () {
                Route::get('/', function(){return redirect()->route('journees.multi.choix-saison');})->name('autres');

                /* ----- DEBUT ROUTES JOURNEES ----- */
                    Route::get('/journees/multi/choix-saison', 'JourneesMultiplesController@choixSaison')->name('journees.multi.choix-saison');
                    Route::get('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiples')->name('journees.multi.editer');
                    Route::post('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiplesPost');
                    Route::get('/journees/multi/saison-{id}', 'JourneesMultiplesController@vueMultiples')->name('journees.multi.voir');
                /* ----- FIN ROUTES JOURNEES ----- */

                /* ----- DEBUT ROUTES MATCHES ----- */
                    Route::get('/matches/foot/ajouter', 'FootMatchController@ajouter')->name('matches.foot.ajouter');
                    Route::post('/matches/foot/ajouter', 'FootMatchController@ajouterPost');
                    Route::get('/matches/foot/editer/{id}', 'FootMatchController@editer')->name('matches.foot.editer');
                    Route::post('/matches/foot/editer/{id}', 'FootMatchController@editerPost');
                    Route::post('/matches/foot/supprimer', 'FootMatchController@supprimer')->name('matches.foot.supprimer');
                    Route::get('/matches/foot/', 'FootMatchController@lister')->name('matches.foot.lister');
                /* ----- FIN ROUTES MATCHES ----- */
            }); /* FIN PREFIX AUTRES */

            /* DEBUT MIDDLEWARE SUPERADMIN */
            Route::prefix('/crud-gestion')->middleware(['check-permission:superadmin'])->group(function () {
                Route::get('/tables', 'CrudAdminController@tables')->name('crud-gestion.tables');
                Route::post('/tables', 'CrudAdminController@tablesPost');

                // Todo : Routes non opérationneles
                // Route::get('/attributs', 'CrudAdminController@attributs')->name('crud-gestion.attributs');
                // Route::post('/attributs/ajax', 'CrudAdminController@attributsAjax')->name('crud-gestion.attributs.ajax');
                // Route::get('/parametres', 'CrudAdminController@parametres')->name('crud-gestion.parametres');

                /* ----- DEBUT ROUTES PDF PARSER ----- */
                Route::get('/pdfparser', 'PdfParserController@get')->name('pdfParser');
                Route::post('/pdfparser', 'PdfParserController@post');
                /* ----- FIN ROUTES PDF PARSER ----- */
            }); /* FIN MIDDLEWARE SUPERADMIN */
        }); /* FIN MIDDLWARE ADMIN */
    });/* FIN MIDDLEWARE PREMIUM */
}); /* FIN MIDDLEWARE AUTH */

Route::post('/ajax/journees-url-editer', function () {
    return view('admin.journees.ajax-url-editer');
})->name('journees.ajax-url-editer'); // Récupérer l'url d'édition de journées multiples en AJAX

Route::match(['get', 'post'], '/ajax/{table}', function ($table) {
    return view('ajax.table', ['table' => $table]);
})->name('ajax');
