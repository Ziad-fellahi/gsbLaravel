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

Route::get('deconnexion',[
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

Route::get('telechargerFrais/{mois}', [
    'as' => 'chemin_telechargerFrais',
    'uses' => EtatFraisController::class . '@telechargerPdf'
]);

/*-------------------- Use case gérer les frais---------------------------*/
Route::get('gererFrais',[
    'as'=>'chemin_gestionFrais',
    'uses'=> GererFraisController::class . '@saisirFrais'
]);

Route::post('sauvegarderFrais',[
    'as'=>'chemin_sauvegardeFrais',
    'uses'=> GererFraisController::class . '@sauvegarderFrais'
]);

/*-------------------- Use case Comptable ---------------------------*/

// 1. Gestion des fiches (Valider)
Route::get('/comptable/fiches', [ComptableController::class, 'gestionFiches'])
    ->name('chemin_gestionFichesComptable');

Route::get('/comptable/valider/{idVisiteur}/{mois}', [ComptableController::class, 'validerFiche'])
    ->name('chemin_validerFiche');

// 2. Suivi de paiement
Route::get('/comptable/suiviPaiement', [ComptableController::class, 'suiviPaiement'])
    ->name('suiviPaiement');

// Route POST pour le paiement (Correction "Method not supported")
Route::post('/comptable/payerFiche', [ComptableController::class, 'payerFiche'])
    ->name('chemin_payerFiche');

// 3. PDF Comptable
Route::get('/comptable/pdf/{idVisiteur}/{mois}', [ComptableController::class, 'telechargerPdf'])
    ->name('chemin_telechargerPdfComptable');
