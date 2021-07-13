<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

if (user_admin() == false) {
    header('location:../index.php');
}


//affichage categories
$liste_categorie = $pdo->query("SELECT * from categorie");

// Ajouter une categorie
if (isset($_GET['action']) && $_GET['action'] == 'creation') {
    $categorie = '';
    $motcles = '';
    if (isset($_POST['categorie']) && isset($_POST['motcles'])) {
        $categorie = trim($_POST['categorie']);
        $motcles = trim($_POST['motcles']);
        $erreur = false;
        if (iconv_strlen($categorie) < 4 || iconv_strlen($motcles) < 4) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention les champs ne peuvent pas être vides et/ou la catégorie est invalide</div>';
        }
        $doubleCateg = $pdo->prepare("SELECT * FROM categorie WHERE categorie = :categorie");
        $doubleCateg->bindParam(':categorie', $categorie, PDO::PARAM_STR);
        $doubleCateg->execute();
        $double = $doubleCateg->fetch(PDO::FETCH_ASSOC);
        if ($doubleCateg->rowCount() > 0) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Cette catégorie existe déjà (id_' . $double['id_categorie'] . ')</div>';
        }

        // categorie update;
        if ($erreur == false) {
            $registerCategorie = $pdo->prepare("INSERT INTO categorie (categorie, motcles) VALUES (:categorie, :motcles)");
            $registerCategorie->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $registerCategorie->bindParam(':motcles', $motcles, PDO::PARAM_STR);
            $registerCategorie->execute();
        }
    }
}

//modif categorie
if (isset($_GET['action']) && $_GET['action'] == 'modifier') {

    $modifCategorie = $pdo->prepare("SELECT categorie, motcles FROM categorie WHERE id_categorie = :id_categorie");
    $modifCategorie->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
    $modifCategorie->execute();
    $categorieModif = $modifCategorie->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['categorie']) && isset($_POST['motcles'])) {
        $categorie = trim($_POST['categorie']);
        $motcles = trim($_POST['motcles']);
        $erreur = false;

        if (iconv_strlen($categorie < 4) || iconv_strlen($motcles < 4)) {
            $erreur = true;
            $msg .= '<div class="alert alert-danger mb-3">Attention les champs ne peuvent pas être vides et/ou la catégorie est invalide</div>';
        }

        // categorie update;
        if ($erreur == false) {
            $majCategorie = $pdo->prepare("UPDATE categorie SET categorie = :categorie, motcles = :motcles WHERE id_categorie = :id_categorie");
            $majCategorie->bindParam(':categorie', $categorie, PDO::PARAM_STR);
            $majCategorie->bindParam(':motcles', $motcles, PDO::PARAM_STR);
            $majCategorie->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
            $majCategorie->execute();
        }
        header('location:gestion_categorie.php');
    }
}

//Suppression catégorie
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_categorie'])) {
    $erreur = false;
    //categorie avec ou sans annonce
    $recupAnnonce = $pdo->prepare("SELECT id_annonce FROM annonce WHERE categorie_id = :id_categorie");
    $recupAnnonce->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
    $recupAnnonce->execute();
    if ($recupAnnonce->rowCount() > 0) {
        $erreur = true;
        $msg .= '<div class="alert alert-danger mb-3">Vous ne pouvez pas supprimer cette catégorie, des annonces en cours y sont rattachées.</div>';
    }
    if ($erreur == false) {
        $suppressionCategorie = $pdo->prepare("DELETE FROM categorie WHERE id_categorie = :id_categorie");
        $suppressionCategorie->bindParam(':id_categorie', $_GET['id_categorie'], PDO::PARAM_STR);
        $suppressionCategorie->execute();
    }
    header('location:gestion_categorie.php');
}



include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Gestion des catégories <i class="far fa-list-alt"></i></h1>
        <p class="lead text-center">
            <hr><?php echo $msg; ?>
        </p>
    </div>
    <div class="my-3 d-flex flex-row-reverse">
        <a href="?action=creation" class="btn">Ajouter une catégorie</a>
    </div>
    <!-- Création categorie -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'creation') { ?>
        <div class="row mt-3 p-5 border border-3 border-primary">
            <form class="row border p-3" method="post" enctype="multipart/form-data">
                <div>
                    <label for="categorie" class="form-label">Categorie</label>
                    <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo $categorie ?>">
                </div>
                <div>
                    <label for="motcle" class="form-label">Modifier la description</label>
                    <textarea class="form-control" id="motcles" name="motcles" rows="4"><?php echo $motcles; ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-3 me-5">
                    <button type="submit" class="btn" id="enregistrer" name="enregistrer">Enregistrer</button>
                </div>
            </form>
        </div>
    <?php } ?>
    <!-- Modifications des categorie -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'modifier') { ?>
        <div class="row mt-3 p-5 border border-3 border-primary">
            <form class="row border p-3" method="post" enctype="multipart/form-data">
                <div>
                    <label for="categorie" class="form-label">Categorie</label>
                    <input type="text" class="form-control" id="categorie" name="categorie" value="<?php echo $categorieModif['categorie'] ?>">
                </div>
                <div>
                    <label for="motcles" class="form-label">Modifier la description</label>
                    <textarea class="form-control" id="motcles" name="motcles" rows="4"><?php echo $categorieModif['motcles']; ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-3 me-5">
                    <button type="submit" class="btn" id="enregistrer" name="enregistrer">Mettre à jour</button>
                </div>
            </form>
        </div>
    <?php } ?>
    <div class="mt-4">
        <table id="gestionCategorie" class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Catégorie</th>
                    <th>Mots clé</th>
                    <th>Modif</th>
                    <th>Suppr</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($categ = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    foreach ($categ as $indice => $valeur) {
                        echo '<td>' . $valeur . '</td>';
                    }
                    echo '<td><a href= "?action=modifier&id_categorie=' . $categ['id_categorie'] . '" class="btn btn-warning"><i class="far fa-edit"></i></td>';
                    echo '<td><a href= "?action=supprimer&id_categorie=' . $categ['id_categorie'] . '" class="btn btn-danger" onclick="return(confirm(\'Seules les catégories sans annonce publiée peuvent être supprimer. Etes vous sûr de vouloir supprimer cette catégorie?\'))"><i class="far fa-trash-alt"></i></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

</main>

<?php
include '../inc/footer.inc.php';
