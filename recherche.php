<?php
include 'inc/init.inc.php';
include 'inc/functions.inc.php';

$recherche=$_GET['myInputValue'];
$recherche=htmlspecialchars($recherche);
$stmt = $pdo->query("SELECT description_longue FROM annonce WHERE description_longue LIKE '%".$recherche."%' ");
$suggestion = $stmt->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($suggestion);



