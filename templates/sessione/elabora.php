<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

if (isset($_POST['invia']) && isset($_POST['id_sessione_test'])) {
    require "../../include/db.php";
    $conn = accediDb();
    
    $id_sessione_test = normalize($conn, $_POST['id_sessione_test']);
    $id_utente = $_SESSION['id_utente'];

    unset($_POST['invia']);
    unset($_POST['id_sessione_test']);

    foreach ($_POST as $key => $value) {
        $key = normalize($conn, $key);
        $value = normalize($conn, $value);

        if (is_array($value)) {
            $value = implode(",", $value);
        }

        $sql = "INSERT INTO risposta (id_domanda, id_studente, id_sessione_test, risposta) VALUES ('$key', '$id_utente', '$id_sessione_test', '$value');";
        $result = $conn->query($sql);
    }

    header("Location: index.php");

    die();
}