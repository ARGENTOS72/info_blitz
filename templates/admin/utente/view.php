<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");

    die();
}

if (isset($_GET['id'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $id = $_GET['id'];

    $sql = "SELECT * FROM utente WHERE id=$id";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $login = $row['login'];
    }
} else {
    // Manca id
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Utente</h1>
    <a href="index.php"><- Utenti</a>
    <dl>
        <dt>Nome:</dt>
        <dd><?= $nome ?></dd>
        <dt>Cognome:</dt>
        <dd><?= $cognome ?></dd>
        <dt>Login:</dt>
        <dd><?= $login ?></dd>
    </dl>
</body>
</html>