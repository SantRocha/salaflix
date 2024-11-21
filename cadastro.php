<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário - SGS</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <style>
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

        form {
            border: 8px solid #006699;
            width: 360px;
            margin: auto;
            border-radius: 15px;
            background-color: aliceblue;
        }

        form label::after {
            content: ":";
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.64em;
            padding: 12px;
            align-items: center;
            font-size: 1em;
            background-color: aliceblue;
            border-radius: 7px;
        }

        .bot {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.64em;
        }

        input, select {
            border-radius: 4px;
            padding: 0.4em;
            border: 1px solid black;
            font-size: 0.9em;
        }

        .enviar, .cancelar {
            background-color: #006699;
            color: white;
            border-radius: 4px;
            padding: 0.4em;
            border: none;
            font-size: 1em;
            font-weight: bold;
            margin-top: 9.6px;
        }

        .enviar:hover, .cancelar:hover {
            background-color: #003366;
            cursor: pointer;
        }

        .cancelar {
            background-color: #bc0000;
        }

        .cancelar:hover {
            background-color: #7b0000;
        }

        .senha {
            position: relative;
            width: 95%;
        }

        .senha input {
            width: 100%;
        }

        .lnr-eye {
            position: absolute;
            top: 5px;
            right: 10px;
            cursor: pointer;
        }

        @media screen and (max-width: 650px) {
            #cadastro {
                width: calc(100% - 50px);
            }

            .grid {
                font-size: 1.05em;
            }

            .bot {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<header>
    <div class="header-container">
        <img class="logo" src="./imagens/logo.png" alt="Logo">
        <h1><b>SalaFlix</b></h1>
    </div>
</header>
<hr>
<hr>
<main>
    <form action="./login/cadastro_usuario.php" method="POST" class="login">
        <div class="grid">
            <label for="nome">Nome Completo</label>
            <input type="text" id="nome" name="nome" required>

            <label for="usuario">Nome de Usuário</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="telefone">Telefone</label>
            <input type="number" name="telefone" id="telefone" required>

            <label for="senha">Senha</label>
            <div class="senha">
                <input type="password" id="senha" name="senha" required>
                <span class="lnr lnr-eye"></span>
            </div>

            <label for="confirmar_senha">Confirmar Senha</label>
            <div class="senha">
                <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                <span class="lnr lnr-eye"></span>
            </div>

            <div class="bot">
                <a href="./index.php" class="cancelar">Cancelar</a>
                <button type="submit" class="enviar">Cadastrar</button>
            </div>
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

<script>
    let btns = document.querySelectorAll('.lnr-eye');

    btns.forEach(btn => {
        btn.addEventListener('click', function () {
            let input = this.previousElementSibling;
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
            } else {
                input.setAttribute('type', 'password');
            }
        });
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        const senha = document.getElementById('senha').value;
        const confirmar_senha = document.getElementById('confirmar_senha').value;

        if (senha !== confirmar_senha) {
            event.preventDefault();
            alert('As senhas não coincidem.');
        }
    });
</script>
</body>
</html>
