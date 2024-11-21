<?php

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_recurso = $_POST['nome_recurso'];

    // Verificar se o nome do recurso foi preenchido
    if (empty($nome_recurso)) {
        echo "<script>
            alert('Por favor, preencha o nome do recurso.');
            window.history.back();
        </script>";
        exit();
    }

    // Verificar se o nome do recurso já existe no banco de dados
    $sqlVerificar = "SELECT COUNT(*) FROM recursos_disponiveis WHERE nome_recurso = ?";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->execute([$nome_recurso]);
    $contagem = $stmtVerificar->fetchColumn();

    if ($contagem > 0) {
        echo "<script>
            alert('Este recurso já está cadastrado. Por favor, escolha outro nome.');
            window.history.back();
        </script>";
        exit();
    }

    // Consulta SQL para inserir o recurso
    $sql = "INSERT INTO recursos_disponiveis (nome_recurso) VALUES (?)";
    $stmt = $conn->prepare($sql);

    // Executar a inserção
    if ($stmt->execute([$nome_recurso])) {
        // Exibir alerta de sucesso e redirecionar
        echo "<script>
            alert('Recurso cadastrado com sucesso!');
            window.location.href = '../templateshtml/recursos.php';
        </script>";
    } else {
        // Exibir alerta de erro em caso de falha
        $errorInfo = $stmt->errorInfo();
        echo "<script>
            alert('Erro ao cadastrar o recurso: " . $errorInfo[2] . "');
            window.history.back();
        </script>";
    }

    $conn = null; // Fechar a conexão
}
?>
