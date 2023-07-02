<?php

function db_connect()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecf_back";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        return null;
    }
}

// Appel de la fonction dbConnect pour établir la connexion
$conn = db_connect();
