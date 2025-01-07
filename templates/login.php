<?php
session_start();

if (isset($_SESSION['login'])) {
    header("Location: admin/utente/index.php");

    die();
}

if (isset($_POST['comando'])) {
    require "../include/db.php";
    $conn = accediDb();

    $post_login = $conn->real_escape_string(htmlspecialchars($_POST['login']));
    $post_password = $conn->real_escape_string(htmlspecialchars($_POST['password']));

    $hashed_password = password_hash($post_password, PASSWORD_BCRYPT);
    $is_password_right = password_verify($post_password, $hashed_password);

    if ($is_password_right) {
        $sql = "SELECT * FROM utente WHERE login='$post_login'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $_SESSION['login'] = $post_login;
    
            header("location: admin/utente/index.php");
        }
    }
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