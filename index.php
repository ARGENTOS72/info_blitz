<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: admin/utente/index.php");
}

header("Location: templates/login.php");