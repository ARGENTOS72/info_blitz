<?php
session_start();

if (!isset($_SESSION['login'])) {
    http_response_code(401);

    die();
}

if ($_SESSION['role'] != "docente") {
    http_response_code(403);

    die();
}

$_SESSION['current_page'] = "test";
$id_utente = $_SESSION['id_utente'];

if (isset($_POST['create'])) {
    require "../../include/db.php";
    $conn = accediDb();

    $titolo = normalize($conn, $_POST['titolo']);
    $descrizione = normalize($conn, $_POST['descrizione']);

    $sql = "INSERT INTO test (titolo, descrizione, id_docente) VALUES ('$titolo', '$descrizione', '$id_utente')";
    $conn->query($sql);
    $test_id = $conn->insert_id;

    if (isset($_POST['domande'])) {
        foreach ($_POST['domande'] as $domanda) {
            $testo_domanda = normalize($conn, $domanda['testo']);
            $tipo_domanda = normalize($conn, $domanda['tipo']);
            $punteggio = normalize($conn, $domanda['punteggio']);

            $sql_domanda = "INSERT INTO domanda (id_test, testo_domanda, tipo, punteggio) VALUES ('$test_id', '$testo_domanda', '$tipo_domanda', '$punteggio')";
            $conn->query($sql_domanda);
            $domanda_id = $conn->insert_id;

            if ($tipo_domanda === 'multipla' && isset($domanda['risposte'])) {
                foreach ($domanda['risposte'] as $index => $risposta) {
                    $testo_risposta = normalize($conn, $risposta);
                    $index = normalize($conn, $index);
                    
                    $corretta = (isset($domanda['corrette']) && in_array($index, $domanda['corrette'])) ? 1 : 0;
                    
                    $sql_risposta = "INSERT INTO domanda_multipla (id_domanda, testo_opzione, corretta) VALUES ('$domanda_id', '$testo_risposta', '$corretta')";
                    $conn->query($sql_risposta);
                }
            }
        }
    }

    header("Location: index.php");

    die();
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

            <div id="domande"></div>

            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('multipla')"> + Domanda Multipla</button>
            <button type="button" class="btn btn-secondary" onclick="aggiungiDomanda('aperta')"> + Domanda Aperta</button>

            <button type="submit" name="create" class="btn btn-primary">Crea Test</button>
        </form>
    </div>
    <script>
        let domandaCounter = 0;

        function aggiungiDomanda(tipo) {
            domandaCounter++;

            const domandeDiv = document.getElementById('domande');

            // Crea un contenitore per la domanda
            const domandaDiv = document.createElement('div');
            domandaDiv.className = 'mb-3 border rounded p-3';
            domandaDiv.innerHTML = `
                <label for="domanda${domandaCounter}" class="form-label">Domanda ${domandaCounter}:</label>
                <input type="text" class="form-control" name="domande[${domandaCounter}][testo]" id="domanda${domandaCounter}" required>
                <input type="hidden" name="domande[${domandaCounter}][tipo]" value="${tipo}">
                <label for="punteggio${domandaCounter}" class="form-label mt-2">Punteggio:</label>
                <input type="number" class="form-control" name="domande[${domandaCounter}][punteggio]" min="1" id="punteggio${domandaCounter}" required>
                <button type="button" class="btn btn-sm btn-outline-danger my-3 d-flex align-items-center gap-1" onclick="this.parentElement.remove()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                    </svg>
                    Rimuovi domanda
                </button>
            `;

            // Se la domanda è a risposta multipla, aggiungi campi per le risposte
            if (tipo === 'multipla') {
                const risposteDiv = document.createElement('div');

                risposteDiv.className = 'mb-3';
                risposteDiv.innerHTML = `
                    <label>Risposte:</label>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="domande[${domandaCounter}][risposte][]" placeholder="Risposta 1" required>
                            <div class="input-group-text d-flex align-items-center gap-2">
                                <input type="checkbox" name="domande[${domandaCounter}][corrette][]" value="0" id="corretta1${domandaCounter}">
                                <label for="corretta1${domandaCounter}" class="form-label mb-0">Corretta</label>
                            </div>
                            <div class="input-group-text d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger my-3 d-flex align-items-center gap-1" onclick="this.parentElement.parentElement.parentElement.remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="domande[${domandaCounter}][risposte][]" placeholder="Risposta 2" required>
                            <div class="input-group-text d-flex align-items-center gap-2">
                                <input type="checkbox" name="domande[${domandaCounter}][corrette][]" value="1" id="corretta2${domandaCounter}">
                                <label for="corretta2${domandaCounter}" class="form-label mb-0">Corretta</label>
                            </div>
                            <div class="input-group-text d-flex align-items-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-danger my-3 d-flex align-items-center gap-1" onclick="this.parentElement.parentElement.parentElement.remove()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="aggiungiRisposta(this)" data-risposta-counter="2">Aggiungi risposta</button>
                `;

                domandaDiv.appendChild(risposteDiv);
            }

            domandeDiv.appendChild(domandaDiv);
        }

        function aggiungiRisposta(button) {
            
            const rispostaCounter = parseInt(button.dataset['rispostaCounter'], 10);
            button.dataset['rispostaCounter'] = rispostaCounter + 1;
            const risposteDiv = button.parentElement;
            const nuovaRisposta = document.createElement('div');

            nuovaRisposta.innerHTML = `
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="${button.previousElementSibling.children[0].children[0].name}" placeholder="Risposta ${rispostaCounter + 1}" required>
                    <div class="input-group-text d-flex align-items-center gap-2">
                        <input type="checkbox" name="${button.previousElementSibling.children[0].children[1].children[0].name}" value="${rispostaCounter}" id="corretta${rispostaCounter + 1}${domandaCounter}">
                        <label for="corretta${rispostaCounter + 1}${domandaCounter}" class="form-label mb-0">Corretta</label>
                    </div>
                    <div class="input-group-text d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-danger my-3 d-flex align-items-center gap-1" onclick="this.parentElement.parentElement.parentElement.remove()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            risposteDiv.insertBefore(nuovaRisposta, button);
        }

        function rimuoviDomanda(button) {
            button.parentElement.remove();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>