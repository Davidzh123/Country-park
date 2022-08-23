<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once './structure/head.php'; ?>
    <link href="./asset/index.css" rel="stylesheet">
    <title>Country Park</title>
</head>

<body>
<?php
require_once './structure/navbar.php';
require_once './structure/caroussel.php';

$reqLogement = $db->prepare('SELECT * FROM herbergeent');
$reqLogement->execute();
$Logements = $reqLogement->fetchAll();

if (isset($_GET['id'])):
    $req = $db->prepare('SELECT * FROM `herbergeent` WHERE id = :id');
    $req->execute(['id' => $_GET['id']]);
    $selectHeberg = $req->fetch();
?>
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $selectHeberg['name'] ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Moyenne Note :
                    <?php
                    $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1) as moyenne FROM avis WHERE idHeberg= :idHeberg');
                    $reqMoyenneNote->execute([
                        "idHeberg"=>$selectHeberg['id']
                    ]);
                    $MoyenneNote = $reqMoyenneNote->fetchColumn();
                    echo $MoyenneNote;
                    ?> /5
                </p>
                <?php echo $selectHeberg['prix']."€ / jours";?>
                <img src="./asset/imgLogmements/<?php echo $selectHeberg['image'];?>" height="150px" width="150">
                <h2 class="text-center">Les avis</h2>
                <?php
                $reqAvis = $db->prepare('SELECT * FROM avis WHERE idHeberg = :idHeberg');
                $reqAvis ->execute([
                        "idHeberg"=>$_GET['id']
                ]);
                $avis = $reqAvis->fetchAll();
                foreach ($avis as $avi):
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
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var myModal = new bootstrap.Modal(document.getElementById('myModal'), {})
    myModal.toggle()
</script>
<?php endif; ?>
<h1 class="text-center">Locations Hébergements</h1>

<h2 class="produits">Nos Produits : </h2>

<div class="logements">
    <?php foreach ($Logements as $logement): ?>
    <div class="scroll">
        <img src="./asset/imgLogmements/<?php echo $logement['image'];?>" height="150px" width="150">
        <a href="?id=<?php echo $logement['id'];?>"><?php echo $logement['name'];?></a>
        <p><?php echo $logement['prix'];?> €/ jours</p>
        <p>
            Moyenne :
            <?php
            $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1)  as moyenne FROM avis WHERE idHeberg= :idHeberg');
            $reqMoyenneNote->execute([
                "idHeberg"=>$logement['id']
            ]);
            $MoyenneNote = $reqMoyenneNote->fetchColumn();
            echo $MoyenneNote;
            ?> /5
        </p>
    </div>
<?php endforeach ;?>
</div>

<?php require_once './structure/footer.php';?>
</body>
</html>

