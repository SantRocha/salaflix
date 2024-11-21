<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Sala - SGS</title>
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        /******* Configurações do formulario de login *******/
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

        .bot, .bot-login {
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

        .enviar, .cancelar, .adicionar, form a {
            background-color: #006699;
            color: white;
            border-radius: 4px;
            padding: 0.4em;
            border: none;
            font-size: 1em;
            font-weight: bold;
            margin-top: 9.6px;
        }

        .enviar:hover, .cancelar:hover, .adicionar:hover, form a:hover {
            background-color: #003366;
            cursor: pointer;
        }

        button > a {
            width: 400px;
        }

        label {
            font-weight: 700;
            text-align: left;
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

        /******* Configurações de responsividade do formulario de login *******/
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
    <form action="" method="POST" class="login">
        <h2>Login</h2>
        <div class="grid">
            <label for="UsuarioLogin">Email</label>
            <input type="text" id="UsuarioLogin" name="login" required>

            <br>
            <label for="UsuarioSenha">Senha</label>
            <div class="senha">
                <input type="password" id="UsuarioSenha" name="senha" required>
                <span class="lnr lnr-eye"></span>
            </div>
            <br>

            <!--<a href="./templateshtml/sala.php">Entrar</a>-->
            <button type="submit" class="enviar">Entrar</button>
            <a href="./cadastro.php" class="enviar">Cadastrar</a>
            
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
    let btn = document.querySelector('.lnr-eye');

    btn.addEventListener('click', function() {

        let input = document.querySelector('#UsuarioSenha');

        if(input.getAttribute('type') == 'password') {
            input.setAttribute('type', 'text');
        } else {
            input.setAttribute('type', 'password');
        }

    });

    document.querySelector('form').addEventListener('submit', async function (event) {
        event.preventDefault();

        const login = document.getElementById('UsuarioLogin').value;
        const senha = document.getElementById('UsuarioSenha').value;

        try {
            const response = await fetch('login/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `login=${encodeURIComponent(login)}&senha=${encodeURIComponent(senha)}`
            });

            const result = await response.json();

            if (result.status === 'success') {
                // Armazenar o token no localStorage
                localStorage.setItem('token', result.token);

                // Redirecionar para a página de salas
                window.location.href = result.redirect;
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('Erro ao autenticar:', error);
            alert(error);
        }
    });

</script>
</body>
</html>
