<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);
    
    die();
}

if ($_SESSION['role'] != "studente") {
    http_response_code(403);
    
    die();
}

$classe = $_SESSION['class'];
$id_utente = $_SESSION['id_utente'];

require "../../include/db.php";
$conn = accediDb();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require "../helpers/studente_navbar.php"; ?>
    <div class="container my-4">
        <h1 class="d-flex justify-content-center">Sessioni attive</h1>
        <div class="row gap-4 justify-content-center mt-5">
            <?php
            $sql =
                "SELECT sessione_test.* FROM sessione_test LEFT JOIN risposta
                ON risposta.id_sessione_test=sessione_test.id AND risposta.id_studente=$id_utente
                WHERE risposta.id IS NULL AND sessione_test.classe='$classe'";
            ?>
            <?php $result = $conn->query($sql); ?>
            <?php while ($sessione_test = $result->fetch_assoc()): ?>
            <?php
            $result_test = $conn->query("SELECT * FROM test WHERE id=".$sessione_test['id_test']);
            $test = $result_test->fetch_assoc();

            $result_docente = $conn->query("SELECT cognome FROM utente WHERE id=".$test['id_docente']);
            $docente = $result_docente->fetch_assoc();
            ?>
            <div class="col-md-3">
                <div class="card h-100 position-relative">
                    <img class="card-img-top" src="../../test.jpg" alt="Card image">
                    <div class="card-body position-static d-flex flex-column">
                        <h4 class="card-title"><?= $test['titolo'] ?></h4>
                        <p class="card-text"><?= $test['descrizione'] ?></p>
                        <div class="d-flex flex-column mt-auto">
                            <p class="text-secondary"><?= $docente['cognome'] ?></p>
                            <a href="view.php?id=<?= $sessione_test['id_test'] ?>&id_sessione_test=<?= $sessione_test['id'] ?>" class="btn btn-primary stretched-link">Tenta test</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>