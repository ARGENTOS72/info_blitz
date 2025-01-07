<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: ../templates/admin/utente/index.php");

    die();
}

header("Location: ../templates/login.php");