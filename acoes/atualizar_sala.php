<?php

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sala = intval($_POST['id_sala']);
    $nome = $_POST['nome_sala'];
    $tipo = intval($_POST['tipo_sala']);
    $largura = intval($_POST['largura_sala']);
    $comprimento = intval($_POST['comprimento_sala']);
    $capacidade = intval($_POST['capacidade_sala']);
    $acessibilidade_para_cadeirantes = isset($_POST['acessibilidade_para_cadeirantes']) ? 1 : 0;
    $descricao = $_POST['descricao_sala'];
    $localizacao = $_POST['localizacao_sala'];
    $valor_por_hora = floatval($_POST['valor_por_hora']);
    $valor_por_mes = floatval($_POST['valor_por_mes']);
    $recursos_selecionados = isset($_POST['recursos']) ? $_POST['recursos'] : [];

    // Iniciar uma transação
    $conn->beginTransaction();

    try {
        // Atualizar as informações da sala
        $sql = "UPDATE salas SET nome_sala=?, tipo_sala=?, largura_sala=?, comprimento_sala=?, capacidade_sala=?, acessibilidade_sala_para_cadeirantes=?, descricao_sala=?, localizacao_sala=?, valor_por_hora=?, valor_por_mes=? WHERE id_sala=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $tipo, $largura, $comprimento, $capacidade, $acessibilidade_para_cadeirantes, $descricao, $localizacao, $valor_por_hora, $valor_por_mes, $id_sala]);

        // Atualizar os recursos da sala
        $sqlDeleteRecursos = "DELETE FROM sala_recurso WHERE id_sala = ?";
        $stmtDeleteRecursos = $conn->prepare($sqlDeleteRecursos);
        $stmtDeleteRecursos->execute([$id_sala]);

        $sqlInsertRecursos = "INSERT INTO sala_recurso (id_sala, id_recurso) VALUES (?, ?)";
        $stmtInsertRecursos = $conn->prepare($sqlInsertRecursos);
        foreach ($recursos_selecionados as $id_recurso) {
            $stmtInsertRecursos->execute([$id_sala, $id_recurso]);
        }

        // Confirmar a transação
        $conn->commit();

        // Exibir alerta de sucesso e redirecionar
        echo "<script>
            alert('Sala atualizada com sucesso!');
            window.location.href = '../templateshtml/sala.php';
        </script>";

    } catch (Exception $e) {
        // Reverter a transação em caso de erro
        $conn->rollBack();
        echo "<script>
            alert('Erro ao atualizar a sala: " . $e->getMessage() . "');
            window.history.back();
        </script>";
    }
}
?>
