@extends('modeles.comptable')

@section('contenu')
    <div id="contenu">
        <h2>Bienvenue sur l'espace comptable</h2>

        <div class="alert alert-info" role="alert">
            <p>
                Bienvenue
                <strong>{{ session('comptable')['prenom'] }} {{ session('comptable')['nom'] }}</strong>.
                <br>
                Vous êtes connecté en tant que comptable.
            </p>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Validation des fiches</h3>
                    </div>
                    <div class="panel-body">
                        <p>Valider les fiches de frais des visiteurs pour le mois dernier.</p>
                        <a href="{{ route('chemin_gestionFichesComptable') }}" class="btn btn-primary">Accéder à la validation</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Suivi des paiements</h3>
                    </div>
                    <div class="panel-body">
                        <p>Mettre en paiement les fiches validées.</p>
                        <a href="{{ route('suiviPaiement') }}" class="btn btn-warning">Accéder au suivi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
