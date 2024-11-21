<?php
// Importa a função para proteger a página
require '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado
$usuario = proteger_pagina_funcionario_ou_adm();

$permissao_adm = ($usuario->funcao === 'adm');
$permissao_funcionario = ($usuario->funcao === 'funcionario' || $usuario->funcao === 'adm');

?>

<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o ID do recurso foi passado na URL
if (isset($_GET['id_recurso'])) {
    $id_recurso = intval($_GET['id_recurso']);

    // Consulta SQL para buscar as informações do recurso pelo ID
    $sql = "SELECT nome_recurso FROM recursos_disponiveis WHERE id_recurso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_recurso]);

    // Recuperar o resultado como array associativo
    $recurso = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($recurso) {
        $nome_recurso = $recurso['nome_recurso'];
    } else {
        echo "Recurso não encontrado.";
        exit;
    }
} else {
    echo "ID do recurso não fornecido.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Recurso</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">
    <style>
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
    <form action="../acoes/atualizar_recurso.php" method="POST">
        <h2>Editar Recurso</h2>
        <br>
        <div class="cadastro">
            <!-- Campo oculto para enviar o ID do recurso -->
            <input type="hidden" name="id_recurso" value="<?php echo $id_recurso; ?>">

            <label for="nome_recurso">Nome do Recurso:</label>
            <input type="text" id="nome_recurso" name="nome_recurso" value="<?php echo $nome_recurso; ?>" required>
        </div><br><br>
        <div class="bot">
            <a href="./recursos.php" class="btncad del">Cancelar</a>
            <button type="submit" class="btncad">Atualizar</button>
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

