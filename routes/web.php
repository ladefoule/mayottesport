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

// Test
Route::get('/', function () {return view('accueil');})->name('accueil');

Route::get('/{sport}/{competition}/{annee}/match-{equipeDom}_{equipeExt}_{id}.html', 'MatchController@match')->name('competition.match');
Route::get('/{sport}/{competition}/classement.html', 'CompetitionController@classement')->name('competition.classement');
Route::get('/{sport}/{competition}/calendrier-et-resultats/{journee}e-journee.html', 'CompetitionController@journee')->name('competition.journee');
Route::get('/{sport}/{competition}/palmares.html', 'CompetitionController@classement')->name('competition.palmares');
Route::get('/{sport}/{competition}', 'CompetitionController@index')->name('competition.index');
Route::get('/{sport}', 'SportController@index')->name('sport.index');

Auth::routes();

/* MIDDLEWARE AUTH */
Route::group(['middleware'=>'auth'], function () {
    Route::get('/profil', 'UserController@profil')->name('profil');
    Route::post('/comment', 'CommentaireController@store')->name('comment');
    Route::post('/comment/delete', 'CommentaireController@delete')->name('comment.delete');

    Route::get('/{sport}/{competition}/resultat/{id}', 'MatchController@resultat')->name('competition.match.resultat');
    Route::post('/{sport}/{competition}/resultat/{id}', 'MatchController@resultatPost');

    /* MIDDLEWARE PREMIUM */
    Route::group(['check-permission:premium|admin|superadmin'], function () {
        Route::get('/{sport}/{competition}/horaire/{id}', 'MatchController@horaire')->name('competition.match.horaire');
        Route::post('/{sport}/{competition}/horaire/{id}', 'MatchController@horairePost');

        /* MIDDLEWARE ADMIN */
        Route::prefix('/admin')->middleware(['check-permission:admin|superadmin'])->group(function () {
            Route::get('/', function(){return view('admin.calendrier');})->name('administration');

            /* DEBUT PREFIX CRUD */
            Route::prefix('/crud')->group(function () {
                Route::get('/', function(){return redirect()->route('crud.index', ['table' => 'sports']);})->name('crud');

                /* MIDDLEWARE DE VERIFICATION DU PAREMETRE {table} */
                Route::group(['middleware' => ['verif-table-crud']], function () {
                    /* MIDDLEWARE DE VERIFICATION SI LA LISTE DES ATTRIBUTS VISIBLES N'EST PAS VIDE */
                    Route::group(['middleware' => ['attribut-visible']], function () {
                        Route::get('/{table}/create', 'CrudController@createForm')->name('crud.create');
                        Route::get('/{table}/ajax', 'CrudController@indexAjax')->name('crud.index-ajax');
                        Route::get('/{table}/{id}', 'CrudController@show')->name('crud.show');
                        Route::get('/{table}', 'CrudController@index')->name('crud.index');
                        Route::get('/{table}/update/{id}', 'CrudController@updateForm')->name('crud.update');
                    });
                    Route::post('/{table}/create', 'CrudController@createStore');
                    Route::post('/{table}/update/{id}', 'CrudController@updateStore');
                    Route::get('/{table}/delete/{id}', 'CrudController@delete')->name('crud.delete');
                    Route::post('/{table}/delete', 'CrudController@deleteAjax')->name('crud.delete-ajax');
                });

            }); /* FIN PREFIX CRUD */

            /* DEBUT PREFIX AUTRES */
            Route::prefix('/autres')->group(function () {
                // Route::get('/', function(){return redirect()->route('journees.multi.choix-saison');})->name('autres');

                /* ----- DEBUT ROUTES JOURNEES ----- */
                    Route::get('/journees/multi/choix-saison', 'JourneesMultiplesController@choixSaison')->name('journees.multi.choix-saison');
                    Route::get('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiples')->name('journees.multi.editer');
                    Route::post('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editMultiplesPost');
                    Route::get('/journees/multi/saison-{id}', 'JourneesMultiplesController@vueMultiples')->name('journees.multi.voir');
                /* ----- FIN ROUTES JOURNEES ----- */

                // Todo : Routes non opérationneles
                /* ----- DEBUT ROUTES MATCHES ----- */
                    // Route::get('/matches/ajouter', 'MatchController@ajouter')->name('matches.ajouter');
                    // Route::post('/matches/ajouter', 'MatchController@ajouterPost');
                    // Route::get('/matches/editer/{id}', 'MatchController@editer')->name('matches.editer');
                    // Route::post('/matches/editer/{id}', 'MatchController@editerPost');
                    // Route::post('/matches/delete', 'MatchController@deleteAjax')->name('matches.delete');
                    // Route::get('/matches', 'MatchController@lister')->name('matches.lister');
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
