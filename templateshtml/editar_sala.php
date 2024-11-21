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

// Verificar se o ID da sala foi passado na URL
if (isset($_GET['id_sala'])) {
    $id_sala = intval($_GET['id_sala']);

    // Consulta SQL para buscar as informações da sala pelo ID
    $sql = "SELECT nome_sala, tipo_sala, largura_sala, comprimento_sala, capacidade_sala, acessibilidade_sala_para_cadeirantes, descricao_sala, localizacao_sala, valor_por_hora, valor_por_mes FROM salas WHERE id_sala = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_sala]);
    $sala = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($sala) {
        // Armazena os dados da sala em variáveis para exibir no formulário
        $nome = $sala['nome_sala'];
        $tipo = $sala['tipo_sala'];
        $largura = $sala['largura_sala'];
        $comprimento = $sala['comprimento_sala'];
        $capacidade = $sala['capacidade_sala'];
        $acessibilidade_para_cadeirantes = $sala['acessibilidade_sala_para_cadeirantes'];
        $descricao = $sala['descricao_sala'];
        $localizacao = $sala['localizacao_sala'];
        $valor_por_hora = $sala['valor_por_hora'];
        $valor_por_mes = $sala['valor_por_mes'];
    } else {
        echo "Sala não encontrada.";
        exit;
    }
} else {
    echo "ID da sala não fornecido.";
    exit;
}

// Consultar tipos de sala
$sqlTipos = "SELECT id_tipo_sala, nome_tipo_sala FROM tipo_sala";
$resultTipos = $conn->query($sqlTipos)->fetchAll(PDO::FETCH_ASSOC);

// Consultar os recursos disponíveis
$sqlRecursos = "SELECT id_recurso, nome_recurso FROM recursos_disponiveis";
$resultRecursos = $conn->query($sqlRecursos)->fetchAll(PDO::FETCH_ASSOC);

// Consultar os recursos já associados à sala
$sqlRecursosSala = "SELECT id_recurso FROM sala_recurso WHERE id_sala = ?";
$stmtRecursosSala = $conn->prepare($sqlRecursosSala);
$stmtRecursosSala->execute([$id_sala]);
$recursos_sala = $stmtRecursosSala->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="../styles/style.css">
    
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
    <form action="../acoes/atualizar_sala.php" method="POST">
        <h2>Editar Sala</h2>
        <br>
        <div class="cadastro">
            <!-- Campo oculto para enviar o ID da sala -->
            <input type="hidden" name="id_sala" value="<?php echo $id_sala; ?>">

            <label for="nome">Nome da Sala:</label>
            <input type="text" id="nome" name="nome_sala" value="<?php echo $nome; ?>" required>

            <label for="tipo">Tipo de Sala:</label>
            <select id="tipo" name="tipo_sala" required>
                <?php
                if (count($resultTipos) > 0) {
                    foreach ($resultTipos as $rowTipo) {
                        $selected = ($tipo == $rowTipo['id_tipo_sala']) ? 'selected' : '';
                        echo "<option value='" . $rowTipo['id_tipo_sala'] . "' $selected>" . $rowTipo['nome_tipo_sala'] . "</option>";
                    }
                } else {
                    echo "<option value=''>Nenhum tipo de sala encontrado</option>";
                }
                ?>
            </select>

            <label for="largura">Largura (m):</label>
            <input type="number" id="largura" name="largura_sala" value="<?php echo $largura; ?>" required>

            <label for="comprimento">Comprimento (m):</label>
            <input type="number" id="comprimento" name="comprimento_sala" value="<?php echo $comprimento; ?>" required>

            <label for="capacidade">Capacidade:</label>
            <input type="number" id="capacidade" name="capacidade_sala" value="<?php echo $capacidade; ?>" required>

            <label for="acessibilidade">Acessibilidade para Cadeirantes:</label>
            <input type="hidden" name="acessibilidade_sala_para_cadeirantes" value="0">
            <input type="checkbox" id="acessibilidade" name="acessibilidade_para_cadeirantes" value="1" <?php if ($acessibilidade_para_cadeirantes) echo 'checked'; ?>>

            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao_sala" required><?php echo $descricao; ?></textarea>

            <label for="localizacao">Localização:</label>
            <input type="text" id="localizacao" name="localizacao_sala" value="<?php echo $localizacao; ?>" required>

            <label for="valor_por_hora">Valor por Hora:</label>
            <input type="number" id="valor_por_hora" name="valor_por_hora" value="<?php echo $valor_por_hora; ?>" step="0.01" required>

            <label for="valor_por_mes">Valor por Mês:</label>
            <input type="number" id="valor_por_mes" name="valor_por_mes" value="<?php echo $valor_por_mes; ?>" step="0.01" required>
        </div><br><br>

        <h3>Recursos Disponíveis na Sala</h3><br><hr><br>
        <div class="recursos">
            <?php
            if (count($resultRecursos) > 0) {
                foreach ($resultRecursos as $rowRecurso) {
                    $checked = in_array($rowRecurso['id_recurso'], $recursos_sala) ? 'checked' : '';
                    echo "<input type='checkbox' id='recurso_" . $rowRecurso['id_recurso'] . "' name='recursos[]' value='" . $rowRecurso['id_recurso'] . "' $checked>";
                    echo "<label for='recurso_" . $rowRecurso['id_recurso'] . "'>" . $rowRecurso['nome_recurso'] . "</label><br>";
                }
            } else {
                echo "Nenhum recurso disponível.";
            }
            ?>
        </div><br><br>
        
        <div class="bot">
            <a href="./sala.php" class="btncad del">Cancelar</a>
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



