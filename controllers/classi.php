<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../templates/login.php");
    
    die();
}

require "../include/db.php";
$conn = accediDb();

$result = $conn->query("SELECT * FROM classe");

$classi = "[";

while ($classe = $result->fetch_assoc()) {
    $classi .= "\"".$classe['classe']."\",";
}

if ($classi == "[") {
    $classi = "[]";
} else {
    $classi[strlen($classi) - 1] = "]";
}

echo $classi;
