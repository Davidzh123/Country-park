<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>
<html>
<?php
if (isset($_GET['id'])){
    $reqDeleteAvis = $db->prepare('DELETE FROM avis WHERE id=:id');
    $reqDeleteAvis ->execute([
        "id"=>$_GET['id']
    ]);
    header('Location: ./admin.php');
}
?>

</html>
