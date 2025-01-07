<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    
    die();
}

$_SESSION['current_page'] = "utente";

$ruolo = "admin";

if (isset($_POST['create'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $nome = normalize($conn, $_POST['nome']);
    $cognome = normalize($conn, $_POST['cognome']);
    $login = normalize($conn, $_POST['login']);
    $password = normalize($conn, $_POST['password']);
    $ruolo = normalize($conn, $_POST['ruolo']);
    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO utente (nome, cognome, login, password, ruolo) VALUES
        ('$nome', '$cognome', '$login', '$password_hash', '$ruolo')";

    $conn->query($sql);

    if ($ruolo == "studente") {
        $sql = "INSERT INTO studente (id_utente, classe) VALUES
            (".$conn->insert_id.", '".normalize($conn, $_POST['classe'])."')";

        $conn->query($sql);
    }

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea utente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php
    if ($ruolo == "admin") {
    require "../../helpers/admin_navbar.php";
    }
    ?>
    <div class="container my-4">
    <h1>Creazione utente</h1>
    <form method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome">
        <br>
        <label for="cognome">Cognome:</label>
        <input type="text" id="cognome" name="cognome">
        <br>
        <label for="login">Login:</label>
        <input type="text" id="login" name="login">
        <br>
        <label for="password">Password:</label>
        <input type="text" id="password" name="password">
        <br>
        <label for="ruolo">Ruolo utente:</label>
        <select name="ruolo" id="ruolo">
            <option value="admin">Admin</option>
            <option value="docente">Docente</option>
            <option value="studente">Studente</option>
        </select>
        <br>
        <div id="dati-ruolo"></div>
        <input type="submit" value="Crea" name="create">
    </form>
    </div>
    <script>
        const form = document.getElementsByTagName('form')[0];
        let formModified = false;

        form.addEventListener('change', () => {
            formModified = true;
        });

        form.addEventListener('submit', () => {
            formModified = false;
        });

        window.addEventListener("beforeunload", event => {
            if (formModified) {
                event.preventDefault();
                event.returnValue = "";
            }
        });

        const dati_ruolo_aggiuntivi = document.getElementById('dati-ruolo');
        document.getElementById('ruolo').addEventListener('input', e => {
            let value = e.target.value;

            if (value === "studente") {
                let req = new XMLHttpRequest();
                req.open("GET", "../../../controllers/classi.php");
            
                req.onload = () => {
                    if (req.status === 200) {
                        let classi = JSON.parse(req.responseText);

                        let classi_input_form = document.createElement('div');

                        let classi_label = document.createElement('label');
                        classi_label.innerText = "Classe:";
                        classi_label.for = "classe";

                        let classi_input = document.createElement('select');
                        classi_input.id = "classe";
                        classi_input.name = "classe";

                        classi.forEach(classe => {
                            let classe_option = document.createElement('option');
                            classe_option.value = classe;
                            classe_option.innerText = classe;

                            classi_input.appendChild(classe_option);
                        });

                        classi_input_form.appendChild(classi_label);
                        classi_input_form.appendChild(classi_input);

                        dati_ruolo_aggiuntivi.appendChild(classi_input_form);
                    }
                }

                req.send();
            } else {
                if (dati_ruolo_aggiuntivi.firstChild) {
                    dati_ruolo_aggiuntivi.removeChild(dati_ruolo_aggiuntivi.firstChild);
                }
            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>