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
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="shortcut icon" href="../imagens/favicon-32x32.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
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

        .cadastro {
            margin: auto;
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 15px;
            width: 400px;
        }

        label {
            font-weight: bold;
        }

        .senha {
            position: relative;
            width: 100%;
        }

        .senha input {
            width: 95%;
        }

        .lnr-eye {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
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
    <form id="formAlterarSenha" action="../acoes/atualizar_senha.php" method="POST">
        <h2>Alterar Senha</h2>
        <br>
        <div class="cadastro">
            <!-- Campo oculto para enviar o ID do usuário -->
            <input type="hidden" name="id_usuario" value="<?php echo $usuario->id; ?>">

            <label for="senha_atual">Senha Atual:</label>
            <div class="senha">
                <input type="password" id="senha_atual" name="senha_atual" required>
                <span class="lnr lnr-eye" data-toggle="#senha_atual"></span>
            </div>

            <label for="nova_senha">Nova Senha:</label>
            <div class="senha">
                <input type="password" id="nova_senha" name="nova_senha" required>
                <span class="lnr lnr-eye" data-toggle="#nova_senha"></span>
            </div>

            <label for="confirmar_senha">Confirmar Nova Senha:</label>
            <div class="senha">
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                <span class="lnr lnr-eye" data-toggle="#confirmar_senha"></span>
            </div>
        </div>
        <br>
        <div class="bot">
            <a href="./perfil.php" class="btncad del">Cancelar</a>
            <button type="submit" class="btncad">Alterar Senha</button>
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

<!-- Adiciona o script de visualização de senha e validação -->
<script>
    // Função para alternar a visualização da senha
    document.querySelectorAll('.lnr-eye').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = document.querySelector(this.getAttribute('data-toggle'));
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
            } else {
                input.setAttribute('type', 'password');
            }
        });
    });

    // Validação de senha ao enviar o formulário
    document.getElementById('formAlterarSenha').addEventListener('submit', function (event) {
        const senhaAtual = document.getElementById('senha_atual').value;
        const novaSenha = document.getElementById('nova_senha').value;
        const confirmarSenha = document.getElementById('confirmar_senha').value;

        // Verificar se a nova senha é igual à antiga
        if (novaSenha === senhaAtual) {
            alert('A nova senha não pode ser igual à senha atual.');
            event.preventDefault(); // Prevenir o envio do formulário
            return;
        }

        // Verificar se a nova senha coincide com a confirmação
        if (novaSenha !== confirmarSenha) {
            alert('A nova senha e a confirmação da senha não coincidem.');
            event.preventDefault(); // Prevenir o envio do formulário
            return;
        }
    });
</script>

</body>
</html>
