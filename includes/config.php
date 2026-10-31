<?php
// Configuration de la connexion MySQL (valeurs par défaut de XAMPP / WAMP)
$host     = "localhost";
$user     = "root";
$password = "";
$database = "sport_db";
$port     = 3306;

$conn = mysqli_connect($host, $user, $password, $database, $port);

if ($conn === false) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
