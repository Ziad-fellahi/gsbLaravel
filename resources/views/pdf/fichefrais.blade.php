<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche de frais</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #eee; }
        h1 { color: #337ab7; }
    </style>
</head>
<body>
<h1>Fiche de frais GSB</h1>
<p><strong>Visiteur :</strong> {{ $visiteur['nom'] }} {{ $visiteur['prenom'] }}</p>
<p><strong>Mois :</strong> {{ $numMois }} / {{ $numAnnee }}</p>

<div style="margin-top: 20px; border: 1px solid #ccc; padding: 15px;">
    <p>
        Etat : <strong>{{ $libEtat }}</strong> depuis le {{ $dateModif }} <br>
        Montant validé : <strong>{{ $montantValide }} €</strong>
    </p>
</div>

<h3>Eléments forfaitisés</h3>
<table>
    <tr>
        @foreach($lesFraisForfait as $unFraisForfait)
            <th> {{$unFraisForfait['libelle']}} </th>
        @endforeach
    </tr>
    <tr>
        @foreach($lesFraisForfait as $unFraisForfait)
            <td>{{ $unFraisForfait['quantite'] }}</td>
        @endforeach
    </tr>
</table>
</body>
</html>
