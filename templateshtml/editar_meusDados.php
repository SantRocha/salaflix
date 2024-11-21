<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Importa a função para proteger a página
require '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado (apenas cliente)
$usuario = proteger_pagina();

$permissao_adm = ($usuario->funcao === 'adm');
$permissao_funcionario = ($usuario->funcao === 'funcionario' || $usuario->funcao === 'adm');

// Conecte ao banco de dados
require '../conexao/db_connection.php';

// Verificar se o usuário está logado e carregar os dados
$id_usuario = $usuario->id;

// Consulta SQL para buscar as informações do usuário logado
$sql = "SELECT nome_usuario, nick_usuario, email_usuario, telefone_usuario FROM usuarios WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);
$usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se os dados do usuário foram recuperados
if ($usuarioData) {
    $nome_usuario = htmlspecialchars($usuarioData['nome_usuario']);
    $nick_usuario = htmlspecialchars($usuarioData['nick_usuario']);
    $email_usuario = htmlspecialchars($usuarioData['email_usuario']);
    $telefone_usuario = htmlspecialchars($usuarioData['telefone_usuario']);
} else {
    echo "Usuário não encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
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
            height: 100px;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
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
    <form action="../acoes/atualizar_dados_pessoais.php" method="POST">
        <h2>Editar Dados Pessoais</h2>
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

            <label for="telefone_usuario">Telefone</label>
            <input type="number" name="telefone_usuario" id="telefone_usuario" value="<?php echo $telefone_usuario; ?>">
        </div>
        <br>
        <div class="bot">
            <a href="./perfil.php" class="btncad del">Cancelar</a>
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
