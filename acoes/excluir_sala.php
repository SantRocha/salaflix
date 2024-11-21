<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o ID da sala foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_sala'])) {
    $idSala = intval($_POST['id_sala']);

    // Iniciar uma transação para garantir que a exclusão da sala e dos recursos relacionados seja atômica
    $conn->beginTransaction();

    try {
        // Excluir os recursos associados à sala na tabela `sala_recurso`
        $sqlRecursos = "DELETE FROM sala_recurso WHERE id_sala = ?";
        $stmtRecursos = $conn->prepare($sqlRecursos);
        $stmtRecursos->execute([$idSala]);

        // Excluir a sala da tabela `salas`
        $sqlSala = "DELETE FROM salas WHERE id_sala = ?";
        $stmtSala = $conn->prepare($sqlSala);
        $stmtSala->execute([$idSala]);

        // Se tudo correr bem, fazer commit
        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        // Em caso de erro, fazer rollback
        $conn->rollBack();
        echo "Erro ao excluir a sala: " . $e->getMessage();
    }
} else {
    echo 'ID da sala não fornecido.';
}
?>
