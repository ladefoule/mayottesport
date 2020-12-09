<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes(['verify' => true]);

/* MIDDLEWARE AUTH */
Route::group(['middleware'=> 'verified'], function () {
    Route::get('/profil', 'UserController@profil')->name('profil');

    Route::get('/{sport}/{competition}/resultat/{uniqid}', 'MatchController@resultat')->name('competition.match.resultat');
    Route::post('/{sport}/{competition}/resultat/{uniqid}', 'MatchController@resultatPost');

    /* MIDDLEWARE PREMIUM */
    Route::group(['check-permission:premium|admin|superadmin'], function () {
        Route::get('/{sport}/{competition}/horaire/{uniqid}', 'MatchController@horaire')->name('competition.match.horaire');
        Route::post('/{sport}/{competition}/horaire/{uniqid}', 'MatchController@horairePost');

        /* MIDDLEWARE ADMIN */
        Route::prefix('/admin')->middleware(['check-permission:admin|superadmin'])->group(function () {
            Route::get('/', function(){return view('admin.calendrier');})->name('administration');

            /* DEBUT PREFIX CRUD */
            Route::prefix('/crud')->group(function () {
                Route::get('/', function(){return redirect()->route('crud.index', ['table' => 'sports']);})->name('crud');
                Route::get('/{table}', 'CrudController@index')->name('crud.index');
                Route::get('/{table}/create', 'CrudController@createForm')->name('crud.create');
                Route::post('/{table}/create', 'CrudController@createStore');
                Route::get('/{table}/ajax', 'CrudController@indexAjax')->name('crud.index-ajax');
                Route::get('/{table}/{id}', 'CrudController@show')->name('crud.show');
                Route::get('/{table}/{id}/update', 'CrudController@updateForm')->name('crud.update');
                Route::post('/{table}/{id}/update', 'CrudController@updateStore');
                Route::get('/{table}/{id}/delete', 'CrudController@delete')->name('crud.delete');
                Route::post('/{table}/delete', 'CrudController@deleteAjax')->name('crud.delete-ajax');
            }); /* FIN PREFIX CRUD */

            /* DEBUT PREFIX AUTRES */
            Route::prefix('/autres')->group(function () {
                /* ----- DEBUT ROUTES JOURNEES ----- */
                    Route::get('/journees/multi/select', 'JourneesMultiplesController@select')->name('journees.multi.select');
                    Route::get('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@edit')->name('journees.multi.edit');
                    Route::post('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editPost');
                    Route::get('/journees/multi/saison-{id}', 'JourneesMultiplesController@show')->name('journees.multi.show');
                /* ----- FIN ROUTES JOURNEES ----- */
            }); /* FIN PREFIX AUTRES */

            /* DEBUT MIDDLEWARE SUPERADMIN */
            Route::prefix('/crud-superadmin')->middleware(['check-permission:superadmin'])->group(function () {
                Route::get('/tables', 'CrudAdminController@tables')->name('crud-superadmin.tables');
                Route::post('/tables', 'CrudAdminController@tablesPost');

                // Todo : Routes non opérationneles
                Route::get('/attributs', 'CrudAdminController@attributs')->name('crud-superadmin.attributs');
                Route::post('/attributs/ajax', 'CrudAdminController@attributsAjax')->name('crud-superadmin.attributs.ajax');
                // Route::get('/parametres', 'CrudAdminController@parametres')->name('crud-superadmin.parametres');

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

Route::match(['get', 'post'], '/ajax/equipe/matches', 'EquipeController@matchesAjax')->name('equipe.matches');
Route::match(['get', 'post'], '/ajax/caches/reload', 'CacheController@reloadCrud')->name('caches.reload-crud');

Route::get('/{sport}/{competition}/{annee}/match-{equipeDom}_{equipeExt}_{uniqid}.html', 'MatchController@match')->name('competition.match');
Route::get('/{sport}/{competition}/classement.html', 'CompetitionController@classement')->name('competition.classement');
Route::get('/{sport}/{competition}/calendrier-et-resultats.html', 'CompetitionController@resultats')->name('competition.calendrier-resultats');
Route::get('/{sport}/{competition}/palmares.html', 'CompetitionController@champions')->name('competition.champions');
Route::get('/{sport}/equipe/{uniqid}_{equipe}.html', 'EquipeController@index')->name('equipe.index');
Route::get('/{sport}/{competition}', 'CompetitionController@index')->name('competition.index');
Route::get('/{sport}', 'SportController@index')->name('sport.index');
