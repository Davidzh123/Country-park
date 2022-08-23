<?php
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
var_dump($_GET['id']);
if(isset($_GET['id'])){
//    $reqUser = $db->prepare('SELECT * ')
    $reqPromuAdmin = $db->prepare('UPDATE users SET admin = 1  WHERE id=:id ');
    $reqPromuAdmin->execute([
        "id"=>$_GET['id']
    ]);
    header('Location: ./admin.php');
}
?>