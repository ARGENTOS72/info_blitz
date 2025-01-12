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

$_SESSION['current_page'] = "classe";

if (isset($_POST['create'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $classe = normalize($conn, $_POST['classe']);

    $sql = "INSERT INTO classe (classe) VALUES ('$classe')";

    $conn->query($sql);

    header("Location: index.php");
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
    <?php require "../../helpers/admin_navbar.php"; ?>
    <div class="container my-4">
        <h1>Creazione utente</h1>
        <form method="post">
            <label for="classe">Classe:</label>
            <input type="text" id="classe" name="classe">
            <br>
            <input type="submit" value="Crea" name="create">
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