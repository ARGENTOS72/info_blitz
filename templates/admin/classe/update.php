<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

if ($_SESSION['role'] != "admin") {
    http_response_code(403);
    
    die();
}

$_SESSION['current_page'] = "classe";

if (isset($_GET['classe'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $classe = $_GET['classe'];

    if (isset($_POST['update'])) {
        $new_class = normalize($conn, $_POST['classe']);

        $sql = "UPDATE classe SET classe='$new_class' WHERE classe='$classe'";

        $conn->query($sql);

        header("Location: index.php");
    
        die();
    }

    $sql = "SELECT * FROM classe WHERE classe='$classe'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        
        $classe = $row['classe'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica classe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require "../../helpers/admin_navbar.php"; ?>
    <div class="container my-4">
        <h1>Modifica classe</h1>
        <form method="post">
            <label for="classe">Classe:</label>
            <input type="text" id="classe" name="classe" value="<?= $classe ?>">
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