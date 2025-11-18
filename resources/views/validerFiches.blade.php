@extends('modeles.comptable')

@section('contenu')
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Validation des fiches de frais</h3>
        </div>
        <div class="panel-body">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Visiteur</th>
                    <th>Mois</th>
                    <th>Justificatifs</th>
                    <th>Montant</th>
                    <th>État actuel</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($fiches as $fiche)
                    <tr>
                        <!-- Nom et Prénom (grâce à la jointure SQL ajoutée précédemment) -->
                        <td>{{ $fiche['nom'] }} {{ $fiche['prenom'] }}</td>

                        <!-- Formatage du mois (ex: 202301 -> 01/2023) -->
                        <td>
                            {{ substr($fiche['mois'], 4, 2) }}/{{ substr($fiche['mois'], 0, 4) }}
                        </td>

                        <td class="text-center">{{ $fiche['nbJustificatifs'] }}</td>
                        <td class="text-right">{{ $fiche['montantValide'] }} €</td>

                        <!-- Affichage de l'état avec une couleur conditionnelle -->
                        <td>
                            @if($fiche['idEtat'] == 'VA')
                                <span class="label label-success">Validée</span>
                            @elseif($fiche['idEtat'] == 'CL')
                                <span class="label label-warning">Saisie clôturée</span>
                            @elseif($fiche['idEtat'] == 'CR')
                                <span class="label label-info">En cours</span>
                            @else
                                {{ $fiche['etat'] }}
                            @endif
                        </td>

                        <!-- Bouton d'action -->
                        <td>
                            <!-- On n'affiche le bouton Valider que si la fiche n'est pas déjà validée -->
                            @if($fiche['idEtat'] != 'VA')
                                <a href="{{ route('chemin_validerFiche', ['idVisiteur' => $fiche['idVisiteur'], 'mois' => $fiche['mois']]) }}"
                                   class="btn btn-success btn-xs"
                                   onclick="return confirm('Voulez-vous vraiment valider cette fiche ?');">
                                    <span class="glyphicon glyphicon-ok"></span> Valider
                                </a>
                            @else
                                <span class="text-muted"><small>Déjà validée</small></span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
