@extends('modeles/visiteur')

@section('menu')
    <div id="menuGauche">
        <div id="infosUtil">
            <h2>Comptable</h2>
        </div>
        <ul id="menuList">
            <li class="smenu">
                <strong>Bonjour {{ $comptable['nom'] . ' ' . $comptable['prenom'] }}</strong>
            </li>

            <li class="smenu">
                <a href="{{ route('chemin_gestionFichesComptable') }}" title="Valider fiches de frais">
                    Valider fiches de frais
                </a>
            </li>

            <li class="smenu">
                <a href="{{ route('suiviPaiement') }}" title="Suivre le paiement des fiches de frais">
                    Suivre paiement fiches de frais
                </a>
            </li>

            <li class="smenu">
                <a href="{{ route('chemin_deconnexion') }}" title="Se déconnecter">
                    Déconnexion
                </a>
            </li>
        </ul>
    </div>
@endsection
