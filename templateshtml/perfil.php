<?php
// Importa a função para proteger a página
require '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado
$usuario = proteger_pagina();

$permissao_adm = ($usuario->funcao === 'adm');
$permissao_funcionario = ($usuario->funcao === 'funcionario' || $usuario->funcao === 'adm');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGS</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">

    <style>
        .perfil {
            width: 730px;
            padding: 30px 15px;
            margin: auto;
            text-align: center;
            background-color: white;
            border-radius: 25px;
            box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.116);
        }

        label {
            font-weight: 800;
        }

        .cadastro {
            margin: auto;
            grid-template-columns: 1fr 5fr;
        }

        .btncad {
            padding: 10px;
            background-color: #00a900;
            font-weight: bold;
            border: solid 1px white;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            margin: 0px 20px;
        }

        .del {
            background-color: #006699;
        }

        .btncad:hover {
            background-color: #007a00;
        }

        .del:hover {
            background-color: #003366;
        }
        
        .sair {
            margin: 0px;
            background-color: #e50000;
            border: none;
        }
        .sair:hover {
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
<nav>
    <a href="./sala.php">Salas</a>
    <a href="./minhasReservas.php">Minhas Reservas</a>
    <?php if ($permissao_funcionario): ?>
        <a href="./reservas.php">Reservas</a>
        <a href="./usuarios.php">Usuarios</a>
        <a href="./recursos.php">Recursos</a>
    <?php endif; ?>
    <a href="#" class="selecionado">Perfil</a>
    <a href="../login/logout.php" class="btncad sair">Sair</a>
</nav>
<hr>
<main>
    <div class="perfil">
        <h3>Suas Informações <span>Nome Usuario</span></h3>
        <br><hr><br>
        <div class="cadastro">
            <label for="nome">Nome:</label>
            <span><?php echo htmlspecialchars($usuario->nome); ?></span>

            <label for="usuario">Nickname:</label>
            <span><?php echo htmlspecialchars($usuario->nickname); ?></span>

            <label for="email">Email:</label>
            <span><?php echo htmlspecialchars($usuario->email); ?></span>

            <label for="telefone">Telefone</label>
            <span><?php echo htmlspecialchars($usuario->telefone); ?></span>
        </div>
        <br><hr><br>
        <div class="bot">
            <a href="./editar_minhaSenha.php" class="btncad del">Alterar Minha Senha</a>
            <a href="./editar_meusDados.php" class="btncad">Atualizar Meus Dados</a>
        </div>
    </div>

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