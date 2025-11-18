<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PdoGsb;
use MyDate;
use Barryvdh\DomPDF\Facade\Pdf;

class ComptableController extends Controller
{
    // 1. PAGE VALIDER FICHES
    function gestionFiches(){
        if(session('comptable') != null){
            $comptable = session('comptable');
            $lesFiches = PdoGsb::getToutesLesFiches();

            return view('validerFiches')
                ->with('lesFiches', $lesFiches)
                ->with('comptable', $comptable)
                ->with('visiteur', $comptable); // Astuce pour éviter l'erreur variable undefined
        }
        else{
            return redirect()->route('chemin_connexion');
        }
    }

    function validerFiche($idVisiteur, $mois){
        if(session('comptable') != null){
            PdoGsb::validerFiche($idVisiteur, $mois);
            return redirect()->route('chemin_gestionFichesComptable')
                ->with('success', 'Fiche validée avec succès !');
        }
        return redirect()->route('chemin_connexion');
    }

    // 2. PAGE SUIVI PAIEMENT
    function suiviPaiement(){
        if(session('comptable') != null){
            $comptable = session('comptable');
            $lesFiches = PdoGsb::getFichesValidees();

            return view('suiviPaiement')
                ->with('lesFiches', $lesFiches)
                ->with('comptable', $comptable)
                ->with('visiteur', $comptable);
        }
        else{
            return redirect()->route('chemin_connexion');
        }
    }

    // ACTION DU FORMULAIRE PAIEMENT
    function payerFiche(Request $request){
        if(session('comptable') != null){
            $idVisiteur = $request->input('idVisiteur');
            $mois = $request->input('mois');

            // Passage à l'état "Remboursée" (RB)
            PdoGsb::majEtatFicheFrais($idVisiteur, $mois, 'RB');

            return redirect()->route('suiviPaiement')
                ->with('success', 'Fiche mise en paiement.');
        }
        return redirect()->route('chemin_connexion');
    }

    // 3. PDF
    public function telechargerPdf($idVisiteur, $mois){
        if(session('comptable') != null){
            $visiteur = PdoGsb::getLeVisiteur($idVisiteur);
            $lesFraisForfait = PdoGsb::getLesFraisForfait($idVisiteur, $mois);
            $lesInfosFicheFrais = PdoGsb::getLesInfosFicheFrais($idVisiteur, $mois);

            $numAnnee = MyDate::extraireAnnee($mois);
            $numMois = MyDate::extraireMois($mois);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif =  $lesInfosFicheFrais['dateModif'];
            $dateModifFr = MyDate::getFormatFrançais($dateModif);

            $pdf = Pdf::loadView('pdf.fichefrais', [
                'visiteur' => $visiteur,
                'lesFraisForfait' => $lesFraisForfait,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois,
                'libEtat' => $libEtat,
                'montantValide' => $montantValide,
                'nbJustificatifs' => $nbJustificatifs,
                'dateModif' => $dateModifFr
            ]);
            return $pdf->download('fiche-'.$visiteur['nom'].'-'.$mois.'.pdf');
        }
        return redirect()->route('chemin_connexion');
    }
}
