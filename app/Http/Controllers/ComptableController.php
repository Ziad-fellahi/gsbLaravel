<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MyApp\PdoGsb;

class GererFraisController extends Controller
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PdoGsb();
    }

    /**
     * Affiche le formulaire de saisie des frais pour le mois courant
     */
    public function saisirFrais()
    {
        // 1. Vérification : Est-ce un Visiteur connecté ?
        if (session('visiteur')) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];

            // Calcul du mois en cours (ex: "202311" pour Novembre 2023)
            $mois = $this->getMois(date("d/m/Y"));
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);

            // Vérifie si c'est le premier frais du mois, sinon crée la nouvelle fiche
            if ($this->pdo->estPremierFraisMois($idVisiteur, $mois)) {
                $this->pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
            }

            // Récupère les frais existants
            $lesFrais = $this->pdo->getLesFraisForfait($idVisiteur, $mois);

            // Affiche la vue (assurez-vous que le fichier resources/views/saisirFrais.blade.php existe)
            return view('saisirFrais', compact('lesFrais', 'numMois', 'numAnnee', 'visiteur'));
        } else {
            // Si ce n'est pas un visiteur, retour à la connexion
            return redirect()->route('chemin_connexion')
                ->with('errors', 'Accès réservé aux visiteurs.');
        }
    }

    /**
     * Enregistre les modifications du formulaire (POST)
     */
    public function sauvegarderFrais(Request $request)
    {
        if (session('visiteur')) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];
            $mois = $this->getMois(date("d/m/Y"));

            // Récupération des données du formulaire (tableau 'lesFrais')
            $lesFrais = $request->input('lesFrais');

            // Validation simple
            if ($this->lesQteFraisValides($lesFrais)) {
                $this->pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
                return redirect()->route('gestionFrais')
                    ->with('success', 'Les modifications ont bien été mises à jour');
            } else {
                return redirect()->route('gestionFrais')
                    ->with('errors', 'Les valeurs des frais doivent être numériques');
            }
        } else {
            return redirect()->route('chemin_connexion');
        }
    }

    /**
     * Retourne le mois au format aaaamm selon la date passée en paramètre (jj/mm/aaaa)
     */
    private function getMois($date)
    {
        @list($jour, $mois, $annee) = explode('/', $date);
        return $annee . $mois;
    }

    /**
     * Vérifie que les quantités de frais sont bien numériques
     */
    private function lesQteFraisValides($lesFrais)
    {
        return collect($lesFrais)->every(function ($qte) {
            return is_numeric($qte);
        });
    }
}
