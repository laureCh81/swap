<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

if (user_admin() == false) {
    header('location:../index.php');
}

$liste_note = $pdo->query("SELECT * FROM note");

include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Gestion des notes <i class="far fa-star"></i></h1>
        <p class="lead text-center">Vous ne pouvez supprimer les notes que si la charte utilisateur n'est pas respectée (Diffamation, injures, discrimination etc... )
            <hr><?php echo $msg; ?>
        </p>
    </div>

    <div class="mt-3">
        <table id="gestionNote" class="display table table-bordered">
            <thead class="table-light">
                <tr class="text-center">
                    <th>ID</th>
                    <th>Qui reçoit la note</th>
                    <th>Qui donne la note</th>
                    <th>Annonce</th>
                    <th>Note</th>
                    <th>Avis</th>
                    <th>Date</th>
                    <th>Supprimer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($note = $liste_note->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr class="text-center">';
                    foreach ($note as $indice => $valeur) {

                        if ($indice == 'note') {
                            if ($note['note'] == 1) {
                                echo '<td><p class="d-none>' . $valeur . '</p><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"></td>';
                            } elseif ($note['note'] == 2) {
                                echo '<td><p class="d-none>' . $valeur . '</p><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"></td>';
                            } elseif ($note['note'] == 3) {
                                echo '<td><p class="d-none>' . $valeur . '</p><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"></td>';
                            } elseif ($note['note'] == 4) {
                                echo '<td><p class="d-none>' . $valeur . '</p><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/starVide.ico" alt="etoile" width="15"></td>';
                            } elseif ($note['note'] == 5) {
                                echo '<td><p class="d-none>' . $valeur . '</p><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"><img src="../assets/img/star.ico" alt="etoile" width="15"></td>';
                            }
                        } elseif ($indice == 'membre_id1') {
                            $liste_membre = $pdo->query("SELECT pseudo FROM membre, note WHERE id_membre ='" . $valeur . "'");
                            $membre = $liste_membre->fetch(PDO::FETCH_ASSOC);
                            echo '<td>' . $valeur . ' - ' . $membre['pseudo'] . '</td>';
                        } elseif ($indice == 'membre_id2') {
                            $liste_membre = $pdo->query("SELECT pseudo FROM membre, note WHERE id_membre ='" . $valeur . "'");
                            $membre = $liste_membre->fetch(PDO::FETCH_ASSOC);
                            echo '<td>' . $valeur . ' - ' . $membre['pseudo'] . '</td>';
                        } else {
                            echo '<td>' . $valeur . '</td>';
                        }
                    }



                    echo '<td><a href= "?action=supprimer&id_note=' . $note['id_note'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cette note ?\'))"><i class="far fa-trash-alt"></i></td>';
                    echo '</tr>';
                }

                ?>
            </tbody>
        </table>
    </div>
</main>

<?php
include '../inc/footer.inc.php';
