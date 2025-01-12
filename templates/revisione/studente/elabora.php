<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

if (isset($_POST['invia']) && isset($_POST['id_test']) && isset($_POST['id_studente'])) {
    require "../../../include/db.php";
    $conn = accediDb();
    
    $id_test = normalize($conn, $_POST['id_test']);
    $id_studente = normalize($conn, $_POST['id_studente']);

    unset($_POST['invia']);
    unset($_POST['id_test']);
    unset($_POST['id_studente']);

    $correzione = NULL;
    $punteggio = NULL;

    foreach ($_POST as $key => $value) {
        $key = normalize($conn, $key);
        $value = normalize($conn, $value);
        
        $key = str_replace("correzione_", "", $key);
        $key = str_replace("punteggio_", "", $key);

        if ($correzione == NULL) {
            $correzione = $value;
        } else if ($punteggio == NULL) {
            $punteggio = $value;

            $sql =
                "INSERT INTO correzione (id_domanda, id_test, id_studente, correzione, punteggio)
                VALUES ('$key', '$id_test', '$id_studente', '$correzione', '$punteggio');";
            $result = $conn->query($sql);

            $correzione = NULL;
            $punteggio = NULL;
        }
    }

    header("Location: index.php");

    die();
}