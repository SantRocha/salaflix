<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o formulário foi enviado corretamente
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_recurso = intval($_POST['id_recurso']);
    $nome_recurso = $_POST['nome_recurso'];

    // Verificar se todos os campos estão preenchidos
    if (empty($nome_recurso)) {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.history.back();
        </script>";
        exit();
    }

    // Atualizar o recurso no banco de dados
    $sql = "UPDATE recursos_disponiveis SET nome_recurso = ? WHERE id_recurso = ?";
    $stmt = $conn->prepare($sql);

    // Executar a consulta e verificar o sucesso
    if ($stmt->execute([$nome_recurso, $id_recurso])) {
        // Redirecionar e exibir alerta de sucesso
        echo "<script>
            alert('Recurso atualizado com sucesso!');
            window.location.href = '../templateshtml/recursos.php';
        </script>";
    } else {
        // Exibir erro em caso de falha
        $errorInfo = $stmt->errorInfo();
        echo "<script>
            alert('Erro ao atualizar o recurso: " . $errorInfo[2] . "');
            window.history.back();
        </script>";
    }

    $conn = null; // Fechar a conexão
} else {
    echo "<script>
        alert('Requisição inválida.');
        window.history.back();
    </script>";
}
?>
