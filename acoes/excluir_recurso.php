<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexao/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_recurso'])) {
    $idRecurso = intval($_POST['id_recurso']);

    $sql = "DELETE FROM recursos_disponiveis WHERE id_recurso = ?";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$idRecurso])) {
            echo 'success';
        } else {
            $errorInfo = $stmt->errorInfo();
            // Verificar erro de chave estrangeira
            if (strpos($errorInfo[2], 'foreign key constraint') !== false) {
                echo 'Erro: Não é possível excluir este recurso, pois ele está vinculado a outras tabelas.';
            } else {
                echo 'Erro ao excluir o recurso: ' . $errorInfo[2];
            }
        }
    } catch (PDOException $e) {
        echo 'Erro ao preparar a consulta: ' . $e->getMessage();
    }

    $conn = null; // Fechar a conexão
} else {
    echo 'ID do recurso não fornecido.';
}
?>
