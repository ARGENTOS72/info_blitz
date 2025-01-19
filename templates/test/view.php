<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");

    die();
}

$_SESSION['current_page'] = "test";

$ruolo = "admin";
$session_test_error = false;

if (isset($_GET['id'])) {
    require "../../include/db.php";
    $conn = accediDb();

    $id_test = normalize($conn, $_GET['id']);

    $sql = "SELECT * FROM test WHERE id=$id_test";
    $result_test = $conn->query($sql);

    if ($result_test->num_rows == 1) {
        $test = $result_test->fetch_assoc();

        $titolo = $test['titolo'];
        $descrizione = $test['descrizione'];

        if (isset($_POST['aggiungi_classe'])) {
            $classe = normalize($conn, $_POST['classe_nuova']);
        
            try {
                $sql = "INSERT INTO sessione_test (id_test, classe) VALUES ($id_test, '$classe')";
                $conn->query($sql);
            } catch (mysqli_sql_exception $err) {
                $session_test_error = true;
            }
        }
    } else {
        http_response_code(404);

        die();
    }
} else {
    http_response_code(404);

    die();
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
    <?php if ($session_test_error): ?>
    <div class="toast show position-fixed bottom-0 end-0 p-3 z-3 mb-3 me-3" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto text-danger">Errore aggiunta</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Aggiungere lo stesso test alla stessa classe non si può!
        </div>
    </div>
    <?php endif; ?>
    <div class="container">
        <h1><?= $titolo ?></h1>
        <h2><?= $descrizione ?></h2>
        <div class="container mb-3">
            <p>Assegnato a: </p>
            <?php
            $sql =
                "SELECT classe.classe FROM sessione_test
                LEFT JOIN classe ON classe.classe=sessione_test.classe
                WHERE id_test=$id_test";
            $result = $conn->query($sql);
            
            while ($sessione = $result->fetch_assoc()):
            ?>
            <div classe="container">
                <label for="classe<?= $sessione['classe'] ?>">Classe</label>
                <input type="text" id="classe<?= $sessione['classe'] ?>" value="<?= $sessione['classe'] ?>" name="classi_assegnate" disabled>
            </div>
            <?php endwhile; ?>
            <div>
                <p class="mb-2">Assegna a:</p>
                <form method="post">
                    <label for="classe_nuova">Classe:</label>
                    <select name="classe_nuova" id="classe_nuova" class="mb-2"></select>
                    <br>
                    <input type="submit" value="Aggiungi" name="aggiungi_classe">
                </form>
            </div>
        </div>
        <form action="elabora.php" method="post">
            <!-- DOMANDE -->
            <?php
            $sql = "SELECT * FROM domanda WHERE id_test=$id_test";
            $result_domande = $conn->query($sql);

            while ($domanda = $result_domande->fetch_assoc()):
            $id_domanda = $domanda['id'];
            ?>
            <div class="card mb-3">
                <h4 class="card-header"><?= $domanda['testo_domanda'] ?></h4>

                <div class="card-body">
                    <!-- DOMANDA MULTIPLA -->
                    <?php
                    switch ($domanda['tipo']):
                    case "multipla":

                    $sql = "SELECT COUNT(corretta) AS conto_corrette FROM domanda_multipla WHERE corretta=1 AND id_domanda=".$id_domanda;
                    $result_conto_corrette = $conn->query($sql);
                    $conto_corrette = $result_conto_corrette->fetch_assoc();
                    $numero_corrette = $conto_corrette['conto_corrette'];
                    
                    $sql = "SELECT * FROM domanda_multipla WHERE id_domanda=".$id_domanda;
                    $result_opzioni_multipla = $conn->query($sql);
                    
                    while ($opzione_scelta_multipla = $result_opzioni_multipla->fetch_assoc()):
                        $id_input = $id_domanda." ".$opzione_scelta_multipla['id'];
                    ?>

                    <?php if ($numero_corrette == 1): ?>
                        <input type="radio" name="<?= $id_domanda ?>"
                            id="<?= $id_input ?>" value="<?= $opzione_scelta_multipla['id'] ?>" disabled>
                        <label for="<?= $id_input ?>"><?= $opzione_scelta_multipla['testo_opzione'] ?></label>
                        <br>
                    <?php else: ?>
                        <input type="checkbox" name="<?= $id_domanda ?>[]"
                            id="<?= $id_input ?>" value="<?= $opzione_scelta_multipla['id'] ?>" disabled>
                        <label for="<?= $id_input ?>"><?= $opzione_scelta_multipla['testo_opzione'] ?></label>
                        <br>
                    <?php endif; ?>
                    
                    <?php endwhile; ?>

                    <?php break; ?>
                    <!-- FINE DOMANDA MULTIPLA -->
                    
                    <!-- DOMANDA APERTA -->
                    <?php case "aperta": ?>
                    <textarea style="resize: both;" name="<?= $id_domanda ?>" cols="42" rows="6" disabled></textarea>
                    <?php break; ?>
                    <!-- FINE DOMANDA APERTA -->

                    <?php endswitch; ?>
                </div>
                <div class="card-footer">La domanda vale <b><?= $domanda['punteggio'] ?></b></div>
            </div>
            <?php endwhile; ?>
            <!-- FINE DOMANDE -->
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

        const selectClassi = document.getElementById('classe_nuova');
        const classiAssegnateElement = document.getElementsByName('classi_assegnate');
        let classiAssegnate = [];

        Array.from(classiAssegnateElement).forEach(element => {
            classiAssegnate.push(element.value);
        });

        let req = new XMLHttpRequest();
        req.open("GET", "../../controllers/classi.php");
    
        req.onload = () => {
            if (req.status === 200) {
                let classi = JSON.parse(req.responseText);

                classi.forEach(classe => {
                    if (!classiAssegnate.includes(classe)) {
                        let classe_option = document.createElement('option');
                        classe_option.value = classe;
                        classe_option.innerText = classe;
                        
                        selectClassi.appendChild(classe_option);
                    }
                });

                if (selectClassi.length === 0) {
                    selectClassi.parentElement.parentElement.remove();
                }
            }
        }

        req.send();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>