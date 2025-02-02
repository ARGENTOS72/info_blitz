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

require "../../../include/db.php";
$conn = accediDb();

if (isset($_POST['remove_definetly']) && isset($_POST['classe'])) {
    $classe = normalize($conn, $_POST['classe']);

    $sql = "DELETE FROM classe WHERE classe='$classe'";

    $conn->query($sql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amministrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        form.remove-user-form {
            display: inline-block;
        }

        table tr td:last-child {
            white-space: nowrap;
            width: 1%
        }
    </style>
</head>
<body>
    <?php require "../../helpers/admin_navbar.php"; ?>
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
                        <input type="hidden" name="classe" id="classe-input-remove-definetly">
                        <input type="submit" value="SI" name="remove_definetly" class="btn btn-primary">
                    </form>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="container my-4">
        <div class="d-flex align-items-center mb-3">
            <h1 class="pe-3">Lista classi</h1>
            <a href="create.php" class="btn btn-primary d-flex align-items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
                Crea
            </a>
        </div>
        <?php $result = $conn->query("SELECT * FROM classe"); ?>
        <table class="table table-striped table-hover">
            <tr>
                <th>Classe</th>
                <th></th>
            </tr>
            
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr class="align-middle">
                <td><?= $row['classe'] ?></td>
                <td>
                    <div class="d-flex align-items-center gap-1">
                        <a href="update.php?classe=<?= $row['classe'] ?>"
                            class="btn btn-outline-primary d-flex align-items-center gap-1" role="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                            </svg>
                            Update
                        </a>
                        <button class="btn btn-outline-danger btn-remove-class d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#removeModal" data-classe="<?= $row['classe'] ?>">
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
        </table>
    </div>
    <script>
        const removeUserForms = document.getElementsByClassName('btn-remove-class');

        Array.prototype.forEach.call(removeUserForms, button => {
            button.addEventListener('click', () => {
                document.getElementById('classe-input-remove-definetly').value = button.dataset['classe'];
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>