<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

require "../../../include/db.php";
$conn = accediDb();

if (isset($_POST['remove_definetly']) && isset($_POST['id']) && $_POST['id'] != 1) {
    $id = $_POST['id'];

    $sql = "DELETE FROM utente WHERE id=$id";

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
        form#remove {
            display: inline-block;
        }
    </style>
</head>
<body>
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
        <h1 class="px-4">Lista utenti</h1>
        <?php $result = $conn->query("SELECT * FROM utente"); ?>
        <table class="table table-striped table-hover">
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Login</th>
                <th></th>
            </tr>
            
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>
                    <a href="view.php?id=<?= $row['id'] ?>"><?= $row['id'] ?></a>
                </td>
                <td><?= $row['nome'] ?></td>
                <td><?= $row['cognome'] ?></td>
                <td><?= $row['login'] ?></td>
                <td>
                    <a href="update.php?id=<?= $row['id'] ?>" class="btn btn-link">Update</a>
                    |
                    <form method="post" class="remove-user-form">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="submit" name="remove" value="Remove" role="link"
                            class="btn btn-link" data-bs-toggle="modal" data-bs-target="#removeModal">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="create.php" class="btn btn-primary">Crea</a>
    </div>
    <script>
        const removeUserForms = document.getElementsByClassName('remove-user-form');

        console.log(removeUserForms);

        Array.prototype.forEach.call(removeUserForms, form => {
            form.addEventListener('submit', e => {
                e.preventDefault();

                document.getElementById('id-input-remove-definetly').value = e.target.id.value;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>