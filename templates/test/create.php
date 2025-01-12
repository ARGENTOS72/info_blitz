<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../../login.php");
    die();
}

$_SESSION['current_page'] = "test";
$id_utente = $_SESSION['id_utente'];
$ruolo = "admin";

if (isset($_POST['create'])) {
    require "../../include/db.php";
    $conn = accediDb();

    // Recupera i dati del test
    $titolo = $conn->real_escape_string($_POST['titolo']);
    $descrizione = $conn->real_escape_string($_POST['descrizione']);

    // Inserisci il test nel database
    $sql = "INSERT INTO test (titolo, descrizione, id_docente) VALUES ('$titolo', '$descrizione', '$id_utente')";
    if ($conn->query($sql) === TRUE) {
        $test_id = $conn->insert_id; // Ottieni l'ID del test appena creato

        // Gestione delle domande
        if (isset($_POST['domande'])) {
            foreach ($_POST['domande'] as $domanda) {
                $testo_domanda = $conn->real_escape_string($domanda['testo']);
                $tipo_domanda = $conn->real_escape_string($domanda['tipo']);
                $punteggio = $conn->real_escape_string($domanda['punteggio']);

                // Inserisci la domanda nel database
                $sql_domanda = "INSERT INTO domanda (id_test, testo_domanda, tipo, punteggio) VALUES ('$test_id', '$testo_domanda', '$tipo_domanda', '$punteggio')";
                if ($conn->query($sql_domanda) === TRUE) {
                    $domanda_id = $conn->insert_id;

                    // Se la domanda è a risposta multipla, gestisci le risposte
                    if ($tipo_domanda === 'multipla' && isset($domanda['risposte'])) {
                        foreach ($domanda['risposte'] as $index => $risposta) {
                            $testo_risposta = $conn->real_escape_string($risposta);
                            // Verifica se la risposta è contrassegnata come corretta
                            $corretta = (isset($domanda['corrette']) && in_array($index, $domanda['corrette'])) ? 1 : 0;
                            $sql_risposta = "INSERT INTO domanda_multipla (id_domanda, testo_opzione, corretta) VALUES ('$domanda_id', '$testo_risposta', '$corretta')";
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
    <?php
    if ($ruolo == "admin") {
        require "../helpers/admin_navbar.php";
    }
    ?>
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

            <div id="domande"></div>

            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('multipla')"> + Domanda Multipla</button>
            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('aperta')"> + Domanda Aperta</button>

            <button type="submit" name="create" class="btn btn-primary mt-3">Crea Test</button>
        </form>
    </div>

    <script>
        let domandaCount = 0;

        function aggiungiDomanda(tipo) {
            domandaCount++;
            const domandeDiv = document.getElementById('domande');

            // Crea un contenitore per la domanda
            const domandaDiv = document.createElement('div');
            domandaDiv.className = 'mb-3 border p-3';
            domandaDiv.innerHTML = `
                <label for="domanda${domandaCount}" class="form-label">Domanda ${domandaCount}:</label>
                <input type="text" class="form-control" name="domande[${domandaCount}][testo]" required>
                <input type="hidden" name="domande[${domandaCount}][tipo]" value="${tipo}">
                <label for="punteggio${domandaCount}" class="form-label mt-2">Punteggio:</label>
                <input type="number" class="form-control" name="domande[${domandaCount}][punteggio]" min="1" required>
            `;

            // Se la domanda è a risposta multipla, aggiungi campi per le risposte
            if (tipo === 'multipla') {
                const risposteDiv = document.createElement('div');
                risposteDiv.className = 'mb-3';
                risposteDiv.innerHTML = `
                    <label>Risposte:</label>
                    <div>
                        <input type="text" class="form-control mb-2" name="domande[${domandaCount}][risposte][]" placeholder="Risposta 1" required>
                        <input type="checkbox" name="domande[${domandaCount}][corrette][]" value="0"> Corretta
                    </div>
                    <div>
                        <input type="text" class="form-control mb-2" name="domande[${domandaCount}][risposte][]" placeholder="Risposta 2" required>
                        <input type="checkbox" name="domande[${domandaCount}][corrette][]" value="1"> Corretta
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="aggiungiRisposta(this)">Aggiungi risposta</button>
                `;
                domandaDiv.appendChild(risposteDiv);
            }

            domandeDiv.appendChild(domandaDiv);
        }

        function aggiungiRisposta(button) {
            const risposteDiv = button.parentElement;
            const nuovaRisposta = document.createElement('div');
            nuovaRisposta.innerHTML = `
                <input type="text" class="form-control mb-2" name="${button.previousElementSibling.name}" placeholder="Nuova risposta" required>
                <input type="checkbox" name="${button.previousElementSibling.previousElementSibling.name}" value="${risposteDiv.children.length - 1}"> Corretta
            `;
            risposteDiv.insertBefore(nuovaRisposta, button);
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>