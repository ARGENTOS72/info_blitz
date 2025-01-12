<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

if ($_SESSION['role'] != "studente") {
    http_response_code(403);
    
    die();
}

$_SESSION['current_page'] = "revisione";
$id_studente = $_SESSION['id_utente'];

if (isset($_GET['id_test'])) {
    require "../../../include/db.php";
    $conn = accediDb();

    $id_test = normalize($conn, $_GET['id_test']);

    $sql = "SELECT titolo FROM test WHERE id=$id_test";
    $result_test = $conn->query($sql);

    if ($result_test->num_rows == 1) {
        $test = $result_test->fetch_assoc();

        $titolo = $test['titolo'];
    } else {
        http_response_code(400);

        die();
    }
} else {
    http_response_code(400);

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
    <?php require "../../helpers/studente_navbar.php"; ?>
    <div class="container">
        <h1 class="my-3"><?= $titolo ?></h1>
        <!-- DOMANDE -->
        <?php
        $sql =
            "SELECT domanda.*, risposta.risposta,
            correzione.correzione, correzione.punteggio AS punteggioCorrezione FROM domanda
            LEFT JOIN risposta ON risposta.id_domanda=domanda.id
            LEFT JOIN correzione ON correzione.id_test=$id_test AND correzione.id_domanda=risposta.id_domanda
            AND risposta.id_studente=$id_studente WHERE domanda.id_test=$id_test";
        $result_domande = $conn->query($sql);

        $punteggio_finale = 0;

        while ($domanda = $result_domande->fetch_assoc()):
        $id_domanda = $domanda['id'];
        $risposta = $domanda['risposta'];
        $punteggio_finale += $domanda['punteggioCorrezione'];
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

                if ($numero_corrette != 1) {
                    $risposte = explode(",", $risposta);

                    $punteggio_per_risposta = $domanda['punteggio'] / $numero_corrette;
                }
                
                $sql = "SELECT * FROM domanda_multipla WHERE id_domanda=".$id_domanda;
                $result_opzioni_multipla = $conn->query($sql);

                $punteggio_finale_domanda = 0;

                while ($opzione_scelta_multipla = $result_opzioni_multipla->fetch_assoc()):
                    $id_input = $id_domanda." ".$opzione_scelta_multipla['id'];

                    if ($opzione_scelta_multipla['corretta']) {
                        if ($numero_corrette != 1 && in_array($opzione_scelta_multipla['id'], $risposte)) {
                            $punteggio_finale_domanda += $punteggio_per_risposta;
                        } else if ($risposta == $opzione_scelta_multipla['id']) {
                            $punteggio_finale_domanda = $domanda['punteggio'];
                        }
                    }
                ?>

                <?php if ($numero_corrette == 1): ?>
                    <!-- UNA RISPOSTA CORRETTA (RADIO BUTTON) -->
                    <input type="radio" name="<?= $id_domanda ?>"
                        id="<?= $id_input ?>" value="<?= $opzione_scelta_multipla['id'] ?>"
                        <?= ($risposta == $opzione_scelta_multipla['id']) ? "checked" : "" ?>
                        disabled>
                    <label for="<?= $id_input ?>"><?= $opzione_scelta_multipla['testo_opzione'] ?></label>
                    <br>
                <?php else: ?>
                    <!-- VARIE RISPOSTE CORRETTE (CHECKBOX BUTTON) -->
                    <input type="checkbox" name="<?= $id_domanda ?>[]"
                        id="<?= $id_input ?>" value="<?= $opzione_scelta_multipla['id'] ?>"
                        <?= (in_array($opzione_scelta_multipla['id'], $risposte)) ? "checked" : "" ?>
                        disabled>
                    <label for="<?= $id_input ?>"><?= $opzione_scelta_multipla['testo_opzione'] ?></label>
                    <br>
                <?php endif; ?>
                
                <?php endwhile; ?>
                
                <label>Correzione:</label>
                <textarea style="resize: both;" name="correzione_<?= $id_domanda ?>" cols="42" rows="6" disabled><?= $domanda['correzione'] ?></textarea>

                <?php break; ?>
                <!-- FINE DOMANDA MULTIPLA -->
                
                <!-- DOMANDA APERTA -->
                <?php case "aperta": ?>
                <label>Risposta:</label>
                <textarea style="resize: both;" name="<?= $id_domanda ?>" cols="42" rows="6" disabled><?= $risposta ?></textarea>
                <br>
                <label>Correzione:</label>
                <textarea style="resize: both;" name="correzione_<?= $id_domanda ?>" cols="42" rows="6" disabled><?= $domanda['correzione'] ?></textarea>
                <?php break; ?>
                <!-- FINE DOMANDA APERTA -->

                <?php endswitch; ?>
            </div>
            <div class="card-footer">
                La domanda vale <b><?= $domanda['punteggio'] ?></b>
                <br>
                Hai totalizzato <b><?= $domanda['punteggioCorrezione'] ?></b>
            </div>
        </div>
        <?php endwhile; ?>
        <!-- FINE DOMANDE -->
        <div class="card mb-4">
            <div class="card-footer">Punteggio finale: <b><?= $punteggio_finale ?></b></div>
        </div>
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>