<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);
    
    die();
}

if ($_SESSION['role'] != "docente") {
    http_response_code(403);
    
    die();
}

$_SESSION['current_page'] = "revisione";
$id_utente = $_SESSION['id_utente'];

require "../../../include/db.php";
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
    <?php require "../../helpers/docente_navbar.php"; ?>
    <div class="container my-4">
        <h1 class="d-flex justify-content-center">Revisione</h1>
        <div class="row gap-4 justify-content-center mt-5">
            <?php
            $sql =
                "SELECT DISTINCT test.*, utente.nome AS NomeStudente,
                utente.cognome AS CognomeStudente, utente.id AS IdStudente FROM test
                LEFT JOIN sessione_test ON sessione_test.id_test=test.id
                LEFT JOIN risposta ON risposta.id_sessione_test=sessione_test.id
                LEFT JOIN utente ON utente.id=risposta.id_studente
                LEFT JOIN correzione ON correzione.id_test=test.id
                WHERE test.id_docente=$id_utente AND risposta.id IS NOT NULL AND correzione.id IS NULL";
            ?>
            <?php $result = $conn->query($sql); ?>
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-3">
                <div class="card h-100 position-relative">
                    <img class="card-img-top" src="../../../test.jpg" alt="Card image">
                    <div class="card-body position-static d-flex flex-column">
                        <h4 class="card-title"><?= $row['titolo'] ?></h4>
                        <p class="card-text">Verifica di <?= $row['CognomeStudente']." ".$row['NomeStudente'] ?></p>
                        <a href="view.php?id_test=<?= $row['id'] ?>&id_studente=<?= $row['IdStudente'] ?>" class="btn btn-primary stretched-link">Correggi test</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>