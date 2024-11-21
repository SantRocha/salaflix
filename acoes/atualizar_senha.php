<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = intval($_POST['id_usuario']);
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Verificar se a nova senha e a confirmação são iguais
    if ($nova_senha !== $confirmar_senha) {
        echo "<script>
            alert('A nova senha e a confirmação da nova senha não coincidem.');
            window.history.back();
        </script>";
        exit();
    }

    // Buscar a senha atual no banco de dados
    $sql = "SELECT senha_usuario FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id_usuario]);
    $senha_hash_atual = $stmt->fetchColumn();

    // Verificar se a senha atual está correta
    if (!password_verify($senha_atual, $senha_hash_atual)) {
        echo "<script>
            alert('A senha atual está incorreta.');
            window.history.back();
        </script>";
        exit();
    }

    // Criptografar a nova senha
    $nova_senha_criptografada = password_hash($nova_senha, PASSWORD_BCRYPT);

    // Atualizar a senha no banco de dados
    $sql = "UPDATE usuarios SET senha_usuario = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);

    // Executar a atualização e exibir a mensagem
    if ($stmt->execute([$nova_senha_criptografada, $id_usuario])) {
        echo "<script>
            alert('Senha atualizada com sucesso!');
            window.location.href = '../templateshtml/perfil.php';
        </script>";
    } else {
        echo "<script>
            alert('Erro ao atualizar a senha: " . $stmt->errorInfo()[2] . "');
            window.history.back();
        </script>";
    }

    $conn = null; // Fechar a conexão
}
?>
