<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';


// ------ VALID USER : ADMIN------------
//--------------------------------------
//--------------------------------------
if (user_is_admin() == false) {
    header('location:../connexion.php');
}
//--------------------------------------
//--------------------------------------
//--------------------------------------

//-------------SUPPRIMER ANNONCE------------
//--------------------------------------
//--------------------------------------

if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_annonce'])) {
    // si l'indice action existe dans $_GET et si sa valeur est égal à supprimmer && et si id_article existe et n'est pas vide dans $_GET
    // Requete delete basée sur l'id_article pour supprimer l'article  en question.
    $suppression = $pdo->prepare("DELETE FROM annonce WHERE id_annonce = :id_annonce"); // preparer la requete
    $suppression->bindParam(':id_annonce', $_GET['id_annonce'], PDO::PARAM_STR); // selectionner la cible de la requete
    $suppression->execute(); // executer la requete 
}
//--------------------------------------
//--------------------------------------
//--------------------------------------

//------RECUPERATION ANNONCE-------------
//--------------------------------------
//--------------------------------------
$liste_annonce = $pdo->query("SELECT * FROM annonce ORDER BY  titre");
//--------------------------------------
//--------------------------------------
//--------------------------------------


include '../inc/header.inc.php'; 
include '../inc/nav.inc.php';
        
?>
        <main class="container">
            <br>
            <br>
            <br>
            <div class="star p-5 rounded text-center">
            <br>
                <h1><i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i> gestion annonces <i class="fab fa-rebel text-danger fa-2x faa-burst animated-hover"></i></h1>
                <p class="lead episode" style="color: yellow;">You are admin, that's mean unlimited power!<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
        <table class="table border rounded text-center bg-secondary">
            <thead class="star sw text-white  border  border">
                <tr>
                    <th>id</th>
                    <th>Titre</th>
                    <th>Description longue</th>
                    <th>Description courte</th>
                    <th>Prix</th>
                    <th>Photo</th>
                    <th>Pays</th>
                    <th>Ville</th>
                    <th>Adresse</th>
                    <th>CP</th>
                    <th>Membre</th>
                    <th>Catégorie</th>
                    <th>Date enregistrement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php

                while ($annonce = $liste_annonce->fetch(PDO::FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $annonce['id_annonce'] ?></td>
                        <td><?php echo $annonce['titre'] ?></td>
                        <td><?php echo $annonce['description_longue'] ?></td>
                        <td><?php echo $annonce['description_courte'] ?></td>
                        <td><?php echo $annonce['prix'] ?></td>
                        <?php foreach ($annonce as $indice => $valeur) {
                            if ($indice == 'photo') {
                                echo '<td><img src="' . URL . 'assets/img_annonce/' . $valeur . '" width="100" class="img-fluid" alt="image produit"></td>';
                            }
                        } ?>

                        <td><?php echo $annonce['pays'] ?></td>
                        <td><?php echo $annonce['ville'] ?></td>
                        <td><?php echo $annonce['adresse'] ?></td>
                        <td><?php echo $annonce['cp'] ?></td>
                                                   <!-- AJOUTER LE PRENOM ET NOM DE LA PERSONNE QUI A POSTE L'ANNONCE AVEC SA REQUETE EN JOINTURE  -->
                                                   <?php $req_membre= $pdo->query('SELECT membre.pseudo FROM membre membre, annonce annonce WHERE  annonce.membre_id = membre.id_membre');
                               ?>
                                <td><?php while( $membreinfo = $req_membre->fetch(PDO::FETCH_ASSOC)) {
                                        // foreach($titre_categorie as $indice => $info){
                                    echo $membreinfo['pseudo'] ;
                                   }?></td>
                                <td><?php echo $annonce['membre_id']?></td>
                              <!-- AJOUTER LE CHAMPS CATEGORIE AVEC SA REQUETE EN JOINTURE  -->
                               <?php $titre_categorie= $pdo->query('SELECT categorie.titre FROM categorie categorie, annonce annonce WHERE  annonce.categorie_id = categorie.id_categorie');
                               ?>
                                <td><?php while( $titre = $titre_categorie->fetch(PDO::FETCH_ASSOC)) {
                                        // foreach($titre_categorie as $indice => $info){
                                    echo $titre['titre'];
                                   }?></td>
                               <td><?php echo $annonce['date_enregistrement']?></td>
                     
                        <!-- Modification et suppression des annonces -->
                        <td><a href="?action=supprimer&id_annonce=<?php echo $annonce['id_annonce'] ?>" class="btn btn-danger" onclick="return (confirm('Etes vous sûr ?'))"><i class="far fa-trash-alt"></i></a></td>
                    </tr>';

                <?php } ?>
            </tbody>
        </table>
      
    </div>
    </div>



        </main>
<?php
include '../inc/footer.inc.php';  