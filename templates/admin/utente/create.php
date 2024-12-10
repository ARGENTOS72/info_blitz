<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

if (isset($_POST['create'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $nome = $conn->real_escape_string($_POST['nome']);
    $cognome = $conn->real_escape_string($_POST['cognome']);
    $login = $conn->real_escape_string($_POST['login']);
    $password = $conn->real_escape_string($_POST['password']);

    $sql = "INSERT INTO utente (id, nome, cognome, login, password) VALUES
        (null, '$nome', '$cognome', '$login', '$password')";
    $conn->query($sql);

    header("Location: index.php");

    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h1>Creazione utente</h1>
    <a href="index.php"><- Utenti</a>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        <br>
        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome">
        <br>
        <label for="login">Login:</label>
        <input type="text" id="login" name="login">
        <br>
        <label for="password">Password:</label>
        <input type="text" id="password" name="password">
        <br>
        <input type="submit" value="Crea" name="create">
    </form>
</body>
</html>