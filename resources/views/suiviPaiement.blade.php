@extends('modeles.comptable') <!-- ou ton layout principal -->

@section('contenu')
<h1>Suivi des paiements</h1>
<ul>
    @foreach ($fiches as $fiche)
    <li>{{ $fiche['nom'] }} {{ $fiche['prenom'] }} - Fiches à suivre</li>
    @endforeach
</ul>
@endsection
