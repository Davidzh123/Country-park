<?php
session_start();
require_once './structure/head.php';
require_once './structure/navbar.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Connexion</title>
    <?php require_once './structure/head.php';?>
</head>
<body>
<form action="register.php " method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Mail</label>
        <input type="text" class="form-control" id="mailconnect" name="mailconnect" aria-describedby="emailHelp">

    </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Mot de passe </label>
                <input type="password" class="form-control" id="mdpconnect" name="mdpconnect" aria-describedby="emailHelp">
            </div>
    <button type="submit" class="btn btn-primary" name="submit" id="submit">se connecter</button>
</form>
<?php
include './functions/getDataBase.php';
$db = getDatabaseConnection();
$message = "";

if(isset($_POST['submit'])) {
    $mailconnect = htmlspecialchars($_POST['mailconnect']);
    $mdpconnect = $_POST['mdpconnect'];

        if(!empty($mailconnect)&& !empty($mdpconnect)){
            $requser = $db->prepare('SELECT mdp FROM `users`  WHERE mail =?');
            $requser -> execute(array($mailconnect));
            $verificationMdp = $requser->fetchColumn();
                if(password_verify($mdpconnect,$verificationMdp)){
                    $requser = $db -> prepare('SELECT *FROM users WHERE mail =?');
                    $requser ->execute(array($mailconnect));
                    $userinfo = $requser-> fetch();
                    $_SESSION['id'] = $userinfo['id'];
                    $_SESSION['pseudo'] = $userinfo['pseudo'];
                    $_SESSION['mail'] = $userinfo['mail'];
                    $_SESSION['admin'] = $userinfo['admin'];
                    header("Location: profil.php?");
                    exit();
                }else{
                    $message = "Mauvais id ou mot de passe ";
                }
        }
}
?>
<div class="box">
<p>
    <?php if(isset($message)){
        echo $message;
    }?>
</p>
</div>
    <?php require_once './structure/footer.php';?>
</body>
</html>

