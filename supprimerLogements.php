<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
?>
<html>
<?php
if (isset($_GET['id'])){
    $reqDeleteLogement = $db->prepare('DELETE FROM herbergeent WHERE id=:id');
    $reqDeleteLogement ->execute([
        "id"=>$_GET['id']
    ]);
    header('Location: ./admin.php');
}
?>

</html>

