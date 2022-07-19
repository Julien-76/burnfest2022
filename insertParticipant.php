<?php

use Devscreencast\ResponseClass\JsonResponse;

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Methods: GET, OPTIONS");


  // Se connecter à la base de données
include("db_connect.php");

$prenom = $_GET["prenom"];
$nom = $_GET["nom"];
$suze = $_GET["suze"];
$picon = $_GET["picon"];
$dimitri = $_GET["dimitri"];
$camping = $_GET["camping"];
$saucisses = $_GET["saucisses"];

global $conn;
$query = "INSERT INTO burnfest2022 (NOM, PRENOM, SUZE, PICON, DIMITRI, CAMPING, BOUFFE) VALUES (\"" .$nom . "\", \"" .$prenom . "\", \"" .$suze . "\",\"" . $picon . "\", \"" . $dimitri . "\", \"" .$camping . "\", \"" .$saucisses . "\")";
    
if(mysqli_query($conn, $query)) {
  echo json_encode(true);
} else {
  echo json_encode(false);
  }