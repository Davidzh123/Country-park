<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
$message ="";
?>
<!DOCTYPE html>
<html>
<head>
    <title>ajouter un type de logement</title>
    <?php require_once './structure/head.php'?>

</head>
<body>
<?php require_once './structure/navbar.php';?>
<h1 class="text-center"> Espace Admin </h1>
<H2>Ajouter un type de logement :</H2>
<form action="ajouterTypeLogement.php" method="post">

    <div class="mb-3">
        <label for="name" class="form-label">Type</label>
        <input type="text" class="form-control" name="name" placeholder="Nom du type de logement" id="name">
    </div>
    <button type="submit" class="btn btn-primary" name="submit" id="submit">ajouter</button>
</form>
<?php
    if(isset($_SESSION['id'])){
        if (isset($_POST['submit'])){
                $insrtNewType = $db->prepare('INSERT INTO type (name) VALUES (:name)');
                $insrtNewType->execute([
                        "name"=>$_POST['name']
                ]);

        }

    }else{
        header('Location: ./admin.php');
    }
?>
<style>
    a{
        text-decoration: none;
    }
</style>
<?php require_once './structure/footer.php';?>
</body>
</html>
