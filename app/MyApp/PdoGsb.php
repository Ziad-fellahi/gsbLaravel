<?php
namespace App\MyApp;

use PDO;
use Illuminate\Support\Facades\Config;

class PdoGsb
{
    private $pdo;

    public function __construct()
    {
        // Récupération des informations de connexion
        $serveur = 'mysql:host=' . Config::get('database.connections.mysql.host');
        $bdd = 'dbname=' . Config::get('database.connections.mysql.database');
        $user = Config::get('database.connections.mysql.username');
        $mdp = Config::get('database.connections.mysql.password');

        // Création de l'instance PDO
        $this->pdo = new PDO($serveur . ';' . $bdd, $user, $mdp);
        $this->pdo->query("SET CHARACTER SET utf8");
    }

    // ------------------- VISITEUR -------------------

    public function getInfosVisiteur($login, $mdp)
    {
        $req = "SELECT id, nom, prenom FROM visiteur WHERE login = :login AND mdp = :mdp";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['login' => $login, 'mdp' => $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $ok = false;
        $req = "SELECT count(*) as nblignesfrais FROM fichefrais
                WHERE fichefrais.mois = :mois AND fichefrais.idVisiteur = :idVisiteur";

        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['mois' => $mois, 'idVisiteur' => $idVisiteur]);
        $laLigne = $stmt->fetch();

        if ($laLigne && $laLigne['nblignesfrais'] == 0) {
            $ok = true;
        }
        return $ok;
    }

    public function getLesMoisDisponibles($idVisiteur)
    {
        $req = "SELECT fichefrais.mois as mois FROM fichefrais
                WHERE fichefrais.idVisiteur = :idVisiteur
                ORDER BY fichefrais.mois desc";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['idVisiteur' => $idVisiteur]);
        $lesMois = array();
        while ($laLigne = $stmt->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois["$mois"] = array(
                "mois" => "$mois",
                "numAnnee" => "$numAnnee",
                "numMois" => "$numMois"
            );
        }
        return $lesMois;
    }

    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    {
        $req = "INSERT INTO fichefrais(idVisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat)
                VALUES(:idVisiteur,:mois,0,0,now(),'CR')";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['idVisiteur' => $idVisiteur, 'mois' => $mois]);

        $reqIds = "SELECT id FROM fraisforfait";
        $stmtIds = $this->pdo->query($reqIds);
        $lesIdFrais = $stmtIds->fetchAll();

        foreach ($lesIdFrais as $uneLigneIdFrais) {
            $idFrais = $uneLigneIdFrais['id'];
            $req = "INSERT INTO lignefraisforfait(idVisiteur,mois,idFraisForfait,quantite)
                    VALUES(:idVisiteur,:mois,:idFrais,0)";
            $stmt = $this->pdo->prepare($req);
            $stmt->execute(['idVisiteur' => $idVisiteur, 'mois' => $mois, 'idFrais' => $idFrais]);
        }
    }

    public function getLesFraisForfait($idVisiteur, $mois)
    {
        $req = "SELECT fraisforfait.id as idfrais, fraisforfait.libelle as libelle,
                lignefraisforfait.quantite as quantite
                FROM lignefraisforfait
                INNER JOIN fraisforfait
                ON fraisforfait.id = lignefraisforfait.idFraisForfait
                WHERE lignefraisforfait.idVisiteur = :idVisiteur AND lignefraisforfait.mois = :mois
                ORDER BY lignefraisforfait.idFraisForfait";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['idVisiteur' => $idVisiteur, 'mois' => $mois]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $req = "SELECT fichefrais.idEtat as idEtat, fichefrais.dateModif as dateModif, fichefrais.nbJustificatifs as nbJustificatifs,
                fichefrais.montantValide as montantValide, etat.libelle as libEtat
                FROM fichefrais
                INNER JOIN etat ON fichefrais.idEtat = etat.id
                WHERE fichefrais.idVisiteur = :idVisiteur AND fichefrais.mois = :mois";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['idVisiteur' => $idVisiteur, 'mois' => $mois]);
        return $stmt->fetch();
    }

    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $req = "UPDATE lignefraisforfait SET quantite = :qte
                    WHERE idVisiteur = :idVisiteur AND mois = :mois AND idFraisForfait = :idFrais";
            $stmt = $this->pdo->prepare($req);
            $stmt->execute([
                'qte' => $qte,
                'idVisiteur' => $idVisiteur,
                'mois' => $mois,
                'idFrais' => $unIdFrais
            ]);
        }
    }

    // ------------------- COMPTABLE -------------------

    public function getToutesLesFiches()
    {
        // Récupère les infos complètes (avec nom/prénom) pour l'affichage comptable
        $req = "SELECT f.idVisiteur, v.nom, v.prenom, f.mois, f.nbJustificatifs, f.montantValide, e.libelle as etat
                FROM fichefrais f
                INNER JOIN etat e ON f.idEtat = e.id
                INNER JOIN visiteur v ON f.idVisiteur = v.id
                ORDER BY f.mois DESC";
        $stmt = $this->pdo->query($req);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function validerFiche($idVisiteur, $mois)
    {
        $req = "UPDATE fichefrais SET idEtat = 'VA', dateModif = NOW()
                WHERE idVisiteur = :idVisiteur AND mois = :mois";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['idVisiteur' => $idVisiteur, 'mois' => $mois]);
    }

    public function getInfosComptable($login, $mdp)
    {
        $req = "SELECT id, nom, prenom FROM comptable WHERE login = :login AND mdp = :mdp";
        $stmt = $this->pdo->prepare($req);
        $stmt->execute(['login' => $login, 'mdp' => $mdp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
