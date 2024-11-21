<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservar Sala</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .form-reserva {
            width: 400px;
            margin: 20px auto;
        }
        .btncad {
            padding: 10px;
            background-color: #00a900;
            font-weight: bold;
            font-size: 1.2em;
            border: solid 1px white;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            margin: 0px 20px;
        }

        .del {
            background-color: #e50000;
        }

        .btncad:hover {
            background-color: #007a00;
        }

        .del:hover {
            background-color: #aa0f00;
        }
        header {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px; /* Defina a altura que desejar */
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 50px; /* Ajuste o tamanho da logo conforme necessário */
            height: auto;
            margin-right: 10px; /* Espaço entre a logo e o título */
        }

        .form-reserva label {
            display: block;
            margin-top: 10px;
        }

        .form-reserva input {
            width: 95%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            padding: 10px;
            width: 100%;
            margin-top: 20px;
            background-color: #00a900;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 1em;
            cursor: pointer;
        }

        button:hover {
            background-color: #007a00;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img class="logo" src="../imagens/logo.png" alt="Logo">
            <h1><b>SalaFlix</b></h1>
        </div>
    </header>
    <main>
        <form action="../cadastros/cadastrar_reserva.php" method="POST" class="form-reserva">
            <input type="hidden" name="id_sala" value="<?php echo $_GET['id_sala']; ?>">

            <label for="data_aluguel">Data do Aluguel:</label>
            <input type="date" id="data_aluguel" name="data_aluguel" required>

            <label for="hora_aluguel">Hora do Aluguel:</label>
            <input type="time" id="hora_aluguel" name="hora_aluguel" required>

            <label for="data_entrega">Data da Entrega:</label>
            <input type="date" id="data_entrega" name="data_entrega" required>

            <label for="hora_entrega">Hora da Entrega:</label>
            <input type="time" id="hora_entrega" name="hora_entrega" required>

            <button type="submit">Reservar</button>
        </form>
    </main>
</body>
</html>
