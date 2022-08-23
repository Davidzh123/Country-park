<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
$message = "";
$selectoption = $db->prepare('SELECT * FROM options ');
$selectoption ->execute();
$options = $selectoption->fetchAll();
$reqherg =$db ->prepare('SELECT * FROM herbergeent WHERE id =:id');
$reqherg ->execute([
    'id'=>$_POST['idHeberg']
]);
$selectHeberg= $reqherg->fetch();
?>

<?php
if(isset($_SESSION['id'])){
    if(isset($_POST['idHeberg'])){


        if (isset($_POST['send'])) {
            $dateValue1 = $_POST['date1'];
            $dateValue2 = $_POST['date2'];
            $interval = date_diff(date_create($dateValue1), date_create($dateValue2));
            $interval->format('%d');
            if ($dateValue1 > $dateValue2) {
                echo "la date de debut et plus grande que la date de fin";

            } else {
                if ($dateValue1 != $dateValue2) {
                    $prixTotal = $interval->format('%d') * $selectHeberg['prix'];

                    $optionsReserv = '';
                    if (!empty($_POST['options'])) {
                        $optionsReserv = implode(',', $_POST['options']);

                        foreach ($options as $option) {
                            foreach ($_POST['options'] as $optionReserv) {
                                if ($option['id'] == $optionReserv) {
                                    $prixTotal = $prixTotal + $option['prix'];
                                }
                            }
                        }
                    }

                    $insrtDateToPanier = $db->prepare('INSERT INTO commandes (dateDebut, idUser,dateFin, idHeberg,prixTotal,options) VALUES (:dateDebut, :idUser, :dateFin, :idHeberg,:prixTotal,:options)');
                    $isInsert = $insrtDateToPanier->execute([
                        "dateDebut" => $dateValue1,
                        "dateFin" => $dateValue2,
                        "idUser" => $_SESSION['id'],
                        "idHeberg" => $_POST['idHeberg'],
                        "prixTotal" => $prixTotal,
                        "options" => $optionsReserv
                    ]);
                    if ($isInsert) {
                        header('Location: ./commande.php?id=' . $db->lastInsertId());
                    }
                } else {
                    echo "Vos dates sont identiques";
                }
            }
        }

        if(!$selectHeberg['status']){
            header('Location: ./hebergement.php');
        }
    }else{
        header('Location: ./hebergement.php');
    };




}else{
    header('Location: ./register.php');
}

?>
<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/html">
<head>
    <title>Réservation</title>
    <link href="./asset/reservation.css" type="text/css" rel="stylesheet">
    <?php include './structure/head.php';?>
</head>
<body>
<?php
require_once './structure/navbar.php';?>
<h1 class="text-center">Réservation</h1>

<h2>Vous louer <?php echo$selectHeberg['name'];?></h2>

<p>Moyenne : <?php
        $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1) as moyenne FROM avis WHERE idHeberg= :idHeberg');
        $reqMoyenneNote->execute([
                "idHeberg"=>$selectHeberg['id']
        ]);
        $MoyenneNote = $reqMoyenneNote->fetchColumn();
        echo $MoyenneNote;?>
</p>

    <img src="./asset/imgLogmements/<?php echo $selectHeberg['image'];?>" width="300" height="300">


<p>Pour <?php echo$selectHeberg['capacité']." Personne(es)";?> </p>
<p>Le prix de cette location est de <?php echo $selectHeberg['prix']."€ /jours ";?>  </p>

<form action="reservation.php" method="post">
    <input name="idHeberg" value="<?php echo $selectHeberg['id'];?>" hidden>
    <div class="form-group">
        <label for="date1">Date d'arrivé</label>
        <input type="date" id="date1" class="inputDate" name="date1" min="<?php echo date('Y-m-d')?>"  >
    </div>
    <div class="form-group">
        <label for="date2">Date de Fin</label>
        <input type="date" id="date2" class="inputDate" name="date2"  >
    </div>
    <p>Vous réserver <span id="nbDays"></span> jours</p>
    <?php


 foreach ($options as $option):?>
     <label for="check<?php echo $option['name'];?>"><?php echo $option['name'];?></label>
    <input type="checkbox" id="check<?php echo $option['name'];?>" value="<?php echo $option['id'];?>" name="options[]">
    <?php endforeach;?>
<!--        Services compléments :-->
<!--    <label >Pension alimentaire</label>-->
<!--   <input type="checkbox" name="option[]"value="alimentaire" >-->
<!-- <label >manage</label>-->
<!--   <input type="checkbox" name="option[]"  value="menage">-->

<!--    <select>-->
<!--        <option>Sans option</option>-->
<!--        <option name="alimentaire" > Pension Alimentaire</option>-->
<!--        <option name="menage"> Menage</option-->
<!--    </select>-->
    <button type="submit"  class="btn btn-outline-secondary" name="send" id="send">Réserver</button>
</form>

<span id="prixTotal"></span>
<span id="interval"></span>



<!--//            $recupDate = $db->prepare('INSERT INTO commanddes WHERE dateDebut=:dateDebut AND dateFin =:dateFin');-->
<!--//            $recupDate->execute([-->
<!--//                "dateDebut" => $dateValue1,-->
<!--//                "dateFin"=>$dateValue2-->
<!--//            ]);-->
<!--//            $dateUser = $recupDate ->fetch();-->
<!--//            header('Location: ./commande.php');-->
<!--//-->
<!--//-->
<!--//    }else{-->
<!--//        echo "Veuillez saisir une date";-->
<!--//    }-->
<!--//    $status = $selectHeberg['status'];-->
<!--//    $reqStatus = $db ->prepare('UPDATE herbergeent SET status = 1 WHERE status =: status ');-->
<!--//    $reqStatus -> execute([-->
<!--//            "status" => $status-->
<!--//    ]);-->
<?php
$reqAvis = $db->prepare('SELECT * FROM avis WHERE idHeberg = :idHeberg');
$reqAvis ->execute([
        "idHeberg"=>$_POST['idHeberg']
]);
$avis = $reqAvis->fetchAll();

?>
<h2>Les avis</h2>
<?php foreach ($avis as $avi):
$reqUser = $db->prepare('SELECT * FROM users WHERE id=:idUser');
$reqUser ->execute([
        "idUser"=>$avi['idUser']
]);
$user = $reqUser->fetch();

?>
<div class="avis">
    <p class="text-center">Pseudo  : <?php echo $user["pseudo"];?> </p>
    <p class="text-center">message : <?php echo $avi['message'];?></p>
    <p class="text-center">note : <?php echo $avi['note'];?>/5</p>
</div>


<?php endforeach;?>


<script>
    const elements = document.querySelectorAll('.inputDate');

    elements.forEach(element => {
        element.addEventListner('change', (e) => {
            var interval = document.getElementById('date1').value - document.getElementById('date1').value;
            document.getElementById('interval').innerText = interval;
            document.getElementById('prixTotal').innerText = <?php echo $selectHeberg['prix'] ?> * interval;
        });
    });
</script>
<?php include './structure/footer.php' ?>
</body>
</html>