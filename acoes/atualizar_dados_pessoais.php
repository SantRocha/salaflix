<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = intval($_POST['id_usuario']);
    $nome_usuario = $_POST['nome_usuario'];
    $nick_usuario = $_POST['nick_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $telefone_usuario = $_POST['telefone_usuario'];

    // Consulta SQL para atualizar os dados pessoais
    $sql = "UPDATE usuarios SET nome_usuario = ?, nick_usuario = ?, email_usuario = ?, telefone_usuario = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);

    // Executar a atualização e exibir a mensagem
    if ($stmt->execute([$nome_usuario, $nick_usuario, $email_usuario, $telefone_usuario, $id_usuario])) {
        // Exibir alerta de sucesso e redirecionar para o perfil
        echo "<script>
            alert('Dados atualizados com sucesso!');
            window.location.href = '../templateshtml/perfil.php';
        </script>";
    } else {
        // Exibir erro em caso de falha na atualização
        $errorInfo = $stmt->errorInfo();
        echo "<script>
            alert('Erro ao atualizar os dados: " . $errorInfo[2] . "');
            window.history.back();
        </script>";
    }

    $conn = null; // Fechar a conexão
}
?>
