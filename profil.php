<!DOCTYPE html>
<?php
session_start();
require_once './structure/head.php';
require_once './structure/navbar.php';
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();

if (isset($_SESSION['id'])) {
    $getid = intval($_SESSION['id']);
} else{
    header('Location: ./register.php');
}

$requser = $db ->prepare('SELECT * FROM users WHERE id =?');
$requser -> execute(array($getid));
$userinfo = $requser->fetch();

//recupere les commandes
$reqCommandes = $db->prepare('SELECT * FROM commandes WHERE idUser=:idUser');
$reqCommandes->execute([
        "idUser"=>$_SESSION['id']
]);
$commandes = $reqCommandes->fetchAll();


?>
<link href="./asset/profil.css" rel="stylesheet">
<h2 class="text-center">Profil de <?php echo $userinfo ['prenom'];?></h2>
<div class="profil">
<?php

    if(empty($userinfo['avatar'])){
        ?>
      <img src="./asset/img/utilisateur.png" width="300" HEIGHT="300">
            <?php
    }else{
        ?>
            <img src="./asset/img/users/avatar/<?php echo$userinfo['avatar']?>">
    <?php } ?>
    <p>Pseudo = <?php echo $userinfo['pseudo'];?></p>
    <p>Mail = <?php echo $userinfo['mail'];?></p>
</div>
<hr>
<img src="asset/img/118089.png" height="60" width="60">
<h2>Vos commandes : </h2>
<div class="container">
<?php
foreach ($commandes as $commande):
//recupere les hebergements
$reqHeberg = $db ->prepare('SELECT * FROM herbergeent WHERE id=:id');
$reqHeberg ->execute([
    'id'=>  $commande['idHeberg']
]);
$hebergement = $reqHeberg->fetch();
$reqType =$db ->prepare('SELECT name FROM type WHERE id = :id');
$reqType ->execute([
        "id" => $hebergement['idType']
]);
$type = $reqType->fetchColumn();


?>
    <div class="commande">
<!--        <p>Le type : --><?php //echo $hebergType;?><!--</p>-->
<!--        <p>Nom du logement : --><?php //echo $hebergements['name'];?><!--</p>-->
<!--        <img src="./asset/imgLogmements/--><?php //echo $hebergements['image'];?><!--" height="150px" width="150">-->
<!--        <p>--><?php //echo $hebergements['prix']."€ /jours";?><!--</p>-->
<!--        <a href="./commande.php?id=--><?php //echo $commande['id']; ?><!--">Voir la commande</a>-->



        <div class="card" style="width: 18rem;">
            <img src="./asset/imgLogmements/<?php echo $hebergement['image'];?>" height="300px" width="100%">
            <div class="card-body">
                <h5 class="card-title text-center"><a href="?id=<?php echo $hebergement['id'];?>"><?php echo $hebergement['name'];?></a></h5>
                <p class="text-center"><?php echo $type;?></p>
                <p class="text-center"><?php echo $hebergement['capacité'];?> personne(es)</p>
                <p class="text-center"> <?php echo $hebergement['prix']."€";?> /jours</p>
                <p class="text-center">Moyenne Note : <?php
                    $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1) as moyenne FROM avis WHERE idHeberg= :idHeberg');
                    $reqMoyenneNote->execute([
                        "idHeberg"=>$hebergement['id']
                    ]);
                    $MoyenneNote = $reqMoyenneNote->fetchColumn();
                    echo $MoyenneNote;




                    ?> /5</p>
                <form action="reservation.php" method="post">
                    <input name="idHeberg" value="<?php echo $hebergement['id'];?>" hidden>
                    <p class="text-center">  <a  href="./commande.php?id=<?php echo $commande['id']; ?>">Voir la commande</a></p>

            </div>
        </div>


    </div>
<?php endforeach; ?>

<?php
if(isset($_SESSION['id'])&& $userinfo['id'] == $_SESSION['id']){
    ?>
</div>
    <a href="./editProfil.php">Modifier votre Profil</a>    
    <?php

}
?>
<?php include './structure/footer.php';?>
</html>
