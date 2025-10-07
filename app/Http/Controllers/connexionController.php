<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;

class connexionController extends Controller
{
    function connecter(){
        return view('connexion')->with('erreurs', null);
    }

    function valider(Request $request){
        $login = $request['login'];
        $mdp = $request['mdp'];

        // Vérification visiteur
        $visiteur = PdoGsb::getInfosVisiteur($login, $mdp);

        if(is_array($visiteur)){
            session(['visiteur' => $visiteur]);
            return view('sommaire')->with('visiteur', session('visiteur'));
        }

        // Si visiteur incorrect → on teste comptable
        $comptable = PdoGsb::getInfosComptable($login, $mdp);

        if(is_array($comptable)){
            session(['comptable' => $comptable]);
            return view('modeles.sommaireComptable')->with('comptable', session('comptable'));

        }

        // Si aucun des deux n’est correct
        $erreurs[] = "Login ou mot de passe incorrect(s)";
        return view('connexion')->with('erreurs', $erreurs);
    }

    function deconnecter(){
        session()->forget(['visiteur','comptable']);
        return redirect()->route('chemin_connexion');
    }
}
