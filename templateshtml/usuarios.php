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
    <title>SGS</title>
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .btnaaaaa{
            display: flex;
            width: 100%;
        }

        .btncad {
            padding: 10px;
            background-color: #00a900;
            font-weight: bold;
            border: solid 1px white;
            border-radius: 5px;
            cursor: pointer;
            color: white;
            margin: 20px auto 0px;
        }

        .btncad:hover {
            background-color: #007a00;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            top: -100%;
            left: 50%;
            transform: translateX(-50%);
            background-color: #f1f1f1;
            color: #333;
            padding: 5px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            white-space: nowrap;
            transition: opacity 0.3s, visibility 0.3s;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .imgtooltip {
            width: 20px;
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
    <a href="./reservas.php">Reservas</a>
    <a href="#" class="selecionado">Usuarios</a>
    <a href="./recursos.php">Recursos</a>
    <a href="./perfil.php">Perfil</a>
    <a href="../login/logout.php" class="btncad sair">Sair</a>
</nav>
<hr>
<main>

    <div class="btnaaaaa">
        <a href="./cadUsuario.php" class="buttonsCad btncad">Cadastrar Novo usuario</a>
    </div>

    <div id="tabela">
        <?php
        include '../listas/usuarios.php';
        ?>
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