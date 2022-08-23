
<nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#">Country Park</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="hebergement.php">Locations des emplacements et logments</a>
                </li>
                <?php
                if(isset($_SESSION['id'])) {
                ?>
                <li class="nav-item">
                    <a class="nav-link active" href="./profil.php">Voir Votre Profil</a>
                </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="./deconnexion.php">Déconnexion</a>
                    </li>
                    <?php if($_SESSION['admin']):?>
                    <li class="nav-item">
                        <a class="nav-link active" href="./admin.php?=<?php echo $_SESSION['admin'];?>">Voir Votre Profil ADMIN</a>
                    </li>
                    <?php endif;?>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link active" href="register.php">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="inscription.php">Inscription</a>
                </li>
                <?php
                }
                ?>


            </ul>

            <form class="d-flex" role="search" action="navbar.php" method="post">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" value="<?php if(isset($_POST['search'])) echo $_POST['search']; ?>">
                <button class="btn btn-outline-success" type="submit">Recherche</button>
            </form>

        </div>
    </div>
</nav>
