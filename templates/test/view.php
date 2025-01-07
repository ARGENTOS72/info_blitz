<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");

    die();
}

$_SESSION['current_page'] = "test";

$ruolo = "admin";

if (isset($_GET['id'])) {
    require "../../include/db.php";
    $conn = accediDb();

    $id_test = $_GET['id'];

    $sql = "SELECT * FROM test WHERE id=$id_test";
    $result_test = $conn->query($sql);

    if ($result_test->num_rows == 1) {
        $test = $result_test->fetch_assoc();

        $titolo = $test['titolo'];
        $descrizione = $test['descrizione'];
    } else {
        // Errore non trova test su db (id passato sbagliato)
    }
} else {
    // No id
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
    <?php
    if ($ruolo == "admin") {
        require "../helpers/admin_navbar.php";
    }
    ?>
    <h1><?= $titolo ?></h1>
    <h2><?= $descrizione ?></h2>
    <form action="elabora.php" method="post">
        <!-- DOMANDE -->
        <?php
        $sql = "SELECT * FROM domanda WHERE id_test=$id_test";
        $result_domande = $conn->query($sql);

        while ($domanda = $result_domande->fetch_assoc()):
        $id_domanda = $domanda['id'];
        ?>
        <div>
            <p><?= $domanda['testo_domanda'] ?></p>

            <!-- DOMANDA MULTIPLA -->
            <?php
            switch ($domanda['tipo']):
            case "multipla":

            $sql = "SELECT * FROM domanda_multipla WHERE id_domanda=".$id_domanda;
            $result_opzioni_multipla = $conn->query($sql);
            
            while ($opzione_scelta_multipla = $result_opzioni_multipla->fetch_assoc()):
                $radio_id = $id_domanda." ".$opzione_scelta_multipla['id'];
            ?>

            <input type="radio" name="<?= $id_domanda ?>"
                id="<?= $radio_id ?>" value="<?= $opzione_scelta_multipla['id'] ?>">
            <label for="<?= $radio_id ?>"><?= $opzione_scelta_multipla['testo_opzione'] ?></label>
            <br>
            <?php endwhile; ?>

            <?php break; ?>
            <!-- FINE DOMANDA MULTIPLA -->
            
            <!-- DOMANDA APERTA -->
            <?php case "aperta": ?>
            <textarea name="<?= $id_domanda ?>"></textarea>
            <?php break; ?>
            <!-- FINE DOMANDA APERTA -->

            <?php endswitch; ?>
        </div>
        <?php endwhile; ?>
        <!-- FINE DOMANDE -->

        <input type="submit" value="Invia" name="invia">
    </form>
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>