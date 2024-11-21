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
    $senha_usuario = $_POST['senha_usuario'];

    $funcao_usuario = isset($_POST['funcao_usuario']) ? $_POST['funcao_usuario'] : 'cliente';

    try {
        // Atualizar a senha apenas se ela for preenchida
        if (!empty($senha_usuario)) {
            $senha_criptografada = password_hash($senha_usuario, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET nome_usuario=?, nick_usuario=?, email_usuario=?, telefone_usuario=?, funcao_usuario=?, senha_usuario=? WHERE id_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nome_usuario, $nick_usuario, $email_usuario, $telefone_usuario, $funcao_usuario, $senha_criptografada, $id_usuario]);
        } else {
            $sql = "UPDATE usuarios SET nome_usuario=?, nick_usuario=?, email_usuario=?, telefone_usuario=?, funcao_usuario=? WHERE id_usuario=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nome_usuario, $nick_usuario, $email_usuario, $telefone_usuario, $funcao_usuario, $id_usuario]);
        }

        // Exibir alerta e redirecionar
        echo "<script>
            alert('Usuário atualizado com sucesso!');
            window.location.href = '../templateshtml/usuarios.php';
        </script>";
    } catch (PDOException $e) {
        // Exibir erro em caso de falha na atualização
        echo "<script>
            alert('Erro ao atualizar o usuário: " . $e->getMessage() . "');
            window.history.back();
        </script>";
    }
}
?>
