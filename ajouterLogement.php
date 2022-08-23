<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
//RECUP TYPE
$reqTRype = $db ->prepare('SELECT * FROM type');
$reqTRype->execute();
$types= $reqTRype->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <title>Ajouter logement</title>
    <?php require_once './structure/head.php'; ?>
</head>
<body>
<?php require_once './structure/navbar.php'; ?>
<h1 class="text-center"> Espace Admin </h1>
<H2>Ajouter un logement :</H2>
<form action="ajouterLogement.php" method="post">

    <div class="mb-3">
        <label for="name" class="form-label">Nom du logement</label>
        <input type="text" class="form-control" name="name" placeholder="Nom du logement" id="name">
    </div>
    <div class="mb-3">
        <label for="type" class="form-label">Type du logmement </label>
        <select name="type">
            </option>
            <?php foreach ($types as $type):?>
                <option value="<?php echo $type['id'];?>"<?php if(isset($_POST['type']) && $_POST['type'] == $type['id']) echo " type" ?>>
                    <?php echo $type['name'];?>
                </option>
            <?php endforeach;?>
        </select>
    </div>

    <div class="mb-3">
        <label for="prix" class="form-label">Prix</label>
        <input type="number" class="form-control" name="prix" id="prix">
    </div>
    <div class="mb-3">
        <label for="capacite" class="form-label">Capacité</label>
        <input type="number" min="0" class="form-control" name="capacite" id="capacite">
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <input type="checkbox" name="status" id="status" value="1">
    </div>
    <div class="mb-3">
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="text"  class="form-control" name="image" id="image">
        </div>

        <label for="upload" class="form-label">image</label>
        <input type="file" name="upload">

    </div>

    <button type="submit" class="btn btn-primary" name="submit" id="submit">ajouter</button>
</form>

<?php
if (isset($_SESSION['id'])) {
//rexup les logements
    $reqLogement = $db->prepare('SELECT * FROM herbergeent WHERE id=:id ');
    $reqLogement->execute([
        "id" => $_SESSION['id']
    ]);
    $logement = $reqLogement->fetch();

    if (isset($_POST['submit'])) {


        $ajouterLogement = $db->prepare('INSERT INTO herbergeent (idType,name,prix,capacité,status,image) VALUES (:idType,:name,:prix,:capacite,:status,:image)');
        $ajouterLogement->execute([
                "idType"=>$_POST['type'],
            "name" => $_POST['name'],
            "prix" => $_POST['prix'],
            "capacite" => $_POST['capacite'],
            "status" => isset($_POST['status']) ? 1 : 0,
            "image" => isset($_POST['image'])
        ]);
        var_dump($_POST['type']);

        if (isset($_FILES['upload'])) {
            $tmpName = $_FILES['tmp_name'];
            $name = $_FILES['name'];
            $size = $_FILES['size'];
            $error = $_FILES['error'];

            $extensions = ['jpg', 'png', 'jpeg', 'gif'];
//Taille max que l'on accepte
            $maxSize = 40000000;
            if (in_array($_FILES['upload']['extension'], $extensions) && $size <= $maxSize) {
                move_uploaded_file($tmpName, './asset/imgLogements' . $name);
            } else {
                echo "Mauvaise extension ou taille trop grande";
            }
        }
    }
} else {
    header('Location: ./admin.php');
}

?>


<?php require_once './structure/footer.php'; ?>
</body>

</html>
