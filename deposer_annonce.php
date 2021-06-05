<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

$titre = '';
$categorie = '';
$desc_courte = '';
$desc_longue = '';
$prix = '';
$photo1 =  '';
$photo2 =  '';
$photo3 =  '';
$photo4 =  '';
$photo5 =  '';
$pays = '';
$ville = '';
$adresse = '';
$cp = '';
$lieu = '';

$liste_categorie = $pdo->query("SELECT DISTINCT titre FROM categorie ORDER BY titre");





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
                            <label for="nom" class="form-label"><i class="far fa-keyboard fa-2"></i></label>
                            <input type="text" class="form-control" id="titre" name="titre" value="<?php echo $titre ?>">
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" >
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen2" aria-expanded="false" aria-controls="panelsStayOpen2">
                            Choisissez votre catégorie
                        </button>
                    </h2>
                    <div id="panelsStayOpen2" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen2">
                        <div class="accordion-body">
                            <select class="form-control" id="categorie" name="categorie">
                                <?php while ($categorie = $liste_categorie->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<option value="' . $categorie['titre'] . '">' . $categorie['titre'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" >
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
                    <h2 class="accordion-header" >
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen4" aria-expanded="false" aria-controls="panelsStayOpen4">
                            Description
                        </button>
                    </h2>
                    <div id="panelsStayOpen4" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen4">
                        <div class="accordion-body">                            
                            <textarea class="form-control" id="description" name="description" placeholder="Entrez votre texte" rows="4"><?php echo $desc_longue; ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" >
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
                    <h2 class="accordion-header" >
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen6" aria-expanded="false" aria-controls="panelsStayOpen6">
                           L'article est-il visible à votre domicile ?
                        </button>
                    </h2>
                    <div id="panelsStayOpen6" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen6">
                        <div class="accordion-body">                            
                        <select class="form-control" id="lieu" name="lieu">
                        <option value="oui">Oui</option>
                        <option value="non"<?php if ($lieu == 'non') { echo 'selected'; }?>>Non</option>                                           
                        </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php
include 'inc/footer.inc.php';
