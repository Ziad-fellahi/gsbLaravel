<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PdoGsb;
use MyDate;

class GererFraisController extends Controller
{
    /**
     * Affiche le formulaire de saisie des frais pour le mois courant
     */
    public function saisirFrais()
    {
        if (session('visiteur')) {
            $visiteur = session('visiteur');
            $idVisiteur = $visiteur['id'];

            $mois = $this->getMois(date("d/m/Y"));
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);

            if (PdoGsb::estPremierFraisMois($idVisiteur, $mois)) {
                PdoGsb::creeNouvellesLignesFrais($idVisiteur, $mois);
            }

            $lesFrais = PdoGsb::getLesFraisForfait($idVisiteur, $mois);

            return view('saisirFrais')
                ->with('lesFrais', $lesFrais)
                ->with('numMois', $numMois)
                ->with('numAnnee', $numAnnee)
                ->with('visiteur', $visiteur);
        }
        else {
            return redirect()->route('chemin_connexion')
                ->with('errors', 'Veuillez vous connecter.');
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

            $lesFrais = $request->input('lesFrais');

            if ($this->lesQteFraisValides($lesFrais)) {
                PdoGsb::majFraisForfait($idVisiteur, $mois, $lesFrais);

                // CORRECTION ICI : on utilise 'chemin_gestionFrais' au lieu de 'gestionFrais'
                return redirect()->route('chemin_gestionFrais')
                    ->with('success', 'Les modifications ont bien été mises à jour');
            } else {
                // CORRECTION ICI AUSSI
                return redirect()->route('chemin_gestionFrais')
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
