<?php
session_start();
require_once './structure/head.php';
require_once './structure/navbar.php';
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
if(isset($_GET['id'])&& $_SESSION['id'] > 0){
    $getid = intval($_GET['id']);
    $requser = $db ->prepare('SELECT * FROM  WHERE id =?');
    $requser -> execute(array($getid));
    $userinfo = $requser->fetch();
}
?>
<div class="center">
    <h2>Profil de <?php echo $userinfo['pseudo'];?></h2>
</div>
<?php

if(empty($userinfo['avatar'])){
    ?>
    <img src="../img/utilisateur.png" width="300" HEIGHT="300">
    <?php
}else{}
?>
<img src="../img/users/avatar"><?php echo$userinfo['avatar']?>
}
Pseudo = <?php echo $userinfo['pseudo'];?>
Mail = <?php echo $userinfo['mail'];?>
<?php
if(isset($_SESSION['id'])&& $userinfo['id'] == $_SESSION['id']){
    ?>
    <a href="#">Voir mes commandes </a>
    <a href="./editProfil.php">Modifier votre Profil</a>
    <a href="./deconnexion.php">Deconnexion</a>
    <?php

}
?>
