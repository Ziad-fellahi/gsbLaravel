@extends('modeles.sommaireComptable')

@section('contenu1')
    <div id="contenu">
        <h2>Suivi de paiement</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="encadre">
            <table class="listeLegere">
                <thead>
                <tr>
                    <th>Visiteur</th>
                    <th>Mois</th>
                    <th>Total</th>
                    <th>Date Modif</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($lesFiches as $fiche)
                    <tr>
                        <td>{{ $fiche['nom'] }} {{ $fiche['prenom'] }}</td>
                        <td>{{ substr($fiche['mois'], 4, 2) }}/{{ substr($fiche['mois'], 0, 4) }}</td>
                        <td>{{ $fiche['montantValide'] }} €</td>
                        <td>{{ $fiche['dateModif'] }}</td>
                        <td>
                            <form action="{{ route('chemin_payerFiche') }}" method="POST">
                                @csrf
                                <input type="hidden" name="idVisiteur" value="{{ $fiche['idVisiteur'] }}">
                                <input type="hidden" name="mois" value="{{ $fiche['mois'] }}">
                                <button type="submit" onclick="return confirm('Mettre en paiement ?');">
                                    Mettre en paiement
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">Aucune fiche validée en attente de paiement.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
