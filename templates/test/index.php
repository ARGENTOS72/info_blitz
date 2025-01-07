<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    die();
}

$_SESSION['current_page'] = "test";

// Recupera il ruolo dall'utente (esempio: dalla sessione o dal database)
$ruolo = $_SESSION['ruolo']; // Assicurati che il ruolo sia memorizzato nella sessione dopo il login
$utente_id = $_SESSION['utente_id']; // ID dell'utente loggato (utile per i docenti)

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
    <?php
    // Mostra la navbar in base al ruolo
    if ($ruolo == "admin") {
        require "../helpers/admin_navbar.php";
    } elseif ($ruolo == "docente") {
        require "../helpers/docente_navbar.php";
    }
    ?>
    
    <div class="container my-4">
        <div class="d-flex align-items-center mb-3">
            <h1 class="pe-3">Lista Test</h1>
            <!-- Mostra il pulsante "Crea" solo per gli admin -->
            <?php if ($ruolo == "admin"): ?>
                <a href="create.php" class="btn btn-primary d-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    Crea
                </a>
            <?php endif; ?>
        </div>

        <?php
        // Query personalizzata in base al ruolo
        if ($ruolo == "admin") {
            // Admin vede tutti i test
            $sql = "SELECT * FROM test";
        } elseif ($ruolo == "docente") {
            // Docente vede solo i test associati al proprio ID
            $sql = "SELECT * FROM test WHERE id_docente = $utente_id";
        }

        $result = $conn->query($sql);
        ?>

        <table class="table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Titolo</th>
                    <th>Descrizione</th>
                    <!-- Mostra la colonna "Id Docente" solo per gli admin -->
                    <?php if ($ruolo == "admin"): ?>
                        <th>Id Docente</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><a href="view.php?id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
                        <td><?= $row['titolo'] ?></td>
                        <td><?= $row['descrizione'] ?></td>
                        <!-- Mostra l'ID del docente solo per gli admin -->
                        <?php if ($ruolo == "admin"): ?>
                            <td><?= $row['id_docente'] ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>