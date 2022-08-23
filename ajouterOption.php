<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
$reqOption = $db->prepare('SELECT * FROM options ');
$reqOption->execute();
$option = $reqOption->fetchAll();
var_dump($option['id']);
?>
<html>
<?php require_once './structure/head.php'; ?>


<?php require_once './structure/navbar.php'; ?>
<h1 class="text-center"> Espace Admin </h1>
<H2>Ajouter une option :</H2>
<form action="ajouterOption.php" method="post">

    <div class="mb-3">
        <label for="name" class="form-label">Option</label>
        <input type="text" class="form-control" name="name" placeholder="Nom de l'option logement" id="name">
    </div>
    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="text" class="form-control" name="prix" placeholder="prix du option" id="prix">
    </div>
    <button type="submit" class="btn btn-primary" name="submit" id="submit">ajouter</button>
</form>
<style>
    a{
        text-decoration: none;
    }
</style>
</html>
<?php
if(isset($_SESSION['id'])){

//
   if(isset($_POST['submit'])){
        echo 'ok';
        $newOption = $db->prepare('INSERT INTO options (name,prix) VALUES (:name,:prix)');
        $newOption->execute([
                "name"=>$_POST['name'],
                "prix"=>$_POST['prix']

        ]);
        var_dump($_POST['prix']);

   }
//        $newOption = $db->prepare('INSERT INTO options (name) VALUES (:name)');
//        $newOption->execute([
//                "name"=>$_POST['name']
//        ]);
//   }else{
//       header('Location: ./admin.php');
//   }
}else{
    header('Location: ./admin.php');
}
