<?php
session_start();
require_once'./functions/getDataBase.php';
$db = getDatabaseConnection();
$message ="";
//verifi si user est  connecter
if (!isset($_SESSION['id'])) {
    header('Location: ./register.php');
}
//verifie le id passer en paramètre
if(isset($_GET['id'])){
//    requete avoir la table commandes
    $reqCommande = $db->prepare('SELECT * FROM commandes WHERE id = :id');
    $reqCommande-> execute([
            "id" => $_GET['id']
    ]);
    $Commandes = $reqCommande->fetch();

    $dateDebut = date_create($Commandes['dateDebut']);
    $dateFin = date_create($Commandes['dateFin']);
    $interval = date_diff($dateDebut,$dateFin);
    $dateDebut = date_format($dateDebut, 'd/m/Y ');
    $dateFin = date_format($dateFin, 'd/m/Y ');

//    requete pour avoir les idHerbg ou id => idHeberg
    $reqLogement = $db->prepare('SELECT * FROM herbergeent WHERE id = :id');
    $reqLogement->execute([
       "id"=>$Commandes['idHeberg']
    ]);
    $logement = $reqLogement->fetch();

    $optionsCommande = [];
    if(!empty($Commandes['options'])) {
        $idOptions = explode(',', $Commandes['options']);
        foreach($idOptions as $idOption) {
            $reqOption = $db->prepare('SELECT * FROM options WHERE id=:id');
            $reqOption->execute([
                    "id" => $idOption
            ]);
            $optionsCommande[] = $reqOption->fetch();
        }
    }


    if($_SESSION['id'] != $Commandes['idUser']){
        header('Location: ./register.php');
    }
//    s'il n'est pas connecter alors redige vers la connexion
} else {
    header('Location: ./register.php');
}
?>
<!DOCTYPE html>
<html>
<head>
<?php
include './structure/head.php'; ?>
    <title>commande</title>
</head>
<body>
<?php require_once './structure/navbar.php'?>
<div class="center">
    <h1>Votre commande : </h1>
    <h2><?php echo $logement['name'];?></h2>
    <p>Moyenne : <?php
        $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1) as moyenne FROM avis WHERE idHeberg= :idHeberg');
        $reqMoyenneNote->execute([
                "idHeberg"=>$logement['id']
        ]);
        $MoyenneNote = $reqMoyenneNote->fetchColumn();
        echo $MoyenneNote;
        $reqType =$db ->prepare('SELECT name FROM type WHERE id = :id');
        $reqType ->execute([
                "id" => $logement['idType']
        ]);
        $typeName = $reqType->fetchColumn();


        ?> /5</p>
    <p>Le type de logement : <?php echo $typeName;?></p>
    <p></p>
    <p> Capacité : <?php echo $logement['capacité'];?> Personne(es)  </p>
    <img src="./asset/imgLogmements/<?php echo $logement['image'];?>" height="150" width="150">
    <p><?php echo $logement['prix']."€";?> /jours </p>
    <p>Vous arrivé le : <?php echo $dateDebut;?> </p>
    <p>Vous parter  le : <?php echo  $dateFin;?> </p>
    <p>Vous réserver pour <?php echo $interval->format('%d');?> jours </p>
    <?php foreach ($optionsCommande as $optionCommande):?>
    <p>options : <?php echo $optionCommande['name'];?></p>
    <p>prix : <?php echo $optionCommande['prix'];?></p>
    <?php endforeach;?>
    <p>Le total de votre séjour : <?php echo $Commandes['prixTotal']?> €</p>
</div>
<!--inserer une note et message -->
<?php
if(isset($_POST['avis'])){
   $insrtAvis = $db->prepare('INSERT INTO avis (idUser,message,note, idHeberg) VALUES (:idUser,:message,:note, :idHeberg)');
   $insrtAvis ->execute([
       "idUser" => $_SESSION['id'],
       "message" => $_POST['message'],
       "note" => $_POST['note'],
       "idHeberg" => $Commandes['idHeberg']
   ]);
   $modifierCommande = $db->prepare('UPDATE commandes SET idAvis = ' . $db->lastInsertId() . ' WHERE idUser=:idUser AND idHeberg = :idHeberg');
   $modifierCommande ->execute([
           "idUser"=>$_SESSION['id'],
       "idHeberg"=>$Commandes['idHeberg']
   ]);
   $UpdateCommande = $modifierCommande->fetch();
   echo "Votre avis à été enrengistré";
   header('Location: ./profil.php');
}
?>

<form action="commande.php?id=<?php echo $_GET['id'];?>" method="post">

    <h2>Laissez votre avis </h2>
    <div class="mb-3">
        <label>Note :</label>
        <input type="number"class="form-label" name="note" max="5" min="0">
    </div>

    <input name="idHeberg" value="<?php echo $_GET['id'];?>" hidden>
    <div class="form-floating">
        <textarea name="message" class="form-control" placeholder = "Votre message"></textarea>
    </div>

    <input type="submit" class="btn btn-primary" value="Envoyer" name="avis">
</form>
<?php require_once './structure/footer.php'?>
</body>
</html>
