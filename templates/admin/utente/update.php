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

    if (isset($_POST['update'])) {
        $nome = normalize($conn, $_POST['nome']);
        $cognome = normalize($conn, $_POST['cognome']);
        $login = normalize($conn, $_POST['login']);
        $password = normalize($conn, $_POST['password']);
    
        $sql = "UPDATE utente SET nome='$nome', password='$password', 
            cognome='$cognome', login='$login' WHERE id=$id";
    
        $conn -> query($sql);
    
        header("Location: index.php");
    
        die();
    }

    $sql = "SELECT * FROM utente WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $nome = $row['nome'];
        $cognome = $row['cognome'];
        $login = $row['login'];
    } else {
        // Errore non c'è utente
    }
} else {
    // Non c'è id
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require "../../helpers/admin_navbar.php"; ?>
    <div class="container my-4">
        <h1>Modifica utente</h1>
        <form method="post">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" value="<?= $nome ?>">
            <br>
            <label for="cognome">Cognome:</label>
            <input type="text" id="cognome" name="cognome" value="<?= $cognome ?>">
            <br>
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?= $login ?>">
            <br>
            <label for="password">Password:</label>
            <input type="text" id="password" name="password">
            <br>
            <input type="submit" value="Modifica" name="update">
        </form>
    </div>
    <script>
        const form = document.getElementsByTagName('form')[0];
        let formModified = false;

        form.addEventListener('change', () => {
            formModified = true;
        });

        form.addEventListener('submit', () => {
            formModified = false;
        });

        window.addEventListener("beforeunload", event => {
            if (formModified) {
                event.preventDefault();
                event.returnValue = "";
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>