@extends('modeles.sommaireComptable')

@section('contenu1')
    <div id="contenu">
        <h2>Validation des fiches de frais</h2>

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
                    <th>Etat</th>
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
                        <td>{{ $fiche['etat'] }}</td>
                        <td>
                            @if($fiche['idEtat'] != 'VA' && $fiche['idEtat'] != 'RB')
                                <a href="{{ route('chemin_validerFiche', ['idVisiteur' => $fiche['idVisiteur'], 'mois' => $fiche['mois']]) }}"
                                   onclick="return confirm('Valider cette fiche ?');">
                                    Valider
                                </a>
                            @else
                                <span style="color:green">Validée/Payée</span>
                            @endif
                            |
                            <a href="{{ route('chemin_telechargerPdfComptable', ['idVisiteur' => $fiche['idVisiteur'], 'mois' => $fiche['mois']]) }}" title="PDF">
                                PDF
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Aucune fiche à traiter.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
