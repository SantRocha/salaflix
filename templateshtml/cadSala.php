<?php
// Importa a função para proteger a página
require '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado
$usuario = proteger_pagina_funcionario_ou_adm();

$permissao_adm = ($usuario->funcao === 'adm');
$permissao_funcionario = ($usuario->funcao === 'funcionario' || $usuario->funcao === 'adm');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Sala - SGS</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">
    <script src="../scripts/javascript.js" defer></script>
    <style>
        #container-imagens{
            display: grid;
            grid-template-columns: 1fr;
            gap: 1em;
            align-items: center;
        }

        #container-imagens input {
            margin: auto;
        }

        #btnimgs {
            font-weight: bolder;
            font-size: 1.5em;
            border: solid 2px black;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            margin-left: 10px;
            background-color: white;
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
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <img class="logo" src="../imagens/logo.png" alt="Logo">
        <h1><b>SalaFlix</b></h1>
    </div>
</header>
<hr>
<main>
    <form action="../cadastros/cadastro_sala.php" method="POST" id="formulario">
        <h2>Cadastro de Sala</h2>
        <br>
        <div class="cadastro">
            <label for="nome_sala">Nome da Sala:</label>
            <input type="text" id="nome_sala" name="nome_sala" required>

            <label for="tipo_sala">Tipo de Sala:</label>
            <select id="tipo_sala" name="tipo_sala" required>
                <?php
                // Conexão com o banco de dados
                include '../conexao/db_connection.php';

                // Consulta para buscar os tipos de sala do banco de dados
                $sqlTipos = "SELECT id_tipo_sala, nome_tipo_sala FROM tipo_sala";
                $stmtTipos = $conn->query($sqlTipos);
                $resultTipos = $stmtTipos->fetchAll(PDO::FETCH_ASSOC);

                // Gerar opções no select com os resultados
                if ($resultTipos) {
                    foreach ($resultTipos as $row) {
                        echo "<option value='" . $row['id_tipo_sala'] . "'>" . $row['nome_tipo_sala'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum tipo de sala encontrado</option>";
                }
                ?>
            </select>

            <label for="largura_sala">Largura (m):</label>
            <input type="number" id="largura_sala" name="largura_sala" required>

            <label for="comprimento_sala">Comprimento (m):</label>
            <input type="number" id="comprimento_sala" name="comprimento_sala" required>

            <label for="capacidade_sala">Capacidade:</label>
            <input type="number" id="capacidade_sala" name="capacidade_sala" required>

            <label for="acessibilidade_para_cadeirantes_sala">Acessibilidade para Cadeirantes:</label>
            <input type="checkbox" id="acessibilidade_para_cadeirantes_sala" name="acessibilidade_para_cadeirantes_sala">

            <label for="valor_por_hora">Valor por Hora:</label>
            <input type="number" id="valor_por_hora" name="valor_por_hora" step="0.01" required>

            <label for="valor_por_mes">Valor por Mês:</label>
            <input type="number" id="valor_por_mes" name="valor_por_mes" step="0.01" required>

            <label for="descricao_sala">Descrição:</label>
            <textarea id="descricao_sala" name="descricao_sala" required></textarea>

            <label for="localizacao_sala">Localização:</label>
            <input type="text" id="localizacao_sala" name="localizacao_sala" required>
        </div>
        <br><hr><br>
        <h3>Recursos Disponíveis na Sala</h3><br><br>
        <div class="recursos">
            <?php
            // Consulta para buscar os recursos disponíveis do banco de dados
            $sqlRecursos = "SELECT id_recurso, nome_recurso FROM recursos_disponiveis";
            $stmtRecursos = $conn->query($sqlRecursos);
            $resultRecursos = $stmtRecursos->fetchAll(PDO::FETCH_ASSOC);

            // Verificar se há recursos disponíveis
            if ($resultRecursos) {
                // Gerar os checkboxes para cada recurso
                foreach ($resultRecursos as $rowRecurso) {
                    echo "<input type='checkbox' id='recurso_" . $rowRecurso['id_recurso'] . "' name='recursos[]' value='" . $rowRecurso['id_recurso'] . "'>";
                    echo "<label for='recurso_" . $rowRecurso['id_recurso'] . "'>" . $rowRecurso['nome_recurso'] . "</label><br>";
                }
            } else {
                echo "Nenhum recurso disponível.";
            }
            ?>
        </div>
        
        <br><hr><br>

        <div class="bot">
            <a href="./sala.php" class="btncad del">Cancelar</a>
            <button id="botcad" class="btncad">Cadastrar</button>
        </div>
    </form>
</main>

<footer><br>
    <div id="info-rodape">
        <h4>SalaFlix - Central de Negócios Compartilhados</h4>
        <p>Rua Floriano Peixoto, n° 883 Papouco &curren; 699000-090 &curren; Rio Branco - AC, Brasil</p>
        <p>Tel: (66) 3223-4618 &curren; E-mail: <a href="mailto:cnc.acre@gmail.com">cnc.acre&#64;gmail.com</a></p>
    </div><br>
</footer>

</body>
</html>