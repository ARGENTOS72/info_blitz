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

$_SESSION['current_page'] = "test";

$id_utente = $_SESSION['id_utente'];

require "../../include/db.php";
$conn = accediDb();

if (isset($_POST['remove_definetly']) && isset($_POST['id'])) {
    $id = normalize($conn, $_POST['id']);

    $sql = "DELETE FROM test WHERE id=$id";

    $conn->query($sql);
}
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
    <?php require "../helpers/docente_navbar.php"; ?>
    <div class="modal fade" id="removeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sei sicuro di volerlo eliminare?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <form method="post">
                        <input type="hidden" name="id" id="id-input-remove-definetly">
                        <input type="submit" value="SI" name="remove_definetly" class="btn btn-primary">
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="container my-4">
        
        <div class="d-flex align-items-center mb-3">
            <h1 class="pe-3">Lista Test</h1>
            <!-- Mostra il pulsante "Crea" solo per gli admin -->
            <a href="create.php" class="btn btn-primary d-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
                Crea
            </a>
        </div>

        <?php
        $sql = "SELECT * FROM test WHERE id_docente=$id_utente";

        $result = $conn->query($sql);
        ?>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Titolo</th>
                    <th>Descrizione</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['titolo'] ?></td>
                        <td><?= (empty($row['descrizione'])) ? "Nessuna descrizione" : $row['descrizione'] ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="view.php?id=<?= $row['id'] ?>" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                    </svg>
                                    Visualizza
                                </a>
                                <a href="update.php?id=<?= $row['id'] ?>"
                                    class="btn btn-outline-primary d-flex align-items-center gap-1" role="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                    </svg>
                                    Update
                                </a>
                                <button class="btn btn-outline-danger btn-remove-test d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#removeModal" data-test-id="<?= $row['id'] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                    </svg>
                                    Remove
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script>
        const removeUserForms = document.getElementsByClassName('btn-remove-test');

        Array.prototype.forEach.call(removeUserForms, button => {
            button.addEventListener('click', () => {
                document.getElementById('id-input-remove-definetly').value = button.dataset['testId'];
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>