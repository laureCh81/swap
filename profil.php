<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

$pseudo = $_SESSION['membre']['pseudo'];
// $mdp = '';
// $nom = '';
// $prenom = '';
// $telephone =  '';
// $email = '';
// $adresse = '';
// $cp = '';
// $ville = '';
// $pays = '';

// date inscription
$date = $pdo->query("SELECT DATE_FORMAT(date_enregistrement, '%d/%m/%Y') AS date FROM membre WHERE pseudo = '" . $pseudo . "'");
$inscription = $date->fetch(PDO::FETCH_ASSOC);

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
    if (iconv_strlen($_POST['mdp']) < 6 ) {
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
    <div class="bg-light p-5 rounded ">
        <h1 class="text-center"> Bienvenue <?= ucfirst($_SESSION['membre']['pseudo']); ?> <i class="far fa-user-circle"></i></h1>
        <p class="lead text-center">Membre depuis le <?= $inscription['date']; ?> </p>
        <p class="lead text-center">note
            <hr>

        </p>
    </div>

    <!-- Avatar -->
    <div class="row justify-content-around p-5">
        <div class="card align-items-center col-md-5">
            <?php if ($_SESSION["membre"]["civilite"] == "m") { ?>
                <img src="<?php echo URL; ?>assets/img/man.png" alt="avatar male" class="card-img-top" style="width:15rem">
            <?php } else { ?>
                <img src="<?php echo URL; ?>assets/img/woman.png" alt="avatar female" class="card-img-top " style="width:15rem">
            <?php } ?>

            <div class="card-body">
                <h5 class="card-title text-center"> <?= $_SESSION["membre"]["prenom"] . " " . $_SESSION["membre"]["nom"] ?> </h5>
            </div>

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
                <li class="list-group-item text-center"> <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#coordonnees">Mettre a jour vos coordonnées</button>
                </li>
                <li class="list-group-item text-center"> <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#psw">Changer votre mot de passe</button>
                </li>
            </ul>
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
                        <form method="POST" action="" class="row">
                            <div class="form-group col-12">
                                <label for="email" class="form-label">Email </label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $_SESSION['membre']['email']; ?>">
                            </div>
                            <div class="form-group col-12">
                                <label for="telephone" class="form-label">Téléphone </label>
                                <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $_SESSION['membre']['telephone']; ?>">
                            </div>
                            <div class="form-group col-12">
                                <label for="adresse">Adresse</label>
                                <input type="text" class="form-control" id="adresse" name="adresse" placeholder="<?php echo $_SESSION['membre']['adresse'] ?>">
                            </div>
                            <div class="form-group col-3">
                                <label for="cp">Code Postal</label>
                                <input type="text" class="form-control" id="cp" name="cp" placeholder="<?php echo $_SESSION['membre']['cp'] ?>">
                            </div>
                            <div class="form-group col-9">
                                <label for="ville">Ville</label>
                                <input type="text" class="form-control" id="ville" name="ville" placeholder="<?php echo $_SESSION['membre']['ville'] ?>">
                            </div>
                            <div class="form-group col-12">
                                <label for="pays">Pays</label>
                                <input type="text" class="form-control" id="pays" name="pays" placeholder="<?php echo $_SESSION['membre']['pays'] ?>">
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
                        <hr><?php echo $msg; ?>
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
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center">
                    <h5> Mes annonces en cours </h5>
                </li>
                <li class="list-group-item text-center"> <a class="btn" aria-current="page" href="deposer_annonce.php">Publier une annonce</a></li>



                <li class="list-group-item text-center">
                    <h5>Les commentaires reçus</h5>
                </li>
            </ul>


        </div>
    </div>
</main>

<?php
include 'inc/footer.inc.php';