<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

require "../../../include/db.php";
$conn = accediDb();

if (isset($_POST['remove_true']) && isset($_POST['id']) && $_POST['id'] != 1) {
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
    <?php if (isset($_POST['remove']) && isset($_POST['id']) && $_POST['id'] != 1): ?>
    <div class="modal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sei sicuro di eliminare?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Modal body text goes here.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                    <form method="post">
                        <input type="hidden" name="id" value="<?= $_POST['id'] ?>">
                        <input type="submit" value="SI" name="remove_true">
                        <input type="submit" value="NO" name="remove_false">
                    </form>
                </div>
            </div>
            </div>
        </div>
        <h2></h2>
        
    </div>
    <?php endif; ?>
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
                    <form method="post" id="remove">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="submit" name="remove" value="Remove" role="link" class="btn btn-link">
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="create.php" class="btn btn-primary">Crea</a>
    </div>
</body>
</html>