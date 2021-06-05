<?php

$host = 'mysql:host=localhost;dbname=swap'; 
$login = 'root';
$password = ''; // mdp
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, 
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' 
    );
$pdo = new PDO($host, $login, $password, $options);

$msg = '';

session_start();

define('URL', 'http://php/swap/'); 
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']); 
define('PROJECT_PATH', '/swap/'); 