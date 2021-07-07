<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {
    session_destroy();
    header('location:index.php');
}
// range
$range = $pdo->query("SELECT prix FROM annonce ORDER BY prix DESC LIMIT 1");
$rangeMax = $range->fetch(PDO::FETCH_ASSOC);
settype($rangeMax['prix'], "int");
settype($_GET['prix'], "int");



// Affichage annonce 
$annonce = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce ORDER BY id_annonce DESC LIMIT 20");
$annonce1 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce ORDER BY id_annonce ASC LIMIT 20");
$annonce2 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce ORDER BY prix ASC LIMIT 20");
$annonce3 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce ORDER BY prix DESC LIMIT 20");

$liste_membre = $pdo->query("SELECT DISTINCT pseudo FROM membre ORDER BY pseudo");
$liste_categorie = $pdo->query("SELECT DISTINCT categorie FROM categorie ORDER BY categorie");

$recupCategorie = $pdo->prepare("SELECT id_categorie AS categ FROM categorie WHERE categorie = :categorie");
$recupCategorie->bindParam(':categorie', $_GET["categorie"], PDO::PARAM_STR);
$recupCategorie->execute();
$categ = $recupCategorie->fetch(PDO::FETCH_ASSOC);


    if (isset($_GET['categorie']) && $_GET['categorie'] != 'tous') {
        if ($_GET['region'] != 'tous' && $_GET['membre'] == 'tous') {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce3->execute();
           
        } elseif ($_GET['region'] != 'tous' && $_GET['membre'] != 'tous') {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce1->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce2->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce3->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce3->execute();
            
        } elseif ($_GET['region'] != 'tous' && $_GET['membre'] != 'tous') {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);            
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce1->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce2->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' AND SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo)  ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce3->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce3->execute();
            
        } else {
            $annonce = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' ORDER BY id_annonce DESC LIMIT 20");
            $annonce1 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' ORDER BY id_annonce ASC LIMIT 20");
            $annonce2 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' ORDER BY prix ASC LIMIT 20");
            $annonce3 = $pdo->query("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE categorie_id = '" . $categ['categ'] . "' ORDER BY prix DESC LIMIT 20");
            
        }
    }


    if (isset($_GET['region']) && $_GET['region'] != 'tous') {
        if ($_GET['membre'] != 'tous' && $_GET['categorie'] == 'tous') {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce1->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce2->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) AND membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce3->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce3->execute();
            
        } else {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE SUBSTR(cp, 1, 2) IN (SELECT num_dep AS cp FROM departements_region WHERE region = :region) ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':region', $_GET['region'], PDO::PARAM_STR);
            $annonce3->execute();
            
        }
    }

    if (isset($_GET['membre']) && $_GET['membre'] != 'tous') {
        if ($_GET['categorie'] != 'tous' && $_GET['region'] == 'tous') {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) AND categorie_id IN (SELECT id_categorie FROM categorie WHERE categorie = :categorie) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
            $annonce->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) AND categorie_id IN (SELECT id_categorie FROM categorie WHERE categorie = :categorie) ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
            $annonce1->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) AND categorie_id IN (SELECT id_categorie FROM categorie WHERE categorie = :categorie) ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
            $annonce2->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) AND categorie_id IN (SELECT id_categorie FROM categorie WHERE categorie = :categorie) ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':categorie', $_GET['categorie'], PDO::PARAM_STR);
            $annonce3->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce3->execute();
            
        } else {
            $annonce = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce DESC LIMIT 20");
            $annonce->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce->execute();
            $annonce1 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY id_annonce ASC LIMIT 20");
            $annonce1->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce1->execute();
            $annonce2 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY prix ASC LIMIT 20");
            $annonce2->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce2->execute();
            $annonce3 = $pdo->prepare("SELECT photo, titre, description_courte AS description, prix, id_annonce, membre_id FROM annonce WHERE membre_id IN (SELECT id_membre FROM membre WHERE pseudo = :pseudo) ORDER BY prix DESC LIMIT 20");
            $annonce3->bindParam(':pseudo', $_GET['membre'], PDO::PARAM_STR);
            $annonce3->execute();
            
        }
    }
    if ($annonce->rowCount() < 1 || $annonce2->rowCount() < 1 || $annonce3->rowCount() < 1) {
        $msg .= '<div class="alert alert-danger mb-3">Aucune annonce ne correspond à votre recherche</div>';
    }


include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Bienvenue sur Swap <img src="<?php echo URL;?>assets/img/logo.png" alt="logo swap" width="5%"></h1>
        <p class="lead text-center">La nouvelle plateforme de vente entre particuliers
            <hr>
        </p>
    </div>

    <div class="row">
        <div class="col-lg-4 col-sm-12 mt-5">
            <form method="GET" action="#">
                <div class="me-5">
                    <label for="triCateg">Catégorie</label>
                    <select name="categorie" id="triCateg" class="form-select" aria-label="TriParCateg">
                        <option value="tous" selected>Toutes les catégories</option>
                        <?php while ($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
                            $categorie = $categorie['categorie'];
                            echo '<option value="' . $categorie . '">' . $categorie . '</option>';
                        }
                        ?>
                    </select>
                    <hr>
                </div>
                <div class="me-5">
                    <label for="triRegion">Region</label>
                    <select name="region" id="triRegion" class="form-select" aria-label="TriParRegion">
                        <option value="tous" selected>Toutes les régions</option>
                        <option value="Auvergne-Rhône-Alpes">Auvergne-Rhône-Alpes</option>
                        <option value="Bourgogne-Franche-Comté">Bourgogne-Franche-Comté</option>
                        <option value="Bretagne">Bretagne</option>
                        <option value="Centre-Val de Loire">Centre-Val de Loire</option>
                        <option value="Corse">Corse</option>
                        <option value="Grand Est">Grand Est</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guyane">Guyane</option>
                        <option value="Hauts-de-France">Hauts-de-France</option>
                        <option value="Île-de-France">Île-de-France</option>
                        <option value="La Réunion">La Réunion</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Normandie">Normandie</option>
                        <option value="Nouvelle-Aquitaine">Nouvelle-Aquitaine</option>
                        <option value="Occitanie">Occitanie</option>
                        <option value="Pays de la Loire">Pays de la Loire</option>
                        <option value="Provence-Alpes-Côte d'Azur">Provence-Alpes-Côte d'Azur</option>
                    </select>
                    <hr>
                </div>
                <div class="me-5">
                    <label for="triMembre">Membre</label>
                    <select name="membre" id="triMembre" class="form-select" aria-label="TriParMembre">
                        <option value="tous" selected>Tous les membres</option>
                        <?php while ($membreListe = $liste_membre->fetch(PDO::FETCH_ASSOC)) {
                            $membreListe = $membreListe['pseudo'];
                            echo '<option value="' . $membreListe . '">' . $membreListe . '</option>';
                        }
                        ?>
                    </select>
                    <hr>
                </div>
                <div id="rangePrice" class="me-5">
                    <label for="rangeIndex" class="form-label">Prix</label>
                    <input type="range" min="0" max="<?php echo $rangeMax['prix']; ?>" step="1" class="form-range" data-rangeslider name="prix">
                    <output></output>
                    <hr>
                </div>
                <div class="mt-3 me-5">
                    <button type="button submit" class="btn btn-primary">Valider</button>
                </div>
            </form>




        </div>
        <div class="col-lg-8 col-sm-12 mt-5">
            <div class="mb-5">
                <select id="triAnnonce" class="form-select" aria-label="Tri-Annonce">
                    <option value="0" selected>Trier par date (de la plus récente à la plus ancienne)</option>
                    <option value="1">Trier par date (de la plus ancienne à la plus récente)</option>
                    <option value="2">Trier par prix (du moins cher au plus cher)</option>
                    <option value="3">Trier par prix (du plus cher au moins cher)</option>
                </select>
                <hr>
            </div>
            <div id="tri0" class="mt-3 d-flex flex-column">
                <?php
                echo $msg;
                while ($liste_annonces = $annonce->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="row">';
                    foreach ($liste_annonces as $indice => $valeur) {
                        if ($indice == 'photo') {
                            echo '<div class="col-4"><a href="annonce.php?id_annonce=' . $liste_annonces['id_annonce'] . '"><img src="' . URL . 'assets/img/' . $valeur . '"  class="img-fluid" alt="image annonce"></a></div>';
                        } elseif ($indice == 'titre') {
                            echo '<div class="col-8"><h5>' . $valeur . '</h5>';
                        } elseif ($indice == 'prix') {
                            echo '<p class="text-end">' . $valeur . ' €</p>';
                        } elseif ($indice == 'description') {
                            echo '<p>' . $valeur . '...   <a href="annonce.php?id_annonce=' . $liste_annonces['id_annonce'] . '">lire la suite</a> </p>';
                        } elseif ($indice == 'membre_id') {
                            $recupUser = $pdo->query("SELECT pseudo, id_membre FROM membre, annonce WHERE id_membre = '" . $valeur . "'");
                            $donneesUser = $recupUser->fetch(PDO::FETCH_ASSOC);
                            $recupNote = $pdo->query("SELECT AVG(note) AS note FROM note WHERE membre_id1 IN
                    (SELECT id_membre FROM membre WHERE id_membre = '" . $valeur . "')");
                            $tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
                            settype($tabNote['note'], "int");
                            echo '<p>' . $donneesUser['pseudo'];
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
                            } else {
                                echo '<span> - <img src="assets/img/starVide.ico" alt="etoile" width="15"> Non noté(e)</span>';
                            }
                            echo '</p>';
                        }
                    }
                    echo '</div></div><hr>';
                }
                ?>
            </div>
            <div id="tri1" class="d-none mt-3 d-flex flex-column">
                <?php
                echo '';
                while ($liste_annonces1 = $annonce1->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="row">';
                    foreach ($liste_annonces1 as $indice => $valeur) {
                        if ($indice == 'photo') {
                            echo '<div class="col-4"><a href="annonce.php?id_annonce=' . $liste_annonces1['id_annonce'] . '"><img src="' . URL . 'assets/img/' . $valeur . '"  class="img-fluid" alt="image produit"></a></div>';
                        } elseif ($indice == 'titre') {
                            echo '<div class="col-8"><h5>' . $valeur . '</h5>';
                        } elseif ($indice == 'prix') {
                            echo '<p class="text-end">' . $valeur . ' €</p>';
                        } elseif ($indice == 'description') {
                            echo '<p>' . $valeur . '...   <a href="annonce.php?id_annonce=' . $liste_annonces1['id_annonce'] . '">lire la suite</a> </p>';
                        } elseif ($indice == 'membre_id') {
                            $recupUser = $pdo->query("SELECT pseudo, id_membre FROM membre, annonce WHERE id_membre = '" . $valeur . "'");
                            $donneesUser = $recupUser->fetch(PDO::FETCH_ASSOC);
                            $recupNote = $pdo->query("SELECT AVG(note) AS note FROM note WHERE membre_id1 IN
                            (SELECT id_membre FROM membre WHERE id_membre = '" . $valeur . "')");
                            $tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
                            settype($tabNote['note'], "int");
                            echo '<p>' . $donneesUser['pseudo'];
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
                            } else {
                                echo '<span> - <img src="assets/img/starVide.ico" alt="etoile" width="15"> Non noté(e)</span>';
                            }
                            echo '</p>';
                        }
                    }
                    echo '</div></div><hr>';
                }
                ?>
            </div>
            <div id="tri2" class="d-none mt-3 d-flex flex-column">
                <?php
                while ($liste_annonces2 = $annonce2->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="row">';
                    foreach ($liste_annonces2 as $indice => $valeur) {
                        if ($indice == 'photo') {
                            echo '<div class="col-4"><a href="annonce.php?id_annonce=' . $liste_annonces['id_annonce'] . '"><img src="' . URL . 'assets/img/' . $valeur . '"  class="img-fluid" alt="image produit"></a></div>';
                        } elseif ($indice == 'titre') {
                            echo '<div class="col-8"><h5>' . $valeur . '</h5>';
                        } elseif ($indice == 'prix') {
                            echo '<p class="text-end">' . $valeur . ' €</p>';
                        } elseif ($indice == 'description') {
                            echo '<p>' . $valeur . '...   <a href="annonce.php?id_annonce=' . $liste_annonces2['id_annonce'] . '">lire la suite</a> </p>';
                        } elseif ($indice == 'membre_id') {
                            $recupUser = $pdo->query("SELECT pseudo, id_membre FROM membre, annonce WHERE id_membre = '" . $valeur . "'");
                            $donneesUser = $recupUser->fetch(PDO::FETCH_ASSOC);
                            $recupNote = $pdo->query("SELECT AVG(note) AS note FROM note WHERE membre_id1 IN
                            (SELECT id_membre FROM membre WHERE id_membre = '" . $valeur . "')");
                            $tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
                            settype($tabNote['note'], "int");
                            echo '<p>' . $donneesUser['pseudo'];
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
                            } else {
                                echo '<span> - <img src="assets/img/starVide.ico" alt="etoile" width="15"> Non noté(e)</span>';
                            }
                            echo '</p>';
                        }
                    }
                    echo '</div></div><hr>';
                }
                ?>
            </div>
            <div id="tri3" class="d-none mt-3 d-flex flex-column">
                <?php
                while ($liste_annonces3 = $annonce3->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="row">';
                    foreach ($liste_annonces3 as $indice => $valeur) {
                        if ($indice == 'photo') {
                            echo '<div class="col-4"><a href="annonce.php?id_annonce=' . $liste_annonces3['id_annonce'] . '"><img src="' . URL . 'assets/img/' . $valeur . '"  class="img-fluid" alt="image produit"></a></div>';
                        } elseif ($indice == 'titre') {
                            echo '<div class="col-8"><h5>' . $valeur . '</h5>';
                        } elseif ($indice == 'prix') {
                            echo '<p class="text-end">' . $valeur . ' €</p>';
                        } elseif ($indice == 'description') {
                            echo '<p>' . $valeur . '...   <a href="annonce.php?id_annonce=' . $liste_annonces3['id_annonce'] . '">lire la suite</a> </p>';
                        } elseif ($indice == 'membre_id') {
                            $recupUser = $pdo->query("SELECT pseudo, id_membre FROM membre, annonce WHERE id_membre = '" . $valeur . "'");
                            $donneesUser = $recupUser->fetch(PDO::FETCH_ASSOC);
                            $recupNote = $pdo->query("SELECT AVG(note) AS note FROM note WHERE membre_id1 IN
                            (SELECT id_membre FROM membre WHERE id_membre = '" . $valeur . "')");
                            $tabNote = $recupNote->fetch(PDO::FETCH_ASSOC);
                            settype($tabNote['note'], "int");
                            echo '<p>' . $donneesUser['pseudo'];
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
                            } else {
                                echo '<span> - <img src="assets/img/starVide.ico" alt="etoile" width="15"> Non noté(e)</span>';
                            }
                            echo '</p>';
                        }
                    }
                    echo '</div></div><hr>';
                }


                ?>
            </div>
            <div id="test" style="display: none;">TESTS JSON</div>

        </div>
    </div>

</main>

<?php
include 'inc/footer.inc.php';
