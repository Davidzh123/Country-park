<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>

<?php
if(isset($_GET['id'])){
    //rexup les logements
    $reqLogement =$db->prepare('SELECT * FROM herbergeent WHERE id=:id');
    $reqLogement->execute([
        "id"=>$_GET['id']
    ]);
    $logements = $reqLogement->fetch();
} else {
    header('Location: ./admin.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Modifier Logement</title>
    <?php require_once './structure/head.php';?>
</head>
<body>
<?php
    require_once './structure/navbar.php';
?>
<h1>Modifier <?php echo $logements['name'];?></h1>

<form action="modifierLogement.php?id=<?php echo $_GET['id'];?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Nom du logement</label>
        <input type="text"class="form-control" name="name" placeholder="Nom du logement" id="name">
    </div>

    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="text" class="form-control" name="prix" id="prix">
    </div>
    <div class="mb-3">
        <label for="capacite" class="form-label">Capacité</label>
        <input type="number" min="0" class="form-control" name="Capacité" id="capacite">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <input type="checkbox" name="status" id="status" value="1">
    </div>
    <button type="submit" class="btn btn-primary" name="submit" id="submit">Modifier</button>
</form>
<?php
if(isset($_POST['submit'])){
    $modifierLogement=$db->prepare('UPDATE herbergeent SET name=:name , prix=:prix,capacité=:capacite,status=:status WHERE id=:id');
    $modifierLogement->execute([
            "name"=>$_POST['name'],
            "prix"=>$_POST['prix'],
            "capacite"=>$_POST['Capacité'],
            "status"=>isset($_POST['status']) ? 1 : 0,
            "id"=>$_GET['id']
    ]);
}
require_once './structure/footer.php';
?>
</body>
</html>
