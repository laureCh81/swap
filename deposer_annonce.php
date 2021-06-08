<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

if (user_connected() == false) {
    header('location:connexion.php?action=annonce');
}

$pseudo = $_SESSION['membre']['pseudo'];
$titre = '';
$categorie = '';
$desc_longue = '';
$desc_courte = '';
$prix = '';
$photo1 =  '';
$photo2 =  '';
$photo3 =  '';
$photo4 =  '';
$photo5 =  '';
$ville = '';
$adresse = '';
$cp = '';
$pays = '';


$liste_categorie = $pdo->query("SELECT DISTINCT titre FROM categorie ORDER BY titre");


if (isset($_POST['categorie']) && isset($_POST['titre']) && isset($_POST['desc_longue']) && isset($_POST['prix']) && isset($_POST['adresse']) && isset($_POST['cp']) && isset($_POST['ville']) && isset($_POST['pays'])) {

    $categorie = trim($_POST['categorie']);
    $titre = trim($_POST['titre']);
    $desc_longue = trim($_POST['desc_longue']);
    $prix = trim($_POST['prix']);
    $adresse = trim($_POST['adresse']);
    $cp = trim($_POST['cp']);
    $ville = trim($_POST['ville']);
    $pays = trim($_POST['pays']);


    $erreur = false;

    //picture

    $tab_extension = array('jpg', 'jpeg', 'gif', 'png', 'webp');

    if (!empty($_FILES['photo1']['name'])) {
        $photo1 = date("Y-m-d H:i:s") . '-' . $_FILES['photo1']['name'];
        $extension = substr(strrchr($photo1, '.'), 1);
        $extension = strtolower($extension);
        if (in_array($extension, $tab_extension)) {
            $photo1 = str_replace(' ', '-', $photo1);
            $photo1 = preg_replace('#[^A-Za-z0-9.\-]#', '', $photo1);
            copy($_FILES['photo1']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img/' . $photo1);
        } else {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention! Format invalide. Les formats attendus sont jpg, jpeg, gif, png ou webp.</div>';
        }
    }
    if (!empty($_FILES['photo2']['name'])) {
        $photo2 = date("Y-m-d H:i:s") . '-' . $_FILES['photo2']['name'];
        $extension = substr(strrchr($photo2, '.'), 1);
        $extension = strtolower($extension);
        if (in_array($extension, $tab_extension)) {
            $photo2 = str_replace(' ', '-', $photo2);
            $photo2 = preg_replace('#[^A-Za-z0-9.\-]#', '', $photo2);
            copy($_FILES['photo2']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img/' . $photo2);
        } else {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention! Format invalide. Les formats attendus sont jpg, jpeg, gif, png ou webp.</div>';
        }
    }
    if (!empty($_FILES['photo3']['name'])) {
        $photo3 = date("Y-m-d H:i:s") . '-' . $_FILES['photo3']['name'];
        $extension = substr(strrchr($photo3, '.'), 1);
        $extension = strtolower($extension);
        if (in_array($extension, $tab_extension)) {
            $photo3 = str_replace(' ', '-', $photo3);
            $photo3 = preg_replace('#[^A-Za-z0-9.\-]#', '', $photo3);
            copy($_FILES['photo3']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img/' . $photo3);
        } else {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention! Format invalide. Les formats attendus sont jpg, jpeg, gif, png ou webp.</div>';
        }
    }
    if (!empty($_FILES['photo4']['name'])) {
        $photo4 = date("Y-m-d H:i:s") . '-' . $_FILES['photo4']['name'];
        $extension = substr(strrchr($photo4, '.'), 1);
        $extension = strtolower($extension);
        if (in_array($extension, $tab_extension)) {
            $photo4 = str_replace(' ', '-', $photo4);
            $photo4 = preg_replace('#[^A-Za-z0-9.\-]#', '', $photo4);
            copy($_FILES['photo4']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img/' . $photo4);
        } else {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention! Format invalide. Les formats attendus sont jpg, jpeg, gif, png ou webp.</div>';
        }
    }
    if (!empty($_FILES['photo5']['name'])) {
        $photo5 = date("Y-m-d H:i:s") . '-' . $_FILES['photo5']['name'];
        $extension = substr(strrchr($photo5, '.'), 1);
        $extension = strtolower($extension);
        if (in_array($extension, $tab_extension)) {
            $photo5 = str_replace(' ', '-', $photo5);
            $photo5 = preg_replace('#[^A-Za-z0-9.\-]#', '', $photo5);
            copy($_FILES['photo5']['tmp_name'], ROOT_PATH . PROJECT_PATH . 'assets/img/' . $photo5);
        } else {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention! Format invalide. Les formats attendus sont jpg, jpeg, gif, png ou webp.</div>';
        }
    }
    //price
    if (!is_numeric($prix)) {
        $prix = 0;
        $msg .= '<div class="alert alert-danger mb-3">Attention! Le prix doit être renseigné .</div>';
    }
    //description
    if (iconv_strlen($desc_longue) < 10) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Attention la description est trop courte.</div>';
    }
    // titre
    if (iconv_strlen($titre) < 5) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Merci de bien vouloir préciser le titre</div>';
    }
    //cp format
    $verif_cp = preg_match('$((0[1-9])|([1-8][0-9])|(9[0-8])|(2A)|(2B))[0-9]{3}$', $cp);
    if ($verif_cp == false) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Veuillez renseigner un code postal valide svp</div>';
    }

    // annonce register;
    if ($erreur == false) {
        //photos : table photo
        $photo1_register = $pdo->prepare("INSERT INTO photo (photo1, photo2, photo3, photo4, photo5) VALUES (:photo1, NULL, NULL, NULL, NULL)");
        $photo1_register->bindParam(':photo1', $photo1, PDO::PARAM_STR);
        $photo1_register->execute();
        $lastInsertID = $pdo->lastInsertId();

        if (!empty($_FILES['photo2']['name'])) {
            $photo2_register = $pdo->prepare("UPDATE photo SET photo2 = :photo2 WHERE id_photo = '" . $lastInsertID . "'");
            $photo2_register->bindParam(':photo2', $photo2, PDO::PARAM_STR);
            $photo2_register->execute();
        }
        if (!empty($_FILES['photo3']['name'])) {
            $photo3_register = $pdo->prepare("UPDATE photo SET photo3 = :photo3 WHERE id_photo = '" . $lastInsertID . "'");
            $photo3_register->bindParam(':photo3', $photo3, PDO::PARAM_STR);
            $photo3_register->execute();
        }
        if (!empty($_FILES['photo4']['name'])) {
            $photo4_register = $pdo->prepare("UPDATE photo SET photo4 = :photo4 WHERE id_photo = '" . $lastInsertID . "'");
            $photo4_register->bindParam(':photo4', $photo4, PDO::PARAM_STR);
            $photo4_register->execute();
        }
        if (!empty($_FILES['photo5']['name'])) {
            $photo5_register = $pdo->prepare("UPDATE photo SET photo5 = :photo5 WHERE id_photo = '" . $lastInsertID . "'");
            $photo5_register->bindParam(':photo5', $photo2, PDO::PARAM_STR);
            $photo5_register->execute();
        }

        $recupPhoto = $pdo->query("SELECT photo1 AS photo1 FROM photo WHERE id_photo = '" . $lastInsertID . "'");
        $photo_principale = $recupPhoto->fetch(PDO::FETCH_ASSOC);

        $recupMembre = $pdo->query("SELECT id_membre as membre FROM membre WHERE pseudo = '" . $pseudo . "'");
        $membre = $recupMembre->fetch(PDO::FETCH_ASSOC);

        $desc_courte = substr($desc_longue, 0, 25);

        $recupCategorie = $pdo->prepare("SELECT id_categorie AS categ FROM categorie WHERE titre = :categorie");
        $recupCategorie->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $recupCategorie->execute();
        $idCategorie = $recupCategorie->fetch(PDO::FETCH_ASSOC);
        $idCategorie = $idCategorie['categ'];


        $enregistrement = $pdo->prepare("INSERT INTO annonce (titre, description_courte, description_longue, prix, photo, adresse, cp, ville, pays, membre_id, photo_id, categorie_id) VALUES (:titre, '" . $desc_courte . "', :desc_longue, :prix, '" . $photo_principale['photo1'] . "', :adresse, :cp, :ville, :pays, '" . $membre['membre'] . "', '" . $lastInsertID . "', '" . $idCategorie . "')");
        $enregistrement->bindParam(':titre', $titre, PDO::PARAM_STR);
        $enregistrement->bindParam(':desc_longue', $desc_longue, PDO::PARAM_STR);
        $enregistrement->bindParam(':prix', $prix, PDO::PARAM_STR);
        $enregistrement->bindParam(':adresse', $adresse, PDO::PARAM_STR);
        $enregistrement->bindParam(':cp', $cp, PDO::PARAM_STR);
        $enregistrement->bindParam(':ville', $ville, PDO::PARAM_STR);
        $enregistrement->bindParam(':pays', $pays, PDO::PARAM_STR);
        $enregistrement->execute();

        header('location:profil.php');
    }
}




include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Déposer une annonce <i class="fas fa-bullhorn"></i></h1>
        <p class="lead text-center">Un titre précis et la bonne catégorie, c'est le meilleur moyen pour que vos futurs acheteurs voient votre annonce !
        </p>
        <hr><?php echo $msg; ?>
    </div>

    <div class="row p-5">
        <form class="row border p-3" method="post" enctype="multipart/form-data">
            <div class="accordion" id="accordionOpen">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen1" aria-expanded="true" aria-controls="panelsStayOpen1">
                            Quel est le titre de votre annonce ?
                        </button>
                    </h2>
                    <div id="panelsStayOpen1" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen">
                        <div class="accordion-body">
                            <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $titre ?>">
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen2" aria-expanded="false" aria-controls="panelsStayOpen2">
                            Choisissez votre catégorie
                        </button>
                    </h2>
                    <div id="panelsStayOpen2" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen2">
                        <div class="accordion-body">
                            <select class="form-control" id="categorie" name="categorie">
                                <?php while ($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
                                    $categorie = $categorie['titre'];
                                    echo '<option value="' . $categorie . '">' . $categorie . '</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen3" aria-expanded="false" aria-controls="panelsStayOpen3">
                            Ajouter des photos (5 maximum)
                        </button>
                    </h2>
                    <div id="panelsStayOpen3" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen3">
                        <div class="accordion-body">
                            <div class="d-flex justify-content-around">
                                <label id="uploadPhoto1" for="photo1" class="depot form-label"><img src="<?php echo URL; ?>assets/img/picture.png" alt="picture icon" style="width:10em;"></label>
                                <img id="preview1" class=" ms-5 d-none" alt="votre photo" style="width:10em;">
                                <input type="file" accept="image/*" class="form-control me-5 d-none" id="photo1" name="photo1" onchange="previewImage1(event)">

                                <label id="uploadPhoto2" for="photo2" class="depot form-label"><img src="<?php echo URL; ?>assets/img/picture.png" alt="picture icon" style="width:10em;"></label>
                                <img id="preview2" class=" ms-5 d-none" alt="votre photo" style="width:10em;">
                                <input type="file" accept="image/*" class="form-control me-5 d-none" id="photo2" name="photo2" onchange="previewImage2(event)">

                                <label id="uploadPhoto3" for="photo3" class="depot form-label"><img src="<?php echo URL; ?>assets/img/picture.png" alt="picture icon" style="width:10em;"></label>
                                <img id="preview3" class=" ms-5 d-none" alt="votre photo" style="width:10em;">
                                <input type="file" accept="image/*" class="form-control me-5 d-none" id="photo3" name="photo3" onchange="previewImage3(event)">

                                <label id="uploadPhoto4" for="photo4" class="depot form-label"><img src="<?php echo URL; ?>assets/img/picture.png" alt="picture icon" style="width:10em;"></label>
                                <img id="preview4" class=" ms-5 d-none" alt="votre photo" style="width:10em;">
                                <input type="file" accept="image/*" class="form-control me-5 d-none" id="photo4" name="photo4" onchange="previewImage4(event)">

                                <label id="uploadPhoto5" for="photo5" class="depot form-label"><img src="<?php echo URL; ?>assets/img/picture.png" alt="picture icon" style="width:10em;"></label>
                                <img id="preview5" class=" ms-5 d-none" alt="votre photo" style="width:10em;">
                                <input type="file" accept="image/*" class="form-control me-5 d-none" id="photo5" name="photo5" onchange="previewImage5(event)">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen4" aria-expanded="false" aria-controls="panelsStayOpen4">
                            Description
                        </button>
                    </h2>
                    <div id="panelsStayOpen4" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen4">
                        <div class="accordion-body">
                            <textarea class="form-control" id="desc_longue" name="desc_longue" placeholder="Entrez votre texte" rows="4"><?php echo $desc_longue; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen5" aria-expanded="false" aria-controls="panelsStayOpen5">
                            Prix (en Euro)
                        </button>
                    </h2>
                    <div id="panelsStayOpen5" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen5">
                        <div class="accordion-body">
                            <input type="text" class="form-control" id="prix" name="prix" value="<?php echo $prix; ?>">
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen6" aria-expanded="false" aria-controls="panelsStayOpen6">
                            Où se trouve votre bien ?
                        </button>
                    </h2>
                    <div id="panelsStayOpen6" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen6">

                        <div class="form-group col-12">
                            <label for="adresse">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $adresse; ?>">
                        </div>
                        <div class="form-group col-3">
                            <label for="cp">Code Postal</label>
                            <input type="text" class="form-control" id="cp" name="cp" value="<?php echo $cp; ?>">
                        </div>
                        <div class="form-group col-9">
                            <label for="ville">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" value="<?php echo $ville; ?>">
                        </div>
                        <div class="form-group col-12">
                            <label for="pays">Pays</label>
                            <input type="text" class="form-control" id="pays" name="pays" value="<?php echo $pays; ?>">
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <div class="d-flex justify-content-end me-5">
        <button type="submit" class="btn" id="enregistrer" name="enregistrer">Enregistrer</button>
    </div>


    </form>
    </div>
</main>

<?php
include 'inc/footer.inc.php';
