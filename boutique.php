<?php
session_start();
require_once './structure/head.php';
require_once './structure/navbar.php';
require_once './functions/getDataBase.php';
$db = getDatabaseConnection();
$reqProducts = $db ->prepare('SELECT * FROM `products`');
$reqProducts->execute();
$produits = $reqProducts->fetchAll();
?>
<table>
    <thead>
            <tr>
                    <th>Produits Complémentaires</th>
                    <th>Prix</th>
            </tr>
    </thead>
    <tbody>
            <?php foreach ($produits as $produit): ?>)
            <tr>
                <td><?php echo $produit['name'];?></td>
                <td><?php echo $produit['price']."€";?></td>
            </tr>
            <? endforeach;?>
    </tbody>
</table>
       

</body>
</html>