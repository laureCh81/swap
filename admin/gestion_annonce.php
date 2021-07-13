<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

if (user_admin() == false) {
    header('location:../index.php');
}

//Affichage annonces
$liste_annonce = $pdo->query("SELECT * FROM annonce ORDER BY id_annonce DESC");

//modif annonce
if (isset($_GET['action']) && $_GET['action'] == 'modifier') {

    $modifAnnonce = $pdo->prepare("SELECT titre, description_longue AS desc_longue FROM annonce, categorie WHERE id_annonce = :id_annonce");
    $modifAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $modifAnnonce->execute();
    $annonceModif = $modifAnnonce->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['titre']) && isset($_POST['desc_longue'])) {
        $titre = trim($_POST['titre']);
        $desc_longue = trim($_POST['desc_longue']);

        $erreur = false;

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
        // annonce update;
        if ($erreur == false) {
            $majAnnonce = $pdo->prepare("UPDATE annonce SET titre = :titre, description_longue = :desc_longue WHERE id_annonce = :id_annonce");
            $majAnnonce->bindParam(':titre', $titre, PDO::PARAM_STR);
            $majAnnonce->bindParam(':desc_longue', $desc_longue, PDO::PARAM_STR);
            $majAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
            $majAnnonce->execute();
        }
        header('location:gestion_annonce.php');
    }
}

// Suppresssion des annonces 

if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_annonce'])) {
    $supprPhoto  = $pdo->prepare("SELECT id_photo FROM photo, annonce WHERE id_photo = photo_id AND id_annonce = :id_annonce");
    $supprPhoto->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $supprPhoto->execute();
    $delPhoto = $supprPhoto->fetch(PDO::FETCH_ASSOC);
    $photoSuppr  = $pdo->prepare("DELETE FROM photo WHERE id_photo = '" . $delPhoto['id_photo'] . "'");

    $suppressionAnnonce = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce");
    $suppressionAnnonce->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR);
    $suppressionAnnonce->execute();
    header('location:gestion_annonce.php');
}

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Gestion des annonces <i class="fas fa-bullhorn"></i></h1>
        <p class="lead text-center">
            <hr><?php echo $msg; ?>
        </p>
    </div>
    <!-- Modifications des annonces -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'modifier') { ?>
        <div class="row mt-3 p-5 border border-3 border-primary">
            <form class="row border p-3" method="post" enctype="multipart/form-data">
                <div>
                    <label for="titre" class="form-label">Modifier le titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $annonceModif['titre'] ?>">
                </div>
                <div>
                    <label for="desc_longue" class="form-label">Modifier la description</label>
                    <textarea class="form-control" id="desc_longue" name="desc_longue" rows="4"><?php echo $annonceModif['desc_longue']; ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-3 me-5">
                    <button type="submit" class="btn" id="enregistrer" name="enregistrer">Mettre à jour</button>
                </div>
            </form>
        </div>
    <?php } ?>
    <div class="mt-3">
        <table id="gestionAnnonce" class="display table table-bordered">
            <thead class="table-light">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Titre</th>
                    <th class="d-none">Description_courte</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Photo</th>
                    <th class="d-none">adresse</th>
                    <th>CP</th>
                    <th class="d-none">vile</th>
                    <th class="d-none">Pays</th>
                    <th>Membre </th>
                    <th class="d-none">photo_id</th>
                    <th>Categorie_id</th>
                    <th>Date</th>
                    <th>Modifier</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($annonce = $liste_annonce->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    foreach ($annonce as $indice => $valeur) {

                        if ($indice == 'photo') {
                            echo '<td><img src="' . URL . 'assets/img/' . $valeur . '" width="70" class="img-fluid" alt="image annonce"></td>';
                        } elseif ($indice == 'description_courte' || $indice == 'adresse' || $indice == 'ville' || $indice == 'pays' || $indice == 'photo_id') {
                            echo '<td class="d-none"></td>';
                        } elseif ($indice == 'membre_id') {
                            $liste_membre = $pdo->query("SELECT pseudo FROM membre, annonce WHERE id_membre ='" . $valeur . "'");
                            $membre = $liste_membre->fetch(PDO::FETCH_ASSOC);
                            echo '<td>' . $valeur . ' - ' . $membre['pseudo'] . '</td>';
                        } elseif ($indice == 'categorie_id') {
                            $liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM categorie, annonce WHERE id_categorie = '" . $valeur . "'");
                            $categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC);
                            echo '<td>' . $valeur . ' - ' . $categorie['categorie'] . '</td>';
                        } else {
                            echo '<td>' . $valeur . '</td>';
                        }
                    }


                    echo '<td><a href= "?action=modifier&id_annonce=' . $annonce['id_annonce'] . '" class="btn btn-warning" onclick="return(confirm(\'Seuls les titres et descriptions contenant des mots condamnés par la CNIL peuvent être modifiées. Etes vous sûr de vouloir modifier cette annonce ?\'))"><i class="far fa-edit"></i></td>';
                    echo '<td><a href= "?action=supprimer&id_annonce=' . $annonce['id_annonce'] . '" class="btn btn-danger" onclick="return(confirm(\'Les annonces ne peuvent être supprimées ! Sauf en cas de désinscription du vendeur ou de non respect de la charte des vendeurs. Etes vous sûr de vouloir supprimer cette annonce ?\'))"><i class="far fa-trash-alt"></i></td>';
                    echo '</tr>';
                }

                ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include '../inc/footer.inc.php';
