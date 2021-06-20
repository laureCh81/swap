<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';
if (user_connected() == false) {
    header('location:connexion.php');
}
$pseudo = $_SESSION['membre']['pseudo'];
$idMembre = $_SESSION['membre']['id_membre'];
$reponse = '';

// date inscription
$date = $pdo->query("SELECT DATE_FORMAT(date_enregistrement, '%d/%m/%Y') AS date FROM membre WHERE pseudo = '" . $pseudo . "'");
$inscription = $date->fetch(PDO::FETCH_ASSOC);

//Annonce en cours
$liste_annonce = $pdo->query("SELECT id_annonce, photo, titre, description_courte, prix FROM annonce, membre WHERE pseudo =  '" . $pseudo . "'AND membre_id=id_membre");

//Note
$recupNote = $pdo->query("SELECT AVG(note) AS note FROM note WHERE membre_id1='" . $idMembre . "'");
$tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
settype($tabNote['note'], "int");

$recupAvis = $pdo->query("SELECT note, avis FROM note WHERE membre_id1='" . $idMembre . "'");

// Question en attente
$recupQuestion = $pdo->query("SELECT id_question, titre, question FROM question, annonce WHERE id_annonce = annonce_id AND annonce.membre_id = '" . $idMembre . "' AND id_question NOT IN (SELECT question_id FROM reponse);");


// Reponse aux question

if (isset($_POST['reponse'])) {
    $reponse = trim($_POST['reponse']);
    $questionId = $_GET['id_question'];

    $validReponse = $pdo->prepare("INSERT INTO reponse (question_id, reponse) VALUES ('" . $questionId . "', :reponse )");
    $validReponse->bindParam(':reponse', $reponse, PDO::PARAM_STR);
    $validReponse->execute();
    header('location:profil.php');
}

//Formulaire coordonnées
if (isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['ville']) && isset($_POST['pays'])) {
    $telephone =  trim($_POST['telephone']);
    $email = trim($_POST['email']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);
    $ville = trim($_POST['ville']);
    $pays = trim($_POST['pays']);

    $erreur = false;

    // mail format
    if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Veuillez utiliser un email valide svp</div>';
    }

    //telephone format
    $verif_telephone = preg_match('#^0[1-68][0-9]{8}$#', $telephone);
    if ($verif_telephone == false) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Veuillez renseigner un numéro de téléphone valide svp</div>';
    }

    //cp format
    $verif_cp = preg_match('$((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$', $cp);
    if ($verif_cp == false) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Veuillez renseigner un code postal valide svp</div>';
    }

    //update registration
    if ($erreur == false) {
        //user's adress        
        $modif_adresse = $pdo->prepare("UPDATE membre SET adresse = :adresse, cp = :cp, ville = :ville, pays = :pays WHERE pseudo = '" . $pseudo . "'");
        $modif_adresse->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $modif_adresse->bindParam(':cp', $cp, PDO::PARAM_STR);
        $modif_adresse->bindParam(':ville', $ville, PDO::PARAM_STR);
        $modif_adresse->bindParam(':pays', $pays, PDO::PARAM_STR);
        $modif_adresse->execute();


        //verification tel and mail
        $verif_tel = $pdo->prepare("SELECT telephone FROM membre WHERE pseudo = '" . $pseudo . "'");
        $verif_tel->execute();

        if ($verif_tel != $telephone) {
            $modif_tel = $pdo->prepare("UPDATE membre SET telephone = :telephone WHERE pseudo = '" . $pseudo . "'");
            $modif_tel->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $modif_tel->execute();
        }

        $verif_mail = $pdo->prepare("SELECT email FROM membre WHERE pseudo = '" . $pseudo . "'");
        $verif_mail->execute();

        if ($verif_mail != $email) {
            $modif_mail = $pdo->prepare("UPDATE membre SET email = :email WHERE pseudo = '" . $pseudo . "'");

            $modif_mail->bindParam(':email', $email, PDO::PARAM_STR);
            $modif_mail->execute();
        }
        header('location:profil.php');
        $msg .= '<div class="alert alert-success mb-3">Les modifications ont été enregistrées</div>';
    }
}
// modification mdp
if (isset($_POST['mdp'])) {
    $mdp = trim($_POST['mdp']);
    $erreur = false;
    if (iconv_strlen($_POST['mdp']) < 6) {
        $erreur = true;
        $msg .= '<p class="alert alert-danger mb-3">Attention le mot de passe doit contenir au moins 6 caractères.</p>';
    }
    if ($erreur == false) {
        $mdp = password_hash($mdp, PASSWORD_DEFAULT);
        $enregistrement = $pdo->prepare("UPDATE membre SET mdp = :mdp WHERE pseudo = '" . $pseudo . "'");
        $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
        $enregistrement->execute();
        session_destroy();
        header('location:connexion.php');
    }
}

$coordonnes_req = $pdo->query("SELECT email AS email, telephone AS telephone, adresse AS adresse, cp AS cp, ville AS ville, pays AS pays FROM membre WHERE pseudo = '" . $pseudo . "'");
$coordonnes = $coordonnes_req->fetch(PDO::FETCH_ASSOC);



include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">
    <div class="bg-light ">
        <h1 class="text-center"><?= ucfirst($_SESSION['membre']['pseudo']); ?> <i class="far fa-user-circle"></i></h1>
        <p class="lead text-center">Membre depuis le <?= $inscription['date']; ?> </p>
        <p class="lead text-center"><?php
                                    if ($tabNote['note'] == 0) {
                                        echo 'Vous n\'avez pas encore de note <img src="assets/img/starVide.ico" alt="etoile" width="25">';
                                    } elseif ($tabNote['note'] == 1) {
                                        echo '<img src="assets/img/star.ico" alt="etoile"  width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30">';
                                    } elseif ($tabNote['note'] == 2) {
                                        echo '<img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30">';
                                    } elseif ($tabNote['note'] == 3) {
                                        echo '<img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30">';
                                    } elseif ($tabNote['note'] == 4) {
                                        echo '<img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/starVide.ico" alt="etoile" width="30">';
                                    } elseif ($tabNote['note'] == 5) {
                                        echo '<img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30"><img src="assets/img/star.ico" alt="etoile" width="30">';
                                    }
                                    ?>

        </p>
    </div>

    <!-- Avatar -->
    <div class="row justify-content-around mt-5">
        <div class="card align-items-center col-md-5">
            <?php if ($_SESSION["membre"]["civilite"] == "m") { ?>
                <img src="<?php echo URL; ?>assets/img/man.png" alt="avatar male" class="card-img-top" style="width:15rem">
            <?php } else { ?>
                <img src="<?php echo URL; ?>assets/img/woman.png" alt="avatar female" class="card-img-top " style="width:15rem">
            <?php } ?>
            <div class="card-body mt-3">
                <h5 class="card-title text-center"> <?= $_SESSION["membre"]["prenom"] . " " . $_SESSION["membre"]["nom"] ?> </h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center">E-mail de contact : <?= $coordonnes['email'] ?> </li>
                    <li class="list-group-item text-center">Téléphone : <?= $coordonnes['telephone'] ?> </li>
                    <li class="list-group-item text-center">Adresse : <br>
                        <?php if ($coordonnes['adresse'] == '') {
                            echo 'Non renseignée';
                        } else {
                            echo $coordonnes['adresse'] . '<br>' . $coordonnes['cp'] . ' ' . $coordonnes['ville'] . '<br>' . $coordonnes['pays'];
                        } ?>
                    </li>
                </ul>
                <div class="mt-5">
                    <button type="button" class="btn me-1" data-bs-toggle="modal" data-bs-target="#coordonnees">Mettre a jour vos coordonnées</button>
                    <button type="button" class="btn ms-1" data-bs-toggle="modal" data-bs-target="#psw">Changer votre mot de passe</button>
                </div>
                <div>
                    <?php if ($recupAvis->rowCount() > 0) { ?>
                        <h5 class="mt-5 text-center">Notes et avis reçus</h5>
                        <table class="table table-bordered table-hover mt-3">
                            <thead class="table text-center">
                                <tr class="table-primary">
                                    <th>Note</th>
                                    <th>Avis</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                while ($tabAvis = $recupAvis->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<tr >';
                                    foreach ($tabAvis as $indice => $valeur) {
                                        if ($indice == 'note') {
                                            echo '<td>';
                                            if ($tabNote['note'] == 1) {
                                                echo '<img src="assets/img/star.ico" alt="etoile"  width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                                            } elseif ($tabNote['note'] == 2) {
                                                echo '<img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                                            } elseif ($tabNote['note'] == 3) {
                                                echo '<img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                                            } elseif ($tabNote['note'] == 4) {
                                                echo '<img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/starVide.ico" alt="etoile" width="15">';
                                            } elseif ($tabNote['note'] == 5) {
                                                echo '<img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15"><img src="assets/img/star.ico" alt="etoile" width="15">';
                                            }
                                            echo '</td>';
                                        } elseif ($indice == 'avis') {
                                            echo '<td>' . $valeur . '</td>';
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>

            </div>
        </div>

        <!-- modal update -->
        <div class="modal fade" id="coordonnees" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="ModalLabel">Mise à jour des coordonnées</h4>
                        <hr><?php echo $msg; ?>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="row">
                            <div class="form-group col-12">
                                <label for="email" class="form-label">Email </label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $coordonnes['email']; ?>">
                            </div>
                            <div class="form-group col-12">
                                <label for="telephone" class="form-label">Téléphone </label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $coordonnes['telephone']; ?>">
                            </div>
                            <div class="form-group col-12">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $coordonnes['adresse'] ?>">
                            </div>
                            <div class="form-group col-3">
                                <label for="cp">Code Postal</label>
                                <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $coordonnes['cp'] ?>">
                                <div style="display: none; color: #f55;" id="error-message"></div>
                            </div>
                            <div class="form-group col-9">
                                <label for="ville">Ville</label>
                                <select class="form-control " id="ville" name="ville"></select>

                            </div>
                            <div class="form-group col-12">
                                <label for="pays">Pays</label>
                                <input type="text" class="form-control" id="pays" name="pays" value="<?php echo $coordonnes['pays'] ?>">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn closeWin" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn" id="enregistrer" name="enregistrer">Enregistrer</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <!-- modal update -->
        <!-- modal update password -->
        <div class="modal fade" id="psw" tabindex="-1" aria-labelledby="ModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header row">
                        <h4 class="modal-title" id="ModalLabel">Changer votre mot de passe</h4>
                        <p>Votre mot de passe doit avoir au moins 6 caractères</p>
                        <?php echo $msg; ?>
                    </div>
                    <div class="modal-body">
                        <form id="profilForm" method="POST" action="" class="row">
                            <div class="form-group col-12">
                                <label for="mdp" class="form-label">Votre nouveau mot de passe</label>
                                <input type="text" class="form-control" id="mdp" name="mdp">
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn closeWin" data-bs-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn" id="enregistrer" name="enregistrer">Enregistrer</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- modal update password -->


        <div class="col-md-7 mt-3">
            <!-- Questions en attente -->
            <?php if ($liste_annonce->rowCount() > 0) { ?>
                <div class="col-12  border p-3">
                    <h5 class="text-center mb-3">Questions en attente de réponse sur mes annonces</h5>
                    <?php if ($recupQuestion->rowCount() > 0) {
                    ?>
                        <table class="table table-bordered table-hover">
                            <thead class="table text-center">
                                <tr class="table-primary">
                                    <th class="d-none">ID</th>
                                    <th>Titre Annonce</th>
                                    <th>Question</th>
                                    <th>Répondre</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                            <?php
                            while ($question = $recupQuestion->fetch(PDO::FETCH_ASSOC)) {
                                echo '<tr >';
                                foreach ($question as $indice => $valeur) {
                                    if ($indice == 'id_question') {
                                        echo '<td class="d-none">' . $valeur . '</td>';
                                    } else {
                                        echo '<td>' . $valeur . '</td>';
                                    }
                                }
                                echo '<td><a href= "?action=repondre&id_question=' . $question['id_question'] . '" class="btn btn-warning"><i class="far fa-edit"></i></td></tr>';
                            }
                        } else {
                            echo '<p class="text-center">Aucune question en attente actuellement.</p> ';
                        } ?>
                            </tbody>
                        </table>
                </div>
                <?php if (isset($_GET['action']) && $_GET['action'] == 'repondre' && !empty($_GET['id_question'])) { ?>
                    <div class=" border border-3 border-primary">
                        <h6 class="text-center">Répondre</h6>

                        <form id="reponseForm" method="POST">
                            <div class="form-group px-4">
                                <textarea class="form-control " id="reponse" name="reponse" value="<?php echo $reponse ?>"></textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn my-2 me-4" id="enregistrer" name="enregistrer">Envoyer</button>
                            </div>
                        </form>
                    </div>
            <?php }
            } ?>
            <!-- annonces en cours -->
            <div class="col-12 mt-5 border p-3">
                <h5 class="text-center mb-3"> Mes annonces en cours </h5>

                <?php if ($liste_annonce->rowCount() > 0) { ?>
                    <table class="table table-bordered table-hover">
                        <thead class="table text-center">
                            <tr class="table-primary">
                                <th class="d-none">ID</th>
                                <th>Photo</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Modif</th>
                                <th>Suppr</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                        <?php
                        while ($annonce = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr >';
                            foreach ($annonce as $indice => $valeur) {

                                if ($indice == 'photo') {
                                    echo '<td><img src="' . URL . 'assets/img/' . $valeur . '" width="70" class="img-fluid" alt="image produit"></td>';
                                } elseif ($indice == 'description_courte') {
                                    echo '<td >' . $valeur . '<a href="annonce.php?id_annonce=' . $annonce['id_annonce'] . '">...</a></td>';
                                } elseif ($indice == 'id_annonce') {
                                    echo '<td class="d-none"></td>';
                                } else {
                                    echo '<td>' . $valeur . '</td>';
                                }
                            }
                            echo '<td><a href= "?action=modifier&' . substr($annonce['titre'], 0, 10) . '" class="btn btn-warning"><i class="far fa-edit"></i></td>';
                            echo '<td><a href= "?action=supprimer&' . substr($annonce['titre'], 0, 10) . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cette annonce ?\'))"><i class="far fa-trash-alt"></i></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<p class="text-center">Aucune annonce en cours actuellement.</p> ';
                    }
                        ?>
                        </tbody>
                    </table>
            </div>
            <!-- annonces en cours -->
            <div class="d-flex justify-content-center my-3">
                <a class="btn " aria-current="page" href="deposer_annonce.php">Publier une annonce</a>
            </div>

        </div>

    </div>
    </div>
</main>

<?php
include 'inc/footer.inc.php';
