<?php
function accediDb() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "quiz";
    $conn = new mysqli($servername, $username, $password,$database);

    return $conn;
}