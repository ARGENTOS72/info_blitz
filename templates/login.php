<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();

    session_destroy();
}

if (isset($_POST['comando'])) {
    require "../include/db.php";
    $conn = accediDb();

    $post_login = normalize($conn, $_POST['login']);
    $post_password = normalize($conn, $_POST['password']);

    $sql = "SELECT * FROM utente WHERE login='$post_login'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $utente = $result->fetch_assoc();

        $is_password_right = password_verify($post_password, $utente['password']);
        
        if ($is_password_right) { 
            $_SESSION['login'] = $post_login;
            $_SESSION['role'] = $utente['ruolo'];
            $_SESSION['id_utente'] = $utente['id'];

            if ($utente['ruolo'] == "studente") {
                $result = $conn->query("SELECT classe FROM studente WHERE id_utente=".$utente['id']);
                $studente = $result->fetch_assoc();

                $_SESSION['class'] = $studente['classe'];
            }
        }
    }
}

if (isset($_SESSION['login'])) {
    switch ($_SESSION['role']) {
    case "studente":
        header("Location: sessione/index.php");
        
        break;
    case "docente":
        header("Location: test/index.php");
        
        break;
    case "admin":
        header("Location: admin/utente/index.php");
        
        break;
    }

    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina di Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form method="post">
        <label for="login">Login:</label>
        <input type="text" id="login" name="login" size="40">
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" size = "40">
        <br>
        <input type="submit" name="comando" value="Login"><br>
</form>
</body>
</html>