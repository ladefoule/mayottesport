<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CacheController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\SportController;
use App\Http\Controllers\EquipeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\JourneesMultiplesController;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes(['verify' => true]);

Route::feeds();

/* MIDDLEWARE AUTH */
Route::group(['middleware'=> 'verified'], function () {
    Route::get('/profil', [UserController::class, 'profil'])->name('profil');
    Route::get('/profil/update', [UserController::class, 'updateForm'])->name('profil.update');
    Route::post('/profil/update', [UserController::class, 'updatePost'])->name('profil.update.post');
    Route::get('/profil/update-password', [UserController::class, 'updatePasswordForm'])->name('profil.update-password');
    Route::post('/profil/update-password', [UserController::class, 'updatePasswordPost'])->name('profil.update-password.post');
    Route::get('/profil/delete', [UserController::class, 'delete'])->name('profil.delete');

    Route::get('/{sport}/{competition}/resultat/{uniqid}', [MatchController::class, 'resultat'])->name('competition.match.resultat');
    Route::post('/{sport}/{competition}/resultat/{uniqid}', [MatchController::class, 'resultatPost'])->name('competition.match.resultat.post');

    /* MIDDLEWARE PREMIUM */
    Route::group(['check-permission:premium|admin|superadmin'], function () {
        Route::get('/{sport}/{competition}/horaire/{uniqid}', [MatchController::class, 'horaire'])->name('competition.match.horaire');
        Route::post('/{sport}/{competition}/horaire/{uniqid}', [MatchController::class, 'horairePost'])->name('competition.match.horaire.post');

        Route::get('/adminsharp/login', function(){return redirect()->route('login');})->name('code16.sharp.login');

        /* PREFIX ADMIN */
        Route::prefix('/admin')->middleware(['check-permission:admin|superadmin'])->group(function () {
            Route::prefix('/article')->group(function () {
                Route::get('/create', [ArticleController::class, 'createForm'])->name('article.create');
                Route::post('/create', [ArticleController::class, 'createPost'])->name('article.create.post');
                Route::get('/update/{uniqid}', [ArticleController::class, 'updateForm'])->name('article.update');
                Route::post('/update/{uniqid}', [ArticleController::class, 'updatePost'])->name('article.update.post');
                Route::get('/show/{uniqid}', [ArticleController::class, 'showAdmin'])->name('article.show.admin');
                Route::get('/select', [ArticleController::class, 'selectForm'])->name('article.select');
                Route::post('/select', [ArticleController::class, 'selectPost'])->name('article.select.post');
            });

            /* ----- DEBUT ROUTES JOURNEES ----- */
                Route::get('/journees/multi/select', [JourneesMultiplesController::class, 'select'])->name('journees.multi.select');
                Route::get('/journees/multi/editer/saison-{id}', [JourneesMultiplesController::class, 'edit'])->name('journees.multi.edit');
                Route::post('/journees/multi/editer/saison-{id}', [JourneesMultiplesController::class, 'editPost']);
                Route::get('/journees/multi/saison-{id}', [JourneesMultiplesController::class, 'show'])->name('journees.multi.show');
            /* ----- FIN ROUTES JOURNEES ----- */

            /* DEBUT MIDDLEWARE SUPERADMIN */
            Route::prefix('/superadmin')->middleware(['check-permission:superadmin'])->group(function () {
                Route::get('/cache-flush', [SuperadminController::class, 'cacheFlush'])->name('cache.flush');

                Route::get('/upload-image', [SuperadminController::class, 'imageUpload'])->name('upload.image');
                Route::post('/upload-image', [SuperadminController::class, 'imageUploadPost'])->name('upload.image.post');
                
                Route::get('/script', [SuperadminController::class, 'script'])->name('script');                
                Route::get('/cache-refresh', [SuperadminController::class, 'cacheRefresh'])->name('cache.refresh');                

                /* ----- DEBUT ROUTES PDF PARSER ----- */
                Route::get('/pdfparser', [SuperadminController::class, 'pdfParser'])->name('pdfparser');
                Route::post('/pdfparser', [SuperadminController::class, 'pdfParserPost']);
                /* ----- FIN ROUTES PDF PARSER ----- */
            }); /* FIN MIDDLEWARE SUPERADMIN */
        }); /* FIN PREFIX ADMIN */
    });/* FIN MIDDLEWARE PREMIUM */
}); /* FIN MIDDLEWARE AUTH */

Route::get('/actualites/{titre}__{uniqid}.html', [ArticleController::class, 'show'])->name('article.show');
Route::get('/{sport}/actualites/{titre}__{uniqid}.html', [ArticleController::class, 'showSport'])->name('article.sport.show');

Route::post('/ajax/journees-url-editer', function () {
    return view('journee.ajax-url-editer');
})->name('journees.ajax-url-editer'); // Récupérer l'url d'édition de journées multiples en AJAX

Route::post('/ajax/{table}', [AjaxController::class, 'index'])->name('ajax');

Route::post('/ajax/equipe/matches', [EquipeController::class, 'matchesAjax'])->name('equipe.matches');
Route::get('/ajax/caches/reload', [CacheController::class, 'reload'])->name('cache.reload');

Route::get('contact', [ContactController::class, 'create'])->name('contact');
Route::post('contact', [ContactController::class, 'post'])->name('contact.post');
Route::get('notre-politique-de-confidentialite.php', [HomeController::class, 'politique'])->name('politique');
Route::get('sitemap.xml', [HomeController::class, 'sitemap'])->name('sitemap');

Route::get('/{sport}/{competition}/{annee}/match-{equipeDom}_{equipeExt}_{uniqid}.html', [MatchController::class, 'match'])->name('competition.match');
Route::get('/{sport}/{competition}/classement.html', [CompetitionController::class, 'classement'])->name('competition.classement');
Route::get('/{sport}/{competition}/{annee}/classement.html', [CompetitionController::class, 'classementSaison'])->name('competition.saison.classement');
// Route::get('/{sport}/{competition}/calendrier-et-resultats.html', [CompetitionController::class, 'resultats'])->name('competition.calendrier-resultats');
Route::get('/{sport}/{competition}/{annee}/calendrier-et-resultats/journee-{journee}', [CompetitionController::class, 'resultats'])->name('competition.saison.calendrier-resultats');
Route::get('/{sport}/{competition}/palmares.html', [CompetitionController::class, 'champions'])->name('competition.palmares');
Route::get('/{sport}/{competition}/l-actualite.html', [CompetitionController::class, 'actualite'])->name('competition.actualite');
Route::get('/{sport}/equipe/{equipe}', [EquipeController::class, 'index'])->name('equipe.index');
Route::get('/{sport}/{competition}', [CompetitionController::class, 'index'])->name('competition.index');
Route::get('/{sport}', [SportController::class, 'index'])->name('sport.index');