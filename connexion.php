<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';
//disconnect


if (user_connected() == true) {
    header('location:profil.php');
}

$pseudo = '';



// connection
if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    $connexion = $pdo->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $connexion->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $connexion->execute();

    if ($connexion->rowCount() > 0) {
        $verif_mdp = $connexion->fetch(PDO::FETCH_ASSOC);
        if (password_verify($mdp, $verif_mdp['mdp'])) {
            $_SESSION['membre'] = array();
            $_SESSION['membre']['id_membre'] = $verif_mdp['id_membre'];
            $_SESSION['membre']['pseudo'] = $verif_mdp['pseudo'];
            $_SESSION['membre']['nom'] = $verif_mdp['nom'];
            $_SESSION['membre']['prenom'] = $verif_mdp['prenom'];
            $_SESSION['membre']['telephone'] = $verif_mdp['telephone'];
            $_SESSION['membre']['email'] = $verif_mdp['email'];
            $_SESSION['membre']['civilite'] = $verif_mdp['civilite'];
            $_SESSION['membre']['statut'] = $verif_mdp['statut'];
            $_SESSION['membre']['adresse'] = $verif_mdp['adresse'];
            $_SESSION['membre']['cp'] = $verif_mdp['cp'];
            $_SESSION['membre']['ville'] = $verif_mdp['ville'];
            $_SESSION['membre']['pays'] = $verif_mdp['pays'];
            $_SESSION['membre']['date_enregistrement'] = $verif_mdp['date_enregistrement'];

            if (isset($_GET['id_annonce'])) {
                header('location:annonce.php?id_annonce=' . $_GET['id_annonce']);
            } else {
                header('location:profil.php');
            }
        } else {
            $msg .= '<div class="alert alert-danger mb-3">Attention! Votre pseudo et/ou votre mot de passe est incorrect.</div>';
        }
    } else {

        $msg .= '<div class="alert alert-danger mb-3">Attention! pseudo et/ou le mot de passe incorrect.</div>';
    }
}

include 'inc/header.inc.php';
include 'inc/nav.inc.php';
?>

<main class="container ">
    <div class="bg-light p-5 rounded mb-5">
        <h1 class="text-center">Bonjour ! </h1>
        <p class="lead text-center"> Connectez-vous pour découvrir toutes nos fonctionnalités. <i class="fas fa-sign-in-alt"></i>
            <hr> <?php echo $msg ?>
        </p>
    </div>
    <form method="POST" class="connect row">
        <div class="row">
            <div class="col-lg-4 col-md-6 mx-auto">
                <div class="mb-3">
                    <label for="pseudo" class="form-label">Pseudo <i class="fas fa-user-alt"></i></label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?php echo $pseudo ?>">
                </div>
                <div class="mb-3">
                    <label for="mdp" class="form-label">Mot de passe <i class="fas fa-key"></i></label>
                    <input type="password" class="form-control" id="mdp" name="mdp">
                </div>
                <div class="mb-3 mt-4">
                    <button type="submit" class="btn btn-outline-secondary w-100" id="connexion" name="connexion"><i class="fas fa-keyboard"></i> Connexion <i class="fas fa-keyboard"></i></button>
                </div>
                <div class="mb-3 mt-4">
                    <p class="text-center">Envie de nous rejoindre ? <br>
                        <a href="<?php echo URL; ?>inscription.php">Créer un compte</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
</main>

<?php
include 'inc/footer.inc.php';
