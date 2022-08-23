<?php
session_start();
//require_once './gestionErreur.php';
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
//recup les users
$reqUser =$db ->prepare('SELECT * FROM users');
$reqUser->execute();
$users = $reqUser->fetchAll();
//rexup les logements
$reqLogement =$db->prepare('SELECT * FROM herbergeent');
$reqLogement->execute();
$logements = $reqLogement->fetchAll();
//recup les avis
$reqAvis  = $db->prepare('SELECT * FROM avis ');
$reqAvis->execute();
$avis = $reqAvis->fetchAll();
$reqOption  = $db->prepare('SELECT * FROM options ');
$reqOption->execute();
$options = $reqOption->fetchAll();
$reqType = $db->prepare('SELECT * FROM type ');
$reqType ->execute();
$type = $reqType->fetchAll();

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once './structure/head.php'?>
    <title>Admin</title>
</head>

<body>
<?php require_once './structure/navbar.php'?>
<h1 class="text-center">Espace Admin</h1>
<h2>Liste des membres : </h2>
<table class="table">
    <thead>
    <tr>
        <th scope="col">pseudo</th>
        <th>ADMIN</th>
        <th>Modifier</th>
        <th>Supprimer</th>

    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
        <td> <?php echo $user['pseudo'];?></td>
        <td> <?php echo $user['admin'];?>
            <?php
            if($user['admin']):?>
                <a href="#">Dégrader admin</a><?php else: ?>
            <a href="./promuAdmin.php?id=<?php echo $user['id'];?>">Promouvoir Admin</a><?php endif;?>
        </td>
        <td>  <a href="./modifier.php?id=<?php echo $user['id'];?>">Modifier</a></td>
        <td><a href="./supprimer.php?id=<?php echo $user['id'];?>">Supprimer</a></td>
    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<a href="ajouterLogement.php">+ Ajouter un Logement</a>
<h2>Liste des Logements :</h2>
<table>
    <thead>
    <tr>
        <th >Nom</th>
        <th>Type</th>
        <th>Prix</th>
        <th>Capacité</th>
        <th>Photo</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($logements as $logement):?>
    <tr>
        <td><?php echo $logement['name'];?></td>
        <td><?php echo $logement['idType'];?></td>
        <td><?php echo $logement['prix']."€";?></td>
        <td><?php echo $logement['capacité'];?></td>
        <td><img src="./asset/imgLogmements/<?php echo $logement['image'];?>" height="150px" width="150"></td>
        <td><a href="./modifierLogement.php?id=<?php echo $logement['id'];?>" >modifier</a> </td>
        <td><a href="./supprimerLogements.php?id=<?php echo $logement['id'];?>" >Supprimer</a> </td>

    </tr>
    <?php endforeach;?>
    </tbody>
</table>
<a href="./ajouterOption.php">+ Ajouter un option</a>
<a href="./ajouterTypeLogement.php">+ Ajouter un type de logement</a>

<h2>Les Avis :</h2>
<?php foreach ($avis as $avi):?>
<div>
    <?php
    $reqSelectUser = $db ->prepare('SELECT pseudo FROM users WHERE id=:idUser');
    $reqSelectUser->execute([
        "idUser"=>$avi["idUser"]
    ]);
    $MessagePseudo = $reqSelectUser->fetchColumn();

    ?>
    <p>Message de <?php echo $MessagePseudo;?>   :  <?php echo $avi['message'];?> et la note : <?php echo $avi['note'];?></p>
    <a href="./supprimeravis.php?id=<?php echo $avi['id'];?>" >Supprimer</a>
</div>
<?php endforeach;?>
<?php require_once './structure/footer.php'?>
</body>
</html>