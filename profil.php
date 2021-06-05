<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';





include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">
    <div class="bg-light p-5 rounded ">
        <h1 class="text-center"> Bienvenue <?php echo ucfirst($_SESSION['membre']['prenom']); ?> <i class="far fa-user-circle"></i></h1>
        <p class="lead text-center">note
            <hr>
            <?php echo $msg; ?>
        </p>
    </div>

    <!-- Avatar -->
    <div class="row justify-content-around p-5">
        <div class="card align-items-center col-md-5" >
            <?php if ($_SESSION["membre"]["civilite"] == "m") { ?>
                <img src="<?php echo URL; ?>assets/img/man.png" alt="avatar male" class="card-img-top" style="width:15rem">
            <?php } else { ?>
                <img src="<?php echo URL; ?>assets/img/woman.png" alt="avatar female" class="card-img-top " style="width:15rem">
            <?php } ?>

            <div class="card-body">
                <h5 class="card-title text-center"> <?= $_SESSION["membre"]["prenom"] . " " . $_SESSION["membre"]["nom"] ?> </h5>
            </div>

            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center">E-mail de contact : <?= $_SESSION["membre"]["email"] ?> </li>
                <li class="list-group-item text-center">Téléphone : <?= $_SESSION["membre"]["telephone"] ?> </li>
                <li class="list-group-item text-center"> <a class="btn" aria-current="page" href="deposer_annonce.php">Publier une annonce</a></li>
            </ul>

        </div>

        <div class="col-md-7 mt-3">
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center">
                    <h5> Mes annonces en cours </h5>
                </li>

                

                <li class="list-group-item text-center">
                    <h5>Les commentaires reçus</h5>
                </li>
            </ul>


        </div>
    </div>
</main>

<?php
include 'inc/footer.inc.php';
