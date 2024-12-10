<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");

    die();
}

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
    <h1>Tutti i test</h1>
    <?php $result = $conn->query("SELECT * FROM test"); ?>
    <table border=1>
        <tr>
            <th>Id</th>
            <th>titolo</th>
            <th>Descrizione</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><a href="view.php?id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
            <td><?= $row['titolo'] ?></td>
            <td><?= $row['descrizione'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>