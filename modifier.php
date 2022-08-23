<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>

<?php
require_once './structure/head.php';
require_once './structure/navbar.php';
require_once './structure/caroussel.php';
if(isset($_SESSION['id']) && isset($_SESSION['admin'])){
    if(isset($_POST['edit'])){
        $reqPseudoExist = $db ->prepare('SELECT pseudo FROM users where pseudo=:pseudo AND id=:id');
        $reqPseudoExist->execute([
            "pseudo" =>$_POST['pseudo'],
            "id"=>$_SESSION['id']
        ]);
        $pseudoExist = $reqPseudoExist->rowCount();
        $reqMailExist = $db ->prepare('SELECT mail FROM users where mail=:mail AND id=:id');
        $reqMailExist->execute([
            "mail" =>$_POST['mail'],
            "id"=>$_SESSION['id']
        ]);
        $MailExist = $reqMailExist->rowCount();
        if(!$pseudoExist AND !$MailExist){
            $modifierUser = $db->prepare('UPDATE users SET (pseudo,mail) VALUES (:pseudo,:mail) WHERE id = :id');
            $modifierUser->execute([
                "pseudo"=>$_POST['pseudo'],
                "mail"=>$_POST['mail'],
                "id"=>$_SESSION['id']
            ]);
            header('Location: ./admin.php');
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier Profil</title>
    <?php require_once './structure/head.php';?>
</head>
<body>
<form action="modifier.php" method="post">
    <input type="text" name="pseudo" placeholder="" required>
    <input type="mail" name="mail" placeholder="Email" required>
    <input type="submit" name="send" value="Modifier">
</form>
<?php
require_once './structure/footer.php';
?>
</body>
</html>
