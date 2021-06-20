<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

$pseudo = $_SESSION['membre']['pseudo'];
$commentaire = "";
$note = "";

$controlIdAnnonce = $pdo->prepare("SELECT id_annonce AS id_annonce FROM annonce WHERE id_annonce = :id_annonce");
$controlIdAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$controlIdAnnonce->execute();
$idAnnonceControl = $controlIdAnnonce->fetch(PDO::FETCH_ASSOC);


// if (!isset($_GET['id_annonce'])) {
//     header('location:index.php');
// } 

//note et commentaire
if (isset($_POST['note']) && isset($_POST['commentaire'])) {

    $commentaire = trim($_POST['commentaire']);
    $note = $_POST['note'];

    $erreur = false;

    //description
    if (iconv_strlen($commentaire) < 10) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir compléter votre commentaire.</div>';
    }

    if ($note == 0) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir mettre une note entre 1 et 5.</div>';
    }
    //recup id1 (celui qui a posté l'annonce)
    $membreId1 = $pdo->prepare("SELECT id_membre AS id1 FROM membre, annonce WHERE id_membre = membre_id AND id_annonce = :id_annonce");
    $membreId1->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $membreId1->execute();
    $idMembre1 = $membreId1->fetch(PDO::FETCH_ASSOC);
    

    //recup id2 (celui qui a met un commentaire)
    $membreId2 = $pdo->query("SELECT id_membre AS id2 FROM membre WHERE pseudo = '" . $pseudo . "'");
    $idMembre2 = $membreId2->fetch(PDO::FETCH_ASSOC);

    if ($membreId1 == $membreId2) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Vous ne pouvez pas noter votre propre annonce</div>';
    }


    if ($erreur == false) {
        var_dump($_POST);
        $enregistrementNote = $pdo->prepare("INSERT INTO note (membre_id1, membre_id2, annonce_id, note, avis) VALUES ('" . $idMembre1['id1'] . "', '" . $idMembre2['id2'] . "', '" . $idAnnonceControl['id_annonce'] . "', :note, :avis)");
        $enregistrementNote->bindParam(':note', $note, PDO::PARAM_STR);
        $enregistrementNote->bindParam(':avis', $commentaire, PDO::PARAM_STR);
        $enregistrementNote->execute();

        $msg .= '<div class="alert alert-success mb-3">Votre avis à bien été pris en compte</div>';
    }
}








include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">


    <div class="bg-light p-5 rounded ">
        <h1 class="text-center"><i class="fas fa-ghost indigo "></i> template <i class="fas fa-ghost indigo"></i></h1>
        <p class="lead text-center">Bienvenue sur notre site.
            <hr><?php echo $msg; ?>
        </p>
    </div>
    <?php if ($controlIdAnnonce->rowCount() > 0) { ?>

        <div class="row">
            <div class="col-sm-6 mt-5">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">Noter le vendeur</button>

                <!-- modal note -->
                <div class="modal fade" id="noteModal" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header row">

                                <h4 class="modal-title" id="ModalLabel">Noter le vendeur</h4>
                                <p>Cette note est basée sur la qualité des échanges et du relationnel</p>
                                <?php echo $msg; ?>
                            </div>
                            <div class="modal-body">
                                <?php if (user_connected() == false) {  ?>
                                    <div class="form-group col-12">
                                        <p>Afin de pouvoir ajouter une note, vous devez être connecté</p>
                                        <a class="btn d-flex align-items-center" aria-current="page" href="connexion.php?id_annonce=<?php echo $_GET['id_annonce']; ?>"></i>Se connecter</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn closeWin" data-bs-dismiss="modal">Fermer</button>

                                    </div>


                                <?php   } else { ?>
                                    <form id="profilForm" method="POST" action="" class="row">
                                        <div class="form-group col-12">
                                            <label for="note" class="form-label">Note</label>
                                            <div class="stars">
                                                <i class="far fa-star fa-2x" data-value="1"></i><i class="far fa-star fa-2x" data-value="2"></i><i class="far fa-star fa-2x" data-value="3"></i><i class="far fa-star fa-2x" data-value="4"></i><i class="far fa-star fa-2x" data-value="5"></i>
                                            </div>

                                            <input class="form-control d-none" type="text" name="note" id="note" value="0">
                                        </div>
                                        <div class="form-group col-12 mt-3">
                                            <label for="commentaire" class="form-label">Votre commentaire </label>
                                            <textarea class="form-control" id="commentaire" name="commentaire" value="<?php echo $commentaire ?>"></textarea>

                                        </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn closeWin" data-bs-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn" id="enregistrer" name="enregistrer">Valider</button>
                            </div>
                            </form>
                        <?php   }  ?>
                        </div>
                    </div>
                </div>
                <!-- modal note -->


            </div>
            <div class="col-sm-6 mt-5">

            </div>
        </div>
</main>







<?php
    } else {
        echo '<div class="alert alert-danger mb-3 text-center">Cette annonce est inconnue. Veuillez effectuer une recherche.</div>';
    }
    include 'inc/footer.inc.php';
