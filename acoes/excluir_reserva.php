<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../conexao/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reserva'])) {
    $idReserva = intval($_POST['id_reserva']);

    $sql = "DELETE FROM reservas WHERE id_reserva = ?";
    
    try {
        $stmt = $conn->prepare($sql);

        if ($stmt->execute([$idReserva])) {
            echo 'success';
        } else {
            $errorInfo = $stmt->errorInfo();
            // Verificar erro de chave estrangeira
            if (strpos($errorInfo[2], 'foreign key constraint') !== false) {
                echo 'Erro: Não é possível excluir esta reserva, pois ela está vinculada a outras tabelas.';
            } else {
                echo 'Erro ao excluir a reserva: ' . $errorInfo[2];
            }
        }

    } catch (PDOException $e) {
        echo 'Erro ao preparar a consulta: ' . $e->getMessage();
    }
} else {
    echo 'ID da reserva não fornecido.';
}
?>
