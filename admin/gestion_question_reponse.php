<?php
include '../inc/init.inc.php';
include '../inc/functions.inc.php';

if (user_admin() == false) {
    header('location:../index.php');
}

// affichage des Q/R

$recupQuestion = $pdo->query("SELECT id_question, question, membre_id, annonce_id, DATE_FORMAT(question.date_enregistrement, '%d/%m/%Y') AS dateQ, id_reponse, question_id, reponse, DATE_FORMAT(reponse.date_enregistrement, '%d/%m/%Y') AS dateR FROM question LEFT JOIN reponse ON id_question = question_id  ORDER BY id_question DESC");

// Suppression réponse
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_reponse'])) {
    $suppReponse = $pdo->prepare("DELETE FROM reponse WHERE id_reponse = :id_reponse ");
    $suppReponse->bindParam(':id_reponse', $_GET['id_reponse'], PDO::PARAM_STR);
    $suppReponse->execute();
}
// Suppression question
if (isset($_GET['action']) && $_GET['action'] == 'supprimer' && !empty($_GET['id_question'])) {
    $suppReponse = $pdo->prepare("DELETE FROM question WHERE id_question = :id_question ");
    $suppReponse->bindParam(':id_question', $_GET['id_question'], PDO::PARAM_STR);
    $suppReponse->execute();
}


include '../inc/header.inc.php';
include '../inc/nav.inc.php';
?>

<main class="container">

    <div class="bg-light p-5 rounded ">
        <h1 class="text-center">Gestion des questions / Réponses <i class="fas fa-question"></i></h1>
        <p class="lead text-center">Vous ne pouvez supprimer les Q/R que si la charte utilisateur n'est pas respectée (Diffamation, injures, discrimination etc... ) <?php echo $msg; ?></p>
    </div>

    <div class="mt-3">
        <table id="gestionQr" class="display table table-bordered">
            <thead class="table-light">
                <tr class="text-center">
                    <th>ID Question</th>
                    <th>Question</th>
                    <th>Qui pose la question ?</th>
                    <th>Annonce</th>
                    <th>Date question</th>
                    <th>ID réponse</th>
                    <th class="d-none">QuestionID</th>
                    <th>Réponse </th>
                    <th>Date de réponse</th>
                    <th>Supprimer la question</th>
                    <th>Supprimer la réponse</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($affichQr = $recupQuestion->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr class="text-center">';
                    foreach ($affichQr as $indice => $valeur) {
                        if ($indice == 'membre_id') {
                            $liste_membre = $pdo->query("SELECT pseudo FROM membre, annonce WHERE id_membre ='" . $valeur . "'");
                            $membre = $liste_membre->fetch(PDO::FETCH_ASSOC);
                            echo '<td>' . $valeur . ' - ' . $membre['pseudo'] . '</td>';
                        } elseif ($indice == 'question_id') {
                            echo '<td class="d-none"></td>';
                        } else {
                            echo '<td>' . $valeur . '</td>';
                        }
                    }
                    echo '<td><a href= "?action=supprimer&id_question=' . $affichQr['id_question'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cette question ?\'))"><i class="far fa-trash-alt"></i></td>';
                    if ($affichQr['id_reponse'] == '') {
                        echo '<td><a href=#' . $affichQr['id_reponse'] . '" class="btn btn-danger" onclick="return(confirm(\'Aucune réponse à supprimer \'))"><i class="far fa-trash-alt"></i></td>';
                    } else {
                        echo '<td><a href= "?action=supprimer&id_reponse=' . $affichQr['id_reponse'] . '" class="btn btn-danger" onclick="return(confirm(\'Etes vous sûr de vouloir supprimer cette réponse \'))"><i class="far fa-trash-alt"></i></td>';
                    }

                    echo '</tr>';
                } ?>
            </tbody>
        </table>
    </div>


</main>

<?php
include '../inc/footer.inc.php';
