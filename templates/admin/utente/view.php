<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

if ($_SESSION['role'] != "admin") {
    http_response_code(403);

    die();
}

$_SESSION['current_page'] = "utente";

if (isset($_GET['id'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $id = normalize($conn, $_GET['id']);

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
    <?php require "../../helpers/admin_navbar.php"; ?>
    <div class="container my-4">
        <h1>Utente</h1>
        <dl>
            <dt>Nome:</dt>
            <dd><?= $nome ?></dd>
            <dt>Cognome:</dt>
            <dd><?= $cognome ?></dd>
            <dt>Login:</dt>
            <dd><?= $login ?></dd>
        </dl>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>