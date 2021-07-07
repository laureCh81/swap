<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';


$commentaire = "";
$note = "";
$question = "";


$controlIdAnnonce = $pdo->prepare("SELECT id_annonce AS id_annonce FROM annonce WHERE id_annonce = :id_annonce");
$controlIdAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$controlIdAnnonce->execute();
$idAnnonceControl = $controlIdAnnonce->fetch(PDO::FETCH_ASSOC);


if (!isset($_GET['id_annonce'])) {
    header('location:index.php');
}

//-----------------------------------------------
//Affichage des données de l'annonce
//-----------------------------------------------

$recupAnnonce = $pdo->prepare("SELECT DATE_FORMAT(date_enregistrement, '%d/%m/%Y') AS publication, titre AS titre, description_longue AS description, prix AS prix, cp AS cp, ville AS ville FROM annonce WHERE id_annonce = :id_annonce");
$recupAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$recupAnnonce->execute();
$donneesAnnonce = $recupAnnonce->fetch(PDO::FETCH_ASSOC);

$recupPhoto = $pdo->prepare("SELECT photo1, photo2, photo3, photo4, photo5 FROM photo, annonce WHERE photo_id = id_photo AND id_annonce = :id_annonce");
$recupPhoto->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$recupPhoto->execute();

$recupUser = $pdo->prepare("SELECT pseudo FROM membre, annonce WHERE id_membre = membre_id AND id_annonce = :id_annonce");
$recupUser->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$recupUser->execute();
$donneesUser = $recupUser->fetch(PDO::FETCH_ASSOC);

$recupNote = $pdo->prepare("SELECT AVG(note) AS note FROM note WHERE membre_id1 IN
(SELECT id_membre FROM membre WHERE id_membre IN
(SELECT membre_id FROM annonce WHERE id_annonce = :id_annonce))");
$recupNote->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$recupNote->execute();
$tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
settype($tabNote['note'], "int");



//-----------------------------------------------
//Contact vendeur
//-------------------------------------------------
$telephone = $pdo->prepare("SELECT telephone FROM membre WHERE id_membre IN (SELECT membre_id FROM annonce WHERE id_annonce = :id_annonce)");
$telephone->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$telephone->execute();
$tel = $telephone->fetch(PDO::FETCH_ASSOC);

$mailAnnonce = $pdo->prepare("SELECT email FROM membre WHERE id_membre IN (SELECT membre_id FROM annonce WHERE id_annonce = :id_annonce)");
$mailAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$mailAnnonce->execute();
$resultMail = $mailAnnonce->fetch(PDO::FETCH_ASSOC);
$mail = $resultMail['email'];


//if (isset($_POST['message'])) {
// $entete  = 'MIME-Version: 1.0' . "\r\n";
// $entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
// $entete .= 'From: ' . $_POST['email'] . "\r\n";

// $message = '<h1>Message envoyé depuis la page annonce' .$donneesAnnonce['titre']. '</h1>
// <p><b>Nom : </b>' . $_POST['nom'] . '<br>
// <b>Email : </b>' . $_POST['email'] . '<br>
// <b>Message : </b>' . $_POST['message'] . '</p>';

// $retour = mail($mail, $donneesAnnonce['titre'], $message, $entete);
// if($retour) {
//     echo '<p>Votre message a bien été envoyé.</p>';
// }
//  $msg .= '<div class="alert alert-success mb-3">Votre mail a bien été envoyé </div>';
//}


//-----------------------------------------------
//Proposition d'annonce
//-------------------------------------------------

$proposViaCateg = $pdo->prepare("SELECT id_annonce, photo FROM annonce WHERE categorie_id IN
(SELECT categorie_id FROM annonce WHERE id_annonce = :id_annonce) AND id_annonce != :id_annonce ORDER BY id_annonce DESC LIMIT 5");
$proposViaCateg->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$proposViaCateg->execute();


//-----------------------------------------------
//note
//-------------------------------------------------

//recup id1 (celui qui a posté l'annonce)
$membreId1 = $pdo->prepare("SELECT id_membre AS id1 FROM membre, annonce WHERE id_membre = membre_id AND id_annonce = :id_annonce");
$membreId1->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$membreId1->execute();
$idMembre1 = $membreId1->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['note']) && isset($_POST['commentaire'])) {

    $commentaire = trim($_POST['commentaire']);
    $note = $_POST['note'];
    $erreur = false;
    $idMembre2 =  $_SESSION['membre']['id_membre'];

    //description
    if (iconv_strlen($commentaire) < 10) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir compléter votre commentaire.</div>';
    }

    if ($note == 0) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir mettre une note entre 1 et 5.</div>';
    }

    if ($idMembre1['id1'] == $idMembre2) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Vous ne pouvez pas noter votre propre annonce</div>';
    }

    if ($erreur == false) {
        $enregistrementNote = $pdo->prepare("INSERT INTO note (membre_id1, membre_id2, annonce_id, note, avis) VALUES ('" . $idMembre1['id1'] . "', '" . $idMembre2 . "', '" . $idAnnonceControl['id_annonce'] . "', :note, :avis)");
        $enregistrementNote->bindParam(':note', $note, PDO::PARAM_STR);
        $enregistrementNote->bindParam(':avis', $commentaire, PDO::PARAM_STR);
        $enregistrementNote->execute();

        $msg .= '<div class="alert alert-success mb-3">Votre avis à bien été pris en compte</div>';
    }
    header('location:annonce.php?id_annonce=' . $_GET['id_annonce']);
}

//-----------------------------------------------
//Question/reponse au vendeur
//-------------------------------------------------

if (isset($_POST['question'])) {
    $erreur = false;
    $question = trim($_POST['question']);
    $idMembre2 =  $_SESSION['membre']['id_membre'];

    if (iconv_strlen($question) < 10) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir préciser votre question.</div>';
    }

    if ($idMembre1['id1'] == $idMembre2) {
        var_dump($idMembre1['id1']);
        var_dump($idMembre2);
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Vous ne pouvez pas publier de question sur votre propre annonce</div>';
    }
    if ($erreur == false) {
        $enregistrementQuestion = $pdo->prepare("INSERT INTO question (membre_id, annonce_id, question) VALUES ('" . $idMembre2 . "', :id_annonce, :question)");
        $enregistrementQuestion->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
        $enregistrementQuestion->bindParam(':question', $question, PDO::PARAM_STR);
        $enregistrementQuestion->execute();
    }
}

$recupQuestion = $pdo->prepare("SELECT id_question, question_id, question, reponse, DATE_FORMAT(question.date_enregistrement, '%d/%m/%Y') AS dateQ, DATE_FORMAT(reponse.date_enregistrement, '%d/%m/%Y') AS dateR FROM question LEFT JOIN reponse ON id_question = question_id WHERE annonce_id = :id_annonce ORDER BY id_question DESC");
$recupQuestion->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
$recupQuestion->execute();


// $recupReponse = $pdo->prepare("SELECT reponse, DATE_FORMAT(date_enregistrement, '%d/%m/%Y') AS date, question_id FROM reponse WHERE question_id IN
//     (SELECT question_id FROM question, annonce WHERE annonce_id = :id_annonce)");
// $recupReponse->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
// $recupReponse->execute();
// $affichReponse = $recupReponse->fetch(PDO::FETCH_ASSOC);

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">


    <div class="bg-light p-5 rounded ">
        <h1 class="text-center"><i class="fas fa-shopping-bag"></i> Détail de l'annonce <i class="fas fa-shopping-bag"></i></h1>
        <p class="lead text-center">
            <hr><?php echo $msg; ?>
        </p>
        <!-- Contact vendeur -->
        <div class="d-flex flex-row-reverse ">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#contactVendeur">Contacter le vendeur</button>
            <div class="modal fade" id="contactVendeur" tabindex="-1" aria-labelledby="contactV" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="contactV">Contacter le vendeur</h5>
                        </div>
                        <div class="modal-body">
                            <p>Contacter le vendeur par téléphone : <?php echo chunk_split($tel['telephone'], 2, ' '); ?> </p>
                            <p>Ou par email : </p>
                            <form method="POST" class="row">
                                <div>
                                    <label class="form-label">Votre Nom</label>
                                    <input class="form-control" type="text" name="nom" required>
                                </div>
                                <div>
                                    <label class="form-label">Votre mail</label>
                                    <input class="form-control" type="email" name="email" required>
                                </div>
                                <div>
                                    <label class="form-label">Votre message</label>
                                    <textarea class="form-control" name="message" required></textarea>
                                </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="button submit" class="btn btn-primary">Envoyer le mail</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin Contact vendeur -->
    <!-- Show annonce -->
    <?php if ($controlIdAnnonce->rowCount() > 0) { ?>
        <div class="col p-5">
            <h3><?php echo $donneesAnnonce['titre']; ?>
            </h3>
        </div>
        <div class="row align-items-center">
            <div class="col-lg-4 pb-5">
                <div class="carousel slide" data-bs-ride="carousel">
                    <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php while ($donneesPhoto = $recupPhoto->fetch(PDO::FETCH_ASSOC)) {
                                foreach ($donneesPhoto as $indice => $valeur) {
                                    if ($indice == 'photo1') {
                                        echo  '<div class="carousel-item active"><img src="assets/img/' . $valeur . '" class="d-block img-fluid rounded img-thumbnail" alt="Photo annonce"></div>';
                                    } elseif ($indice != 'photo1' && $valeur != NULL) {
                                        echo  '<div class="carousel-item"><img src="assets/img/' . $valeur . '" class="d-block img-fluid rounded img-thumbnail" alt="Photo annonce"></div>';
                                    }
                                }
                            } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mt-5">
                <p><?php echo $donneesAnnonce['description']; ?></p>
            </div>
        </div>

        <div class="row d-flex justify-content-between mx-5">
            <div class="col-lg-3">
                <p><i class="far fa-calendar-alt"></i> Date de publication: <?php echo $donneesAnnonce['publication']; ?></p>
            </div>
            <div class="col-lg-3">
                <p><i class="far fa-user-circle"></i>
                    <?php
                    echo $donneesUser['pseudo'];
                    if ($tabNote['note'] == 1) {
                        echo ' - <img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                    } elseif ($tabNote['note'] == 2) {
                        echo ' - <img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                    } elseif ($tabNote['note'] == 3) {
                        echo ' - <img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                    } elseif ($tabNote['note'] == 4) {
                        echo ' - <img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                    } elseif ($tabNote['note'] == 5) {
                        echo ' - <img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15">';
                    }  ?></p>
            </div>
            <div class="col-lg-3">
                <p><i class="fas fa-euro-sign"></i> <?php echo $donneesAnnonce['prix']; ?> €</p>
            </div>
            <div class="col-lg-3">
                <p><i class="fas fa-map-marked-alt"></i> <?php echo $donneesAnnonce['cp'] . ' ' . $donneesAnnonce['ville']; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-sm-12 mt-5">
                <iframe width="450" height="250" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?key=AIzaSyCB3c5bwe1YQDtC0j32ppdwnkmofc8mZRU&q=<?php echo $donneesAnnonce['cp']; ?>+France" allowfullscreen>
                </iframe>
            </div>
            <div class="col-lg-6 col-sm-12 mt-5">
                <?php
                while ($affichQuestion = $recupQuestion->fetch(PDO::FETCH_ASSOC)) {
                    if ($affichQuestion['question_id'] == $affichQuestion['id_question']) {
                        echo '<div class="border mb-2"><p class="questRep font-monospace ps-2 mt-n1"><strong>Question envoyée le ' . $affichQuestion['dateQ'] . '</strong> ' . $affichQuestion['question'] . '</p><p class="questRep font-monospace ps-2"><strong>Réponse du vendeur le ' . $affichQuestion['dateR'] . ' : </strong> ' . $affichQuestion['reponse'] . '</p>';
                    } else {
                        echo '<div class="border mb-2"><p class="questRep font-monospace ps-2 mt-n1"><strong>Question envoyée le ' . $affichQuestion['dateQ'] . '</strong> ' . $affichQuestion['question'] . '</p>';
                    }
                    echo '</div>';
                } ?>
            </div>
        </div>
        <div class="mt-5">
            <h5>Autres annonces dans cette catégorie</h5>
            <div class="row p-5 align-items-center ">
                <?php

                while ($viaCateg = $proposViaCateg->fetch(PDO::FETCH_ASSOC)) {
                    foreach ($viaCateg as $indice => $valeur) {
                        if ($indice == 'id_annonce') {
                            echo '<div class="col-3 me-5"><a href="annonce.php?id_annonce=' . $viaCateg['id_annonce'] . '">';
                        } elseif ($indice == 'photo') {
                            echo '<img  src="' . URL . 'assets/img/' . $valeur . '" class="img-fluid rounded img-thumbnail" alt="image produit"></a></div>';
                        }
                    }
                }
                if ($proposViaCateg->rowCount() < 1) {
                    echo '<p>Aucune autre annonce dans cette catégorie. </p> ';
                }

                ?>
            </div>
        </div>
        <!-- End Show annonce -->
        <!-- Note, question et retour annonce -->
        <div class="row mt-5">
            <div class="col-4 mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#noteModal">Noter le vendeur</button>
            </div>
            <div class="col-4 mt-3">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#question">Poser une question au vendeur</button>
            </div>
            <div class="col-4 mt-3">
                <a class="btn" aria-current="page" href="index.php">Retour vers les annonces</a>
            </div>
        </div>
        <!-- modal question -->
        <div class="modal fade" id="question" tabindex="-1" aria-labelledby="Modalquestion" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header row">
                        <h4 class="modal-title" id="Modalquestion">Question</h4>
                        <p>Une question ? Besoin de précisions sur cette annonce ?</p>
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
                            <form id="questionForm" method="POST">
                                <div class="form-group">
                                    <textarea class="form-control" id="question" name="question" value="<?php echo $question ?>"></textarea>
                                </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn closeWin" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn" id="enregistrer" name="enregistrer">Envoyer</button>
                    </div>
                    </form>
                <?php   }  ?>
                </div>
            </div>
        </div>
        <!-- modal question -->
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
                            <form id="noteForm" method="POST" action="" class="row">
                                <div class="form-group col-12">
                                    <label for="note" class="form-label">Note</label>
                                    <div class="stars">
                                        <i class="far fa-star fa-2x" data-value="1"></i><i class="far fa-star fa-2x" data-value="2"></i><i class="far fa-star fa-2x" data-value="3"></i><i class="far fa-star fa-2x" data-value="4"></i><i class="far fa-star fa-2x" data-value="5"></i>
                                    </div>

                                    <input class="form-control d-none" type="text" name="note" id="note" value="0">
                                </div>
                                <div class="form-group col-12 mt-3">
                                    <label for="commentaire" class="form-label">Votre avis</label>
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
        <!-- Fin modal note -->





    <?php } else {
        echo '<div class="alert alert-danger mb-3 text-center">Cette annonce est inconnue. Veuillez effectuer une recherche.</div>';
    } ?>
</main>







<?php

include 'inc/footer.inc.php';
