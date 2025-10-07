<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use PdoGsb;

class connexionComptableController extends Controller
{
    function connecter(){

        return view('connexion')->with('erreurs',null);
    }
    function valider(Request $request){
        $login = $request['login'];
        $mdp = $request['mdp'];
        $comptable = PdoGsb::getInfosComptable($login,$mdp);
        if(!is_array($comptable)){
            $erreurs[] = "Login ou mot de passe incorrect(s)";
            return view('connexion')->with('erreurs',$erreurs);
        }
        else{
            session(['comptable' => $comptable]);
            return view('sommaire')->with('comptable',session('comptable'));
        }
    }
    function deconnecter(){
        session(['comptable' => null]);
        return redirect()->route('chemin_connexion');
    }

}
