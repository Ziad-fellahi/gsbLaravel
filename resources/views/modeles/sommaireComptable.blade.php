@extends('modeles/visiteur') <!-- tu peux créer modeles/comptable.blade.php si tu veux -->

@section('menu')
    <!-- Menu gauche pour le comptable -->
    <div id="menuGauche">
        <div id="infosUtil">
            <strong>Bonjour {{ $comptable['nom'] . ' ' . $comptable['prenom'] }}</strong>
        </div>
        <ul id="menuList">
            <li class="smenu">
                <a href="{{ route('gestionFrais') }}" title="Valider fiche de frais">Valider fiche de frais</a>
            </li>
            <li class="smenu">
                <a href="{{ route('suiviPaiement') }}" title="Suivre paiement">Suivre paiement</a>
            </li>
            <li class="smenu">
                <a href="{{ route('deconnexion') }}" title="Se déconnecter">Déconnexion</a>
            </li>
        </ul>
    </div>
@endsection
