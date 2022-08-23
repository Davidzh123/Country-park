<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>


<?php include './structure/head.php';?>
<link href="./asset/hebergement.css" rel="stylesheet">
<body><?php
require_once './structure/navbar.php';
$where ="";

if(isset($_POST['envoi'])){
    if(isset($_POST['filtrer']) && $_POST['filtrer'] != ''){
        $where = " WHERE name LIKE  '%" . $_POST['filtrer'] . "%'";
    }
    if(isset($_POST['select']) && $_POST['select'] != ''){
        if ($where) {
            $where .= " AND idType = '" . $_POST['select'] . "'";
        } else {
            $where = " WHERE idType = '" . $_POST['select'] . "'";
        }
    }
}
$req =$db->prepare('SELECT * FROM `herbergeent`' . $where);
$req ->execute();
$hebergements  = $req->fetchAll();

$reqType =$db ->prepare('SELECT * FROM type ');
$reqType ->execute();
$types = $reqType->fetchAll();

if (isset($_GET["id"])):
    $req = $db->prepare('SELECT * FROM `herbergeent` WHERE id = :id');
    $req->execute(['id' => $_GET['id']]);
    $selectHeberg = $req->fetch();
//
//    $reqAllAvis = $db->prepare('SELECT * FROM avis ');
//    $reqAllAvis->execute();
//    $avis = $reqAllAvis->fetchAll();
//    $reqMoyenne = $db->prepare('SELECT  ROUND(AVG(note)) FROM avis WHERE idHeberg=:idHeberg ');
//    $reqMoyenne->execute([
//        "idHeberg" => $avis['idHeberg*']
////    ]);
//    $MoyenneNote = $reqMoyenne->fetchColumn();

    ?>
<!<!DOCTYPE html>
<html>
<body>
<head>
    <title>
        Hébergements
    </title>
    <link href="./asset/hebergement.css" type="text/css" rel="stylesheet">
</head>

    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $selectHeberg['name'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <p>Moyenne Note : <?php
                        $reqMoyenneNote = $db ->prepare('SELECT ROUND(AVG(note),1) as moyenne FROM avis WHERE idHeberg= :idHeberg');
                        $reqMoyenneNote->execute([
                            "idHeberg"=>$selectHeberg['id']
                        ]);
                        $MoyenneNote = $reqMoyenneNote->fetchColumn();
                        echo $MoyenneNote;




                        ?> /5</p>
                    <?php echo $selectHeberg['prix']."€ / jours";?>
                    <img src="./asset/imgLogmements/<?php echo $selectHeberg['image'];?>" height="150px" width="150">
                </div>


                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var myModal = new bootstrap.Modal(document.getElementById('myModal'), {})
        myModal.toggle()
    </script>
<?php endif; ?>

<form action="hebergement.php"method="post">
    Search : <input type="text" name="filtrer" value="<?php if(isset($_POST['filtrer'])) echo $_POST['filtrer']; ?>">

    <select name="select">
        <option value=""<?php if(!isset($_POST['select'])) echo " selected" ?>>
            Tous
        </option>
        <?php foreach ($types as $type):?>
            <option value="<?php echo $type['id'];?>"<?php if(isset($_POST['select']) && $_POST['select'] == $type['id']) echo " selected" ?>>
                <?php echo $type['name'];?>
            </option>
        <?php endforeach;?>
    </select>
    <input type="submit" class="btn btn-outline-secondary"   name="envoi" value="Filtrer">
</form>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Type</th>
                <th>Prix</th>
                <th>Capacité</th>
                <th>Photo</th>
            </tr>
        </thead>
        <tbody id="table-body">
            <?php foreach ($hebergements as $hebergement):
                $hebergType = '';
                foreach ($types as $type) {
                    if ($type['id'] == $hebergement['idType']) {
                        $hebergType = $type['name'];
                    }
                }
            ?>
                <tr>
                    <td><a href="?id=<?php echo $hebergement['id'];?>"><?php echo $hebergement['name'];?></a></td>
                    <td><?php echo $hebergType;?></td>
                    <td><?php echo $hebergement['prix']."€";?></td>
                    <td><?php echo $hebergement['capacité'];?></td>
                    <td><img src="./asset/imgLogmements/<?php echo $hebergement['image'];?>" height="150px" width="150"></td>
                    <td><form action="reservation.php" method="post">
                            <input name="idHeberg" value="<?php echo $hebergement['id'];?>" hidden>
                            <button  class="btn btn-outline-secondary" type="submit" name="submit" <?php if (!$hebergement['status']) echo 'disabled'; ?>>Réserver</button>
                        </form></td>
                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
<!--boutton reservation-->

<script src="index.js"></script>






    <?php foreach ($hebergements as $hebergement): ?>
        <div class="card" style="width: 18rem;">
            <img src="./asset/imgLogmements/<?php echo $hebergement['image'];?>" height="300px" width="100%">
            <div class="card-body">
                <h5 class="card-title text-center"><a href="?id=<?php echo $hebergement['id'];?>"><?php echo $hebergement['name'];?></a></h5>
                <p class="text-center"><?php echo $hebergType;?></p>
                <p class="text-center"><?php echo $hebergement['capacité'];?> personne(es)</p>
                <p class="text-center"> <?php echo $hebergement['prix']."€";?> /jours</p>
                <form action="reservation.php" method="post">
                    <input name="idHeberg" value="<?php echo $hebergement['id'];?>" hidden>
                    <form action="reservation.php" method="post">
                        <input name="idHeberg" value="<?php echo $hebergement['id'];?>" hidden>
                        <button  class="btn btn-outline-secondary" type="submit" name="submit" <?php if (!$hebergement['status']) echo 'disabled'; ?>>Réserver</button>
                    </form>
            </div>
        </div>


<?php endforeach;?>
<?php include './structure/footer.php';?>
</body>
</html>