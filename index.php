<?php 
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

if (isset($_GET['action']) && $_GET['action'] == 'deconnexion') {   
    session_destroy();    
    header('location:index.php');   
}

// Affichage annonce
$annonces = $pdo->query("SELECT * FROM annonce");
      


include 'inc/header.inc.php'; 
include 'inc/nav.inc.php';
?>

<main class="container">
           
            <div class="bg-light p-5 rounded ">
                <h1 class="text-center"><i class="fas fa-ghost indigo "></i> template <i class="fas fa-ghost indigo"></i></h1>
                <p class="lead text-center">Bienvenue sur Swap.<hr><?php echo $msg; ?></p>                
            </div>

            <div class="row">
                <div class="col-lg-4 col-sm-12 mt-5">
                blabla
                    
                </div>
                <div class="col-lg-8 col-sm-12 mt-5">
                  blabla  
                </div>
            </div>
        </main>

<?php 
include 'inc/footer.inc.php';