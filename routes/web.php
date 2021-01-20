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
    Route::get('/profil/update', 'UserController@updateForm')->name('profil.update');
    Route::post('/profil/update', 'UserController@updatePost')->name('profil.update.post');
    Route::get('/profil/delete', 'UserController@delete')->name('profil.delete');

    Route::get('/{sport}/{competition}/resultat/{uniqid}', 'MatchController@resultat')->name('competition.match.resultat');
    Route::post('/{sport}/{competition}/resultat/{uniqid}', 'MatchController@resultatPost')->name('competition.match.resultat.post');

    /* MIDDLEWARE PREMIUM */
    Route::group(['check-permission:premium|admin|superadmin'], function () {
        Route::get('/{sport}/{competition}/horaire/{uniqid}', 'MatchController@horaire')->name('competition.match.horaire');
        Route::post('/{sport}/{competition}/horaire/{uniqid}', 'MatchController@horairePost')->name('competition.match.horaire.post');

        Route::get('/adminsharp/login', function(){return redirect()->route('login');})->name('code16.sharp.login');

        /* PREFIX ADMIN */
        Route::prefix('/admin')->middleware(['check-permission:admin|superadmin'])->group(function () {
            Route::prefix('/article')->group(function () {
                Route::get('/create', 'ArticleController@createForm')->name('article.create');
                Route::post('/create', 'ArticleController@createPost')->name('article.create.post');
                Route::get('/update/{uniqid}', 'ArticleController@updateForm')->name('article.update');
                Route::post('/update/{uniqid}', 'ArticleController@updatePost')->name('article.update.post');
                Route::get('/show/{uniqid}', 'ArticleController@showAdmin')->name('article.show.admin');
                Route::get('/select', 'ArticleController@selectForm')->name('article.select');
                Route::post('/select', 'ArticleController@selectPost')->name('article.select.post');
            });

            /* ----- DEBUT ROUTES JOURNEES ----- */
                Route::get('/journees/multi/select', 'JourneesMultiplesController@select')->name('journees.multi.select');
                Route::get('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@edit')->name('journees.multi.edit');
                Route::post('/journees/multi/editer/saison-{id}', 'JourneesMultiplesController@editPost');
                Route::get('/journees/multi/saison-{id}', 'JourneesMultiplesController@show')->name('journees.multi.show');
            /* ----- FIN ROUTES JOURNEES ----- */

            /* DEBUT MIDDLEWARE SUPERADMIN */
            Route::prefix('/superadmin')->middleware(['check-permission:superadmin'])->group(function () {
                Route::post('/cache-flush', 'SuperadminController@cacheFlush')->name('cache-flush');

                Route::get('/upload-image', 'SuperadminController@imageUpload')->name('upload.image');
                Route::post('/upload-image', 'SuperadminController@imageUploadPost')->name('upload.image.post');

                /* ----- DEBUT ROUTES PDF PARSER ----- */
                Route::get('/pdfparser', 'SuperadminController@pdfParser')->name('pdfparser');
                Route::post('/pdfparser', 'SuperadminController@pdfParserPost');
                /* ----- FIN ROUTES PDF PARSER ----- */
            }); /* FIN MIDDLEWARE SUPERADMIN */
        }); /* FIN PREFIX ADMIN */
    });/* FIN MIDDLEWARE PREMIUM */
}); /* FIN MIDDLEWARE AUTH */

Route::get('/actualites/{titre}__{uniqid}.html', 'ArticleController@show')->name('article.show');
Route::get('/{sport}/actualites/{titre}__{uniqid}.html', 'ArticleController@showSport')->name('article.sport.show');

Route::post('/ajax/journees-url-editer', function () {
    return view('journee.ajax-url-editer');
})->name('journees.ajax-url-editer'); // Récupérer l'url d'édition de journées multiples en AJAX

Route::match(['get', 'post'], '/ajax/{table}', function ($table) {
    return view('ajax.table', ['table' => $table]);
})->name('ajax');

Route::match(['get', 'post'], '/ajax/equipe/matches', 'EquipeController@matchesAjax')->name('equipe.matches');
Route::match(['get', 'post'], '/ajax/caches/reload', 'CacheController@reload')->name('caches.reload');

Route::get('contact', 'ContactController@create')->name('contact');
Route::post('contact', 'ContactController@post')->name('contact.post');
Route::get('notre-politique-de-confidentialite.php', 'HomeController@politique')->name('politique');
Route::get('parametrer-les-cookies.html', 'CookieController@getCookies')->name('get-cookies');

Route::get('/{sport}/{competition}/{annee}/match-{equipeDom}_{equipeExt}_{uniqid}.html', 'MatchController@match')->name('competition.match');
Route::get('/{sport}/{competition}/classement.html', 'CompetitionController@classement')->name('competition.classement');
Route::get('/{sport}/{competition}/calendrier-et-resultats.html', 'CompetitionController@resultats')->name('competition.calendrier-resultats');
Route::get('/{sport}/{competition}/palmares.html', 'CompetitionController@champions')->name('competition.palmares');
Route::get('/{sport}/equipe/{equipe}', 'EquipeController@index')->name('equipe.index');
Route::get('/{sport}/{competition}', 'CompetitionController@index')->name('competition.index');
Route::get('/{sport}', 'SportController@index')->name('sport.index');