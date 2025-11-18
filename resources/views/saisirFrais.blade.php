@extends('sommaire')

@section('contenu1')
    <div id="contenu">
        <h2>Renseigner ma fiche de frais du mois {{ $numMois }}-{{ $numAnnee }}</h2>

        @if(session('success'))
            <div class="alert alert-success" style="color: green; font-weight: bold; margin-bottom: 15px;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('errors'))
            <div class="alert alert-danger" style="color: red; font-weight: bold; margin-bottom: 15px;">
                {{ session('errors') }}
            </div>
        @endif

        <form method="POST" action="{{ route('chemin_sauvegardeFrais') }}">
            @csrf <div class="corpsForm">
                <fieldset>
                    <legend>Eléments forfaitisés</legend>

                    @foreach($lesFrais as $unFrais)
                        <div class="form-group" style="margin-bottom: 10px;">
                            <label for="idFrais_{{ $unFrais['idfrais'] }}" style="display:inline-block; width:150px;">
                                {{ $unFrais['libelle'] }} :
                            </label>
                            <input type="text"
                                   id="idFrais_{{ $unFrais['idfrais'] }}"
                                   name="lesFrais[{{ $unFrais['idfrais'] }}]"
                                   value="{{ $unFrais['quantite'] }}"
                                   size="10" maxlength="5"
                                   style="padding: 5px;">
                        </div>
                    @endforeach

                </fieldset>
            </div>

            <div class="piedForm" style="margin-top: 20px;">
                <p>
                    <input id="ok" type="submit" value="Valider" class="btn btn-primary" />
                    <input id="annuler" type="reset" value="Annuler" class="btn btn-default" />
                </p>
            </div>
        </form>
    </div>
@endsection
