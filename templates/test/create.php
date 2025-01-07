<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

$_SESSION['current_page'] = "test";

$ruolo = "prof";

if (isset($_POST['create'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $titolo = $conn->real_escape_string($_POST['titolo']);
    $descrizione = $conn->real_escape_string($_POST['descrizione']);

    $sql = "INSERT INTO test (id, titolo, descrizione) VALUES
        (null, '$titolo', '$descrizione', )";

    try {
        $result = $conn->query($sql);

        header("Location: index.php");
    
        die();
    } catch (mysqli_sql_exception $err) {
        echo $err->getMessage();
        echo $err->getSqlState();
    }
}