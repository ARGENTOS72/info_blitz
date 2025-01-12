<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

$_SESSION['current_page'] = "test";
$id_utente = $_SESSION['id_utente'];

if (isset($_POST['create'])) {
    require "../../include/db.php";
    $conn = accediDb();

    // Recupera i dati del test
    $titolo = normalize($conn, $_POST['titolo']);
    $descrizione = normalize($conn, $_POST['descrizione']);

    // Inserisci il test nel database
    $sql = "INSERT INTO test (titolo, descrizione, id_docente) VALUES ('$titolo', '$descrizione', '$id_utente')";
    if ($conn->query($sql) === TRUE) {
        $test_id = $conn->insert_id; // Ottieni l'ID del test appena creato

        // Gestione delle domande
        if (isset($_POST['domande'])) {

            foreach ($_POST['domande'] as $domanda) {
                $testo_domanda = $domanda['testo'];
                $tipo_domanda = $domanda['tipo'];

                // Inserisci la domanda nel database
                $sql_domanda = "INSERT INTO domande (test_id, testo, tipo) VALUES ('$test_id', '$testo_domanda', '$tipo_domanda')";
                if ($conn->query($sql_domanda) === TRUE) {
                    $domanda_id = $conn->insert_id;

                    // Se la domanda è a risposta multipla, gestisci le risposte prendendo l'id della domanda e lo usa poi per salvare le risposte con il giusto id
                    if ($tipo_domanda === 'multipla' && isset($domanda['risposte'])) {
                        foreach ($domanda['risposte'] as $risposta) {
                            $testo_risposta = $risposta;
                            $sql_risposta = "INSERT INTO risposta (domanda_id, testo) VALUES ('$domanda_id', '$testo_risposta')";
                            $conn->query($sql_risposta);
                        }
                    }
                }
            }
        }

        header("Location: index.php");
        die();
    } else {
        echo "Errore durante la creazione del test: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crea Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require "../helpers/docente_navbar.php"; ?>
    <div class="container my-4">
        <h1>Creazione Test</h1>
        <form method="post">
            <div class="mb-3">
                <label for="titolo" class="form-label">Titolo:</label>
                <input type="text" class="form-control" id="titolo" name="titolo" required>
            </div>
            <div class="mb-3">
                <label for="descrizione" class="form-label">Descrizione:</label>
                <textarea class="form-control" id="descrizione" name="descrizione" rows="3" required></textarea>
            </div>

            <div id="domande">
            </div>

            <!-- i due bottoni che si occupano di creare i tipi di domande -->
            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('multipla')"> + domanda multiple</button>
            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('aperta')"> + domanda aperta</button>

            <button type="submit" name="create" class="btn btn-primary">Crea Test</button>
        </form>
    </div>

    <script>
        let domandaCounter = 0;

        function aggiungiDomanda(tipo) {
            domandaCounter++;

            const domandeDiv = document.getElementById('domande');

            // Crea un contenitore per la domanda, dividere la creazione della domada e la sua differenziazione in 2 parti concise
            const domandaDiv = document.createElement('div');

            domandaDiv.className = 'mb-3';
            domandaDiv.innerHTML = `
                <label for="domanda${domandaCount}" class="form-label">Domanda ${domandaCount}:</label>
                <input type="text" class="form-control" name="domande[${domandaCount}][testo]" required>
                <input type="hidden" name="domande[${domandaCount}][tipo]" value="${tipo}">
            `;

            // Se la domanda è a risposta multipla, aggiungi campi per le risposte
            if (tipo === 'multipla') {
                const risposteDiv = document.createElement('div');

                risposteDiv.className = 'mb-3';
                risposteDiv.innerHTML = `
                    <label>Risposte:</label>
                    <input type="text" class="form-control mb-2" name="domande[${domandaCounter}][risposte][]" placeholder="Risposta 1" required>
                    <input type="text" class="form-control mb-2" name="domande[${domandaCounter}][risposte][]" placeholder="Risposta 2" required>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="aggiungiRisposta(this)">Aggiungi risposta</button>
                `;

                domandaDiv.appendChild(risposteDiv);
            }

            domandeDiv.appendChild(domandaDiv);
        }

        function aggiungiRisposta(button) {
            const risposteDiv = button.parentElement;
            const nuovaRisposta = document.createElement('input');

            nuovaRisposta.type = 'text';
            nuovaRisposta.className = 'form-control mb-2';
            nuovaRisposta.name = button.previousElementSibling.name; // Usa lo stesso nome dell'ultima risposta
            nuovaRisposta.placeholder = 'Nuova risposta';

            risposteDiv.insertBefore(nuovaRisposta, button);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>