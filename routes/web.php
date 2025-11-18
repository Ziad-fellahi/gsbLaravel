<?php

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

/*-------------------- Imports des Contrôleurs ---------------------------*/
use App\Http\Controllers\ConnexionController;
use App\Http\Controllers\EtatFraisController;
use App\Http\Controllers\GererFraisController;
use App\Http\Controllers\ComptableController;

/*-------------------- Use case connexion---------------------------*/
Route::get('/',[
    'as' => 'chemin_connexion',
    'uses' => ConnexionController::class . '@connecter'
]);

Route::post('/',[
    'as'=>'chemin_valider',
    'uses'=> ConnexionController::class . '@valider'
]);

// 1. Route pour le Comptable (nom court)
Route::get('deconnexion',[
    'as'=>'deconnexion',
    'uses'=> ConnexionController::class . '@deconnecter'
]);

// 2. Route Doublure pour le Visiteur (ancien nom 'chemin_...')
Route::get('logout',[
    'as'=>'chemin_deconnexion',
    'uses'=> ConnexionController::class . '@deconnecter'
]);

/*-------------------- Use case état des frais---------------------------*/
Route::get('selectionMois',[
    'as'=>'chemin_selectionMois',
    'uses'=> EtatFraisController::class . '@selectionnerMois'
]);

Route::post('listeFrais',[
    'as'=>'chemin_listeFrais',
    'uses'=> EtatFraisController::class . '@voirFrais'
]);

/*-------------------- Use case gérer les frais---------------------------*/

// 1. La route officielle (attendue par le Contrôleur de Connexion)
Route::get('gererFrais',[
    'as'=>'chemin_gestionFrais',
    'uses'=> GererFraisController::class . '@saisirFrais'
]);

// 2. La route "Doublure" (attendue par le Menu Visiteur)
Route::get('saisirFrais',[
    'as'=>'gestionFrais',
    'uses'=> GererFraisController::class . '@saisirFrais'
]);

// CORRECTION : Ajout de la route POST pour 'saisirFrais'
Route::post('saisirFrais',[
    'uses'=> GererFraisController::class . '@sauvegarderFrais'
]);

// Route pour accepter le POST direct sur la page 'gererFrais'
Route::post('gererFrais',[
    'uses'=> GererFraisController::class . '@sauvegarderFrais'
]);

Route::post('sauvegarderFrais',[
    'as'=>'chemin_sauvegardeFrais',
    'uses'=> GererFraisController::class . '@sauvegarderFrais'
]);

/*-------------------- Use case Comptable---------------------------*/

// Routes pour le comptable
Route::get('/comptable/fiches', [ComptableController::class, 'gestionFiches'])
    ->name('chemin_gestionFichesComptable');

Route::get('/comptable/valider/{idVisiteur}/{mois}', [ComptableController::class, 'validerFiche'])
    ->name('chemin_validerFiche');

Route::get('/comptable/suiviPaiement', [ComptableController::class, 'suiviPaiement'])
    ->name('suiviPaiement');
