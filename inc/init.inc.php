<?php

//CONNEXION BDD LOCAL

$bdd = new PDO('mysql:host=localhost;dbname=boutique', 'root', '', array
(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

//CONNEXION BDD EN LIGNE
// $bdd = new PDO('mysql:host=sql200.epizy.com;dbname=epiz_27185136_boutique', 'epiz_27185136', '0bNvmY2RKB', array
// (PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// SESSION

session_start();

// CONSTANTES (chemin)

define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/PHP/09-boutique/'); //EN LOCAL

// define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/'); // EN LIGNE

// $_SERVER['DOCUMENT_ROOT'] --> c:/xampp/htdocs
// echo RACINE_SITE . '<hr>' ; // C:/xampp/htdocs/PHP/09-boutique
// cette constante retourne le chemin physique du dossier 09-boutique sur le serveur local xampp.
//Lors de l'enregistrement d'une image/photo, nous aurons du chemin physique complet vers le dossier photo sur le serveur pour enregistrer la photo dans le bon dossier (par ex)
// On appelle $_SERVER['DOCUMENT_ROOT'] parce que chaque serveur dispose 

define("URL", "http://localhost/PHP/09-boutique/"); //EN LOCAL

// define("URL", "http://mon-projet-2020.freecluster.eu/"); // EN LIGNE

// cette constante servira à enregistrer l'URL d'une image/photo dans la BDD

// INCLUSION
// En appellant init.inc sur chaque fichier on inclus en même temps les fonctions déclarées
require_once('fonctions.inc.php');