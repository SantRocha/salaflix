<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexao/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $idUsuario = intval($_POST['id_usuario']);

    $sql = "DELETE FROM usuarios WHERE id_usuario = ?";

    try {
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$idUsuario])) {
            echo 'success';
        } else {
            $errorInfo = $stmt->errorInfo();
            echo 'Erro ao excluir o usuário: ' . $errorInfo[2];
        }
    } catch (PDOException $e) {
        echo 'Erro ao preparar a consulta: ' . $e->getMessage();
    }
} else {
    echo 'ID do usuário não fornecido.';
}
?>
