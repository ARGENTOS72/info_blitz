<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

$_SESSION['current_page'] = "test";

$ruolo = "prof";
$ruolo = "admin";

if (isset($_POST['create'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $titolo = $conn->real_escape_string($_POST['titolo']);
    $descrizione = $conn->real_escape_string($_POST['descrizione']);

    $sql = "INSERT INTO test (id, titolo, descrizione) VALUES
        (null, '$titolo', '$descrizione', )";

    try {
        $result = $conn->query($sql);

        header("Location: index.php");
    
        die();
    } catch (mysqli_sql_exception $err) {
        echo $err->getMessage();
        echo $err->getSqlState();
    }
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
    <?php
    if ($ruolo == "admin") {
    require "../../helpers/admin_navbar.php";
    }
    ?>
    <div class="container my-4">
    <h1>Creazione utente</h1>    <h1>Creazione utente</h1>
    <form method="post">
        <input type="text" id="nome" name="nome">
        <br>
        <label for="cognome">Descrizione:</label>
        <input type="text" id="cognome" name="cognome">
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