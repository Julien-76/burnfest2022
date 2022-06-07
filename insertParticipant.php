<?php
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

global $conn;
    $query = "INSERT INTO burnfest2022 (NOM, PRENOM, SUZE, PICON, DIMITRI, CAMPING) VALUES (".$nom .", ". $prenom . ", " . $suze . ", ". $picon .", ". $dimitri .", ". $camping")"
    $response = mysqli_query($conn, $query);
    
    if(mysqli_query($conn, $query)) {
        echo json_encode(true);
    } else {
        echo json_encode(false);
    }