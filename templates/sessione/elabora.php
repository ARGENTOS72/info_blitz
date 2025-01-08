<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");

    die();
}

if (isset($_POST['invia'])) {
    require "../../include/db.php";
    $conn = accediDb();

    foreach ($_POST as $key => $value) {
        if (is_array($value)) {
            $value = implode(",", $value);
        }

        if ($key != "invia") {
            $sql = "INSERT INTO risposta (id, id_domanda, risposta) VALUES (null, $key, '$value');";
            $result = $conn->query($sql);
        }
    }

    header("Location: index.php");

    die();
}