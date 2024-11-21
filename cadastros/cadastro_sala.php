<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_sala = $_POST['nome_sala'];
    $tipo_sala = $_POST['tipo_sala'];
    $largura_sala = $_POST['largura_sala'];
    $comprimento_sala = $_POST['comprimento_sala'];
    $capacidade_sala = $_POST['capacidade_sala'];
    $valor_por_hora = $_POST['valor_por_hora'];
    $valor_por_mes = $_POST['valor_por_mes'];
    $acessibilidade_para_cadeirantes_sala = isset($_POST['acessibilidade_para_cadeirantes_sala']) ? 1 : 0;
    $descricao_sala = $_POST['descricao_sala'];
    $localizacao_sala = $_POST['localizacao_sala'];
    $recursos_selecionados = isset($_POST['recursos']) ? $_POST['recursos'] : [];

    // Verificar se todos os campos obrigatórios foram preenchidos
    if (empty($nome_sala) || empty($tipo_sala) || empty($largura_sala) || empty($comprimento_sala) || empty($capacidade_sala) || empty($valor_por_hora) || empty($valor_por_mes) || empty($descricao_sala) || empty($localizacao_sala)) {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.history.back();
        </script>";
        exit();
    }

    // Iniciar uma transação
    $conn->beginTransaction();

    try {
        // Consulta SQL para inserir a nova sala
        $sqlSala = "INSERT INTO salas (nome_sala, tipo_sala, largura_sala, comprimento_sala, capacidade_sala, acessibilidade_sala_para_cadeirantes, descricao_sala, localizacao_sala, valor_por_hora, valor_por_mes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtSala = $conn->prepare($sqlSala);
        $stmtSala->execute([$nome_sala, $tipo_sala, $largura_sala, $comprimento_sala, $capacidade_sala, $acessibilidade_para_cadeirantes_sala, $descricao_sala, $localizacao_sala, $valor_por_hora, $valor_por_mes]);

        // Obter o ID da sala recém-cadastrada
        $id_sala = $conn->lastInsertId();

        // Inserir os recursos selecionados na tabela `sala_recurso`
        if (!empty($recursos_selecionados)) {
            $sqlRecurso = "INSERT INTO sala_recurso (id_sala, id_recurso) VALUES (?, ?)";
            $stmtRecurso = $conn->prepare($sqlRecurso);
            foreach ($recursos_selecionados as $id_recurso) {
                $stmtRecurso->execute([$id_sala, $id_recurso]);
            }
        }

        // Confirmar a transação
        $conn->commit();

        echo "<script>
            alert('Sala cadastrada com sucesso!');
            window.location.href = '../templateshtml/sala.php';
        </script>";

    } catch (Exception $e) {
        // Reverter a transação em caso de erro
        $conn->rollBack();
        echo "<script>
            alert('Erro ao cadastrar a sala: " . $e->getMessage() . "');
            window.history.back();
        </script>";
    }
}
?>
