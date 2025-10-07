@extends('modeles.visiteur')

@section('contenu1')
    <h1>Bienvenue {{ $visiteur['prenom'] ?? '' }} {{ $visiteur['nom'] ?? '' }}</h1>
    <p>Vous êtes connecté en tant que <strong>Visiteur</strong>.</p>
@endsection
