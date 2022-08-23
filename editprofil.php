<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
if(isset($_SESSION['id'])){
    $requser =$db->prepare('SELECT * FROM users WHERE id =?');
    $requser-> execute(array($_SESSION['id']));
    $user = $requser->fetch();

        if(isset($_POST['edit'])){
            $newpseudo = htmlspecialchars($_POST['newpseudo']);
            $insertpseudo = $db ->prepare('UPDATE users SET pseudo =? WHERE id =?');
            $insertpseudo->execute(array($newpseudo,$_SESSION['id']));
            header('Location: profil.php?id=' . $_SESSION['id']);
            exit();
        }
        if(isset($_POST['oldmdp'])&&!empty($_POST['oldmdp'])){
            $reqnewmdp= $db->prepare('SELECT mdp FROM users pseudo = ?');
            $reqnewmdp->execute(array($user['pseudo']));
            $verificationMdp = $reqnewmdp->fetchColumn();
            if(password_verify($_POST['oldmdp'],$verificationMdp)){
                if($_POST['newmdp']== $_POST['newmdp2']){
                    $options =[
                        'coast' =>12,
                    ];
                    $hashpass = password_hash($_POST['newmdp'],PASSWORD_BCRYPT,$options);
                    $reqnewmdp = $db->prepare('UPDATE users SET mdp =? WHERE pseudo=?');
                        if($reqnewmdp->execute(array($hashpass,$user['pseudo']))){
                            echo "Ca été modifié";
                        } else {
                            echo "Ca n'a pas été modifié";
                        }
                } else {
                    echo "Les mot de passe ne sont pas identiques";
                }
            } else {
                echo "Le mot de passe n'a pas été à jour";
            }
        } else {
            echo "L'ancien mot de passe n'est pas bon";
        }
}
if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
    $tailleMax = 5000000;
    $extensionValides = array('jpg', 'jpeg', 'gif', 'png');
    if ($_FILES['avatar']['size'] <= $tailleMax) {
        $extensionUpload = strtolower(substr(strrchr($_FILES['avatar']['name'], '.'), 1));
        if (in_array($extensionUpload, $extensionValides)) {
            $chemin = "./asset/img/users/avatar/" . $_SESSION['id'] . "." . $extensionUpload;
            $resultat = move_uploaded_file($_FILES['avatar']['tmp_name'], $chemin);
            if ($resultat) {
                $updateavatar = $db->prepare('UPDATE users SET avatar = :avatar WHERE id = :id ');
                $updateavatar->execute(array(
                    'avatar' => $_SESSION['id'] . "." . $extensionUpload,
                    'id' => $_SESSION['id']

                ));
                header('Location: profil.php');
                exit();
            }
        } else {
            $msg = "VOtre photo doit etre au format jpd, ";

            echo $msg;
        }
    } else {
        $msg = "Votre photo de profil ne  doit pas  ddépasser 5MO";
        echo $msg;
    }


}

?>
<!DOCTYPE html>
<html>
<head>
    <title>
        mmodifier profil
    </title>
    <?php require_once './structure/head.php';?>
</head>
<body>
<div class="center">
    <h2>Modifier votre profil <?php echo $user['pseudo'];?></h2>
    <form action="editprofil.php " method="post" enctype="multipart/form-data">
        <input type="text" name="newpseudo" placeholder="Nouveau pseudo">
        <input type="password" name="oldmdp" placeholder="Votre mot de passe ">
        <input type="password" name="newmdp" placeholder="Votre nouveau mot de passe ">
        <input type="password" name="newmdp2" placeholder="Confirmer votre nouveau mot de passe ">
        <input type="file" name="avatar" placeholder="Ajouter une photo">
        <input type="submit" name="edit" value="Modifier">
    </form>
</div>
</body>
</html>


