<?php


$recherche = '';
if (isset($_GET['rechercher'])) {
    $recherche = trim($_GET['rechercher']);
    header('location:' . URL . 'index.php?recherche=' . $recherche);
}

?>

<nav class="navbar navbar-expand-md navbar-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo URL; ?>index.php">
            <img src="<?php echo URL . 'assets/img/logo.png'; ?>" alt="logo swap" width="30" height="24" class="d-inline-block align-text-top"><strong>
                SWAP</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item me-3">
                    <a class="btn d-flex align-items-center" aria-current="page" href="deposer_annonce.php"><i class="me-2 far fa-plus-square fa-2x"></i> Déposer une annonce</a>
                </li>
                <li class="nav-item mt-1 me-3">
                    <form method="GET" class="d-flex">
                        <input id="rechercher" name="rechercher" class="form-control me-2" type="search" list="datalistOptions" placeholder="Rechercher" aria-label="Search">
                        <datalist id="datalistOptions">
                        <button class="btn d-flex align-items-center" type="submit"><i class="me-2 fas fa-search"></i></button>

                    </form>
                </li>
            </ul>
            <ul class="navbar-nav  mb-2 mb-lg-0">
                <?php if (user_admin() == true) { ?>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user-cog"></i> Administration</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown03">
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_annonce.php">Gestion des annonces</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_categorie.php">Gestion des catégories</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_membre.php">Gestion des membres</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_question_reponse.php">Gestions des questions & réponses</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/gestion_note.php">Gestions des notes</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>admin/statistiques.php">Statistiques</a>
                        </div>
                    </li>
                <?php } ?>
                <?php if (user_connected() == false) {  ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> Espace membre</a>
                        <div class="dropdown-menu" aria-labelledby="dropdown01">
                            <a class="dropdown-item" href="<?php echo URL; ?>connexion.php">Connexion</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>inscription.php">Inscription</a>
                        </div>
                    </li>
                <?php   } else { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i> Bienvenue <?php echo ucfirst($_SESSION['membre']['prenom']); ?> </a>
                        <div class="dropdown-menu" aria-labelledby="dropdown02">
                            <a class="dropdown-item" href="<?php echo URL; ?>profil.php">Profil</a>
                            <a class="dropdown-item" href="<?php echo URL; ?>index.php?action=deconnexion">Deconnexion</a>
                        </div>
                    </li>
                <?php   }  ?>

            </ul>
        </div>
    </div>
</nav>