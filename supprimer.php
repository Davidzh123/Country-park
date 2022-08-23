<?php
session_start();
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
var_dump($_GET['id']);
?>
<html>
<?php
if (isset($_GET['id'])){
    $reqDeleteUser = $db->prepare('DELETE FROM users WHERE id=:id');
    $reqDeleteUser ->execute([
            "id"=>$_GET['id']
    ]);
    header('Location: ./admin.php');
}
?>

</html>
