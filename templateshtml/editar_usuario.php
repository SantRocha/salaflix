<?php
// Importa a função para proteger a página
require '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado
$usuario = proteger_pagina_funcionario_ou_adm();

$permissao_adm = ($usuario->funcao === 'adm');
$permissao_funcionario = ($usuario->funcao === 'funcionario' || $usuario->funcao === 'adm');

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o ID do usuário foi passado na URL
if (isset($_GET['id_usuario'])) {
    $id_usuario = intval($_GET['id_usuario']);

    // Consulta SQL para buscar as informações do usuário pelo ID
    $sql = "SELECT nome_usuario, nick_usuario, email_usuario, telefone_usuario, funcao_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);

    // Recuperar o resultado como array associativo
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $nome_usuario = $usuario['nome_usuario'];
        $nick_usuario = $usuario['nick_usuario'];
        $email_usuario = $usuario['email_usuario'];
        $telefone_usuario = $usuario['telefone_usuario'];
        $funcao_usuario = $usuario['funcao_usuario'];
    } else {
        echo "Usuário não encontrado.";
        exit;
    }
} else {
    echo "ID do usuário não fornecido.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">
    <style>
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
    <form action="../acoes/atualizar_usuario.php" method="POST">
        <h2>Editar Usuário</h2>
        <br>
        <div class="cadastro">
            <!-- Campo oculto para enviar o ID do usuário -->
            <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">

            <label for="nome_usuario">Nome Completo:</label>
            <input type="text" id="nome_usuario" name="nome_usuario" value="<?php echo $nome_usuario; ?>" required>

            <label for="nick_usuario">Nome de Usuário (Nickname):</label>
            <input type="text" id="nick_usuario" name="nick_usuario" value="<?php echo $nick_usuario; ?>" required>

            <label for="email_usuario">Email:</label>
            <input type="email" id="email_usuario" name="email_usuario" value="<?php echo $email_usuario; ?>" required>

            <label for="telefone_usuario">Telefone:</label>
            <input type="number" name="telefone_usuario" id="telefone_usuario" value="<?php echo $telefone_usuario ?>">

            <label for="senha_usuario">Senha:</label>
            <input type="password" id="senha_usuario" name="senha_usuario">


            <?php if ($permissao_adm): ?>
                <label for="funcao_usuario">Função:</label>
                <select id="funcao_usuario" name="funcao_usuario" required>
                    <option value="cliente" <?php if ($funcao_usuario == 'cliente') echo 'selected'; ?>>Cliente</option>
                        <option value="funcionario" <?php if ($funcao_usuario == 'funcionario') echo 'selected'; ?>>Funcionário</option>
                        <option value="adm" <?php if ($funcao_usuario == 'adm') echo 'selected'; ?>>Administrador</option>
                </select>
            <?php endif; ?>
            <br><br>
        </div>
        <div class="bot">
            <a href="./usuarios.php" class="btncad del">Cancelar</a>
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
