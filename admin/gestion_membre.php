<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

if (user_admin() == false) {
    header('location:../index.php');
}

//Affichage des membres
$liste_membre = $pdo->query("SELECT * FROM membre ORDER BY id_membre");

//Modification du membre
if (isset($_GET['action']) && $_GET['action'] == 'modifier') {
    $modifMembre = $pdo->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $modifMembre->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
    $modifMembre->execute();
    $statut = '';

    if (isset($_POST['statut'])) {
        $statut = trim($_POST['statut']);

        if ($statut == 1 || $statut == 2) {
            $changeStatut = $pdo->prepare("UPDATE membre SET statut = :statut WHERE id_membre = :id_membre ");
            $changeStatut->bindParam(':statut', $statut, PDO::PARAM_STR);
            $changeStatut->bindParam(':id_membre', $_GET['id_membre'], PDO::PARAM_STR);
            $changeStatut->execute();
            header('location:gestion_membre.php');
        } else {
            $msg .= '<div class="alert alert-danger mb-3">Attention! Ce statut est incorrect</div>';
        }
    }
}

//nouvel admin
if (isset($_GET['action']) && $_GET['action'] == 'creation') {
    $pseudo = '';
    $mdp = '';
    $nom = '';
    $prenom = '';
    $telephone =  '';
    $email = '';
    $civilite = '';
    $adresse = '';
    $cp = '';
    $ville = '';
    $pays = '';

    if (isset($_POST['pseudo']) && isset($_POST['mdp']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['telephone']) && isset($_POST['email']) && isset($_POST['civilite'])) {
        $pseudo = trim($_POST['pseudo']);
        $mdp = trim($_POST['mdp']);
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $telephone =  trim($_POST['telephone']);
        $email = trim($_POST['email']);
        $civilite = trim($_POST['civilite']);

        $erreur = false;
        //nickname length
        if (iconv_strlen($_POST['pseudo']) < 4 || iconv_strlen($_POST['pseudo']) > 14) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention le pseudo doit avoir entre 4 et 14 caractères inclus.</div>';
        }
        //characters allowed for the nickname
        $verif_pseudo = preg_match('#^[-a-zA-Z0-9._-]+$#', $pseudo);
        if ($verif_pseudo == false) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Les caractères autorisés pour le pseudo sont : a-z 0-9 </div>';
        }
        //nickname availability
        $verif_dispopseudo = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
        $verif_dispopseudo->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $verif_dispopseudo->execute();
        if ($verif_dispopseudo->rowCount() > 0) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention ce pseudo est deja utilisé</div>';
        }
        // mail format
        if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Veuillez utiliser un email valide svp</div>';
        }
        // password not empty
        if (empty($mdp)) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Veuillez renseigner un mot de passe svp</div>';
        }
        //telephone format
        $verif_telephone = preg_match('#^0[1-68][0-9]{8}$#', $telephone);
        if ($verif_telephone == false) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Veuillez renseigner un numéro de téléphone valide svp</div>';
        }


        //registration
        if ($erreur == false) {
            $mdp = password_hash($mdp, PASSWORD_DEFAULT);
            $enregistrement = $pdo->prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, telephone, email, civilite, statut, adresse, cp, ville, pays) VALUES (:pseudo, :mdp, :nom, :prenom, :telephone, :email, :civilite, 2, '','','','')");
            $enregistrement->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $enregistrement->bindParam(':mdp', $mdp, PDO::PARAM_STR);
            $enregistrement->bindParam(':nom', $nom, PDO::PARAM_STR);
            $enregistrement->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $enregistrement->bindParam(':telephone', $telephone, PDO::PARAM_STR);
            $enregistrement->bindParam(':email', $email, PDO::PARAM_STR);
            $enregistrement->bindParam(':civilite', $civilite, PDO::PARAM_STR);
            $enregistrement->execute();
            header('location:gestion_membre.php');
        }
    }
}

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Gestion des membres <i class="fas fa-user"></i></h1>
        <p class="lead text-center">
            <hr><?php echo $msg; ?>
        </p>
    </div>
    <div class="my-3 d-flex flex-row-reverse">
        <a href="?action=creation" class="btn">Créer un nouvel administrateur</a>
    </div>
    <!-- Création nouvel admin -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'creation') { ?>
        <div class="row mt-3 p-5 border border-3 border-primary">
            <form method="POST" class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="civilite" class="form-label">Civilite <i class="fas fa-user-alt"></i></label>
                        <select class="form-control" id="civilite" name="civilite">
                            <option value="f">Madame</option>
                            <option value="m" <?php if ($civilite == 'm') {
                                                    echo 'selected';
                                                }  ?>>Monsieur</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom <i class="fas fa-user-alt"></i></label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo $nom ?>">
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prenom <i class="fas fa-user-alt"></i></label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo $prenom ?>">
                    </div>
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone <i class="fas fa-user-alt"></i></label>
                        <input type="text" class="form-control" id="telephone" name="telephone" value="<?php echo $telephone ?>">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email <i class="fas fa-at"></i></label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $email ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo <i class="fas fa-user-alt"></i></label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" minlength="6" required value="<?php echo $pseudo ?>">
                    </div>
                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe <i class="fas fa-key"></i></label>
                        <input type="password" class="form-control" id="mdp" name="mdp">
                    </div>
                    <div class="mb-3 mt-4">
                        <button type="submit" class="btn btn-outline-secondary w-100" id="enregistrer" name="enregistrer"><i class="fas fa-keyboard"></i> Inscription <i class="fas fa-keyboard"></i></button>
                    </div>
                </div>
            </form>
        </div>
    <?php } ?>
    <!-- Modification du statut du membre -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'modifier') { ?>
        <div class="row mt-3 p-5 border border-3 border-primary">
            <div>
                <p class="text-center">Attention : Vous ne pouvez modifier que le statut du membre.
                <ul class="text-center">
                    <li class="list-group-item list-group-item-info">1 = utilisateur</li>
                    <li class="list-group-item list-group-item-info">2 = administrateur</li>
                </ul>
                </p>
            </div>
            <form class="row border p-3" method="post" enctype="multipart/form-data">
                <table class="display table table-bordered">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>pseudo</th>
                            <th class="d-none">mdp</th>
                            <th>Nom</th>
                            <th>Prenom</th>
                            <th>Téléphone</th>
                            <th>E-mail</th>
                            <th>Civilité</th>
                            <th>Statut</th>
                            <th class="d-none">adresse</th>
                            <th class="d-none">cp</th>
                            <th class="d-none">ville</th>
                            <th class="d-none">pays</th>
                            <th>Date</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    while ($membre = $modifMembre->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        foreach ($membre as $indice => $valeur) {
                            if ($indice == 'mdp' || $indice == 'adresse' || $indice == 'cp' || $indice == 'ville' || $indice == 'pays') {
                                echo '<td class="d-none"></td>';
                            } elseif ($indice == 'statut') {
                                echo '
                                <td>
                                <input type="text" class="form-control" id="statut" name="statut" value="' . $statut . ' ">
                                </td>';
                            } else {
                                echo '<td>' . $valeur . '</td>';
                            }
                        }
                        echo '<td><button type="submit" class="btn" id="enregistrer" name="enregistrer">Mettre à jour</button>';
                        echo '</tr>';
                    }
                } ?>
                    </tbody>

                </table>
            </form>


        </div>

        </div>


        <div class="mt-3">
            <table id="gestionMembre" class="display table table-bordered">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>ID</th>
                        <th>pseudo</th>
                        <th class="d-none">mdp</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Téléphone</th>
                        <th>E-mail</th>
                        <th>Civilité</th>
                        <th>Statut</th>
                        <th class="d-none">adresse</th>
                        <th class="d-none">cp</th>
                        <th class="d-none">ville</th>
                        <th class="d-none">pays</th>
                        <th>Date</th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($membre = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        foreach ($membre as $indice => $valeur) {
                            if ($indice == 'mdp' || $indice == 'adresse' || $indice == 'cp' || $indice == 'ville' || $indice == 'pays') {
                                echo '<td class="d-none"></td>';
                            } else {
                                echo '<td>' . $valeur . '</td>';
                            }
                        }

                        // rajout de 2 liens pour modif et suppression
                        echo '<td><a href= "?action=modifier&id_membre=' . $membre['id_membre'] . '" class="btn btn-warning"><i class="far fa-edit"></i></td>';
                        echo '<td><a href= "?action=supprimer&id_membre=' . $membre['id_membre'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cet utilisateur ?\'))"><i class="far fa-trash-alt"></i></td>';
                        echo '</tr>';
                    }

                    ?>
                </tbody>
            </table>
        </div>

</main>

<?php
include '../inc/footer.inc.php';
