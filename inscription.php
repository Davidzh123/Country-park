<?php
require_once './structure/head.php';
require_once './structure/navbar.php';
?>
    <!DOCTYPE html>
    <html lang="fr">
<head>
    <title>Inscription</title>
    <?php require_once './structure/head.php';?>
</head>
<body>
<form action="inscription.php" method="post">
    <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" name="pseudo" aria-describedby="emailHelp">

    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Prenom</label>
        <input type="text" class="form-control" id="prenom" name="prenom">
    </div>

    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Email</label>
        <input type="email" class="form-control" id="mail" name="mail">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="mdp" name="mdp">
    </div>
    <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Confirmer le mot de passe </label>
        <input type="password" class="form-control" id="cmdp" name="cmdp">
    </div>
    <button type="submit" class="btn btn-primary" name="send" id="send">s'enrengister</button>

</form>
<?php require_once './structure/footer.php';?>
</body>
</html>

<?php
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();

if(isset($_POST['send'])) {

    extract($_POST);
    if (!empty($pseudo) && !empty($prenom) && !empty($nom) && !empty($mail) && !empty($mdp) && !empty($cmdp)) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $mail = htmlspecialchars($_POST['mail']);
        $mdp = $_POST['mdp'];
        $cmdp = $_POST['cmdp'];

        if ($mdp == $cmdp) {
            $options = [
                'cost' => 12,
            ];

            $hashpass = password_hash($mdp, PASSWORD_BCRYPT, $options);


            if (strlen($pseudo) <= 255) {
                $reqmail = $db->prepare('SELECT * FROM users WHERE mail = ?');
                $reqmail->execute(array($mail));
                $mailexist = $reqmail->fetch();
                if (!$mailexist) {
                    $reqinsrt = $db->prepare('INSERT INTO users (pseudo,prenom,nom,mail,mdp) VALUES (?,?,?,?,?)');
                    if ($reqinsrt->execute(array($pseudo, $prenom, $nom, $mail, $hashpass))) {

                        echo 'votre compte a été créer ';
                    } else {
                        echo " Erreur de création de compte ";
                    }
                } else {
                    echo "Addresse mail déjà utilisée ";

                }
            }
        } else {
            echo "Vos mot de passe sont different";
        }
    }

//}
//function smtpmailer($to, $from, $from_name, $subject, $body)
//{
//    $mail = new PHPMailer();
//    $mail->IsSMTP();
//    $mail->SMTPAuth = true;
//
//    $mail->SMTPSecure = 'ssl';
//    $mail->Host = 'smtp.gmail.com';
//    $mail->Port = 465;
//    $mail->Username = 'neo.dzh13@gmail.com';
//    $mail->Password = 'ENTER YOUR EMAIL PASSWORD';
//
//    //   $path = 'reseller.pdf';
//    //   $mail->AddAttachment($path);
//
//    $mail->IsHTML(true);
//    $mail->From = "neo.dzh13@gmail.com";
//    $mail->FromName = $from_name;
//    $mail->Sender = $from;
//    $mail->AddReplyTo($from, $from_name);
//    $mail->Subject = $subject;
//    $mail->Body = $body;
//    $mail->AddAddress($to);
//    if(!$mail->Send())
//    {
//        $error ="Please try Later, Error Occured while Processing...";
//        return $error;
//    }
//    else
//    {
//        $error = "Thanks You !! Your email is sent.";
//        return $error;
//    }
//}
//
//
//    $to = $mail;
//    $from = 'neo.dzh13@gmail.com';
//    $name = 'Country Park';
//    $subj = 'Confirmation de compte';
//    $msg = 'http://localhost/Country%20park/inscription_connexion/verif.php?id='.$_SESSION['id'];
//
//    $error= smtpmailer($to, $from, $name, $subj, $msg);
}