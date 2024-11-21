<?php

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST['nome_usuario'];
    $nick_usuario = $_POST['nick_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $telefone_usuario = $_POST['telefone_usuario'];
    $senha_usuario = $_POST['senha_usuario'];

    $funcao_usuario = isset($_POST['funcao_usuario']) ? $_POST['funcao_usuario'] : 'cliente';

    // Verificar se todos os campos foram preenchidos
    if (empty($nome_usuario) || empty($nick_usuario) || empty($email_usuario) || empty($senha_usuario) || empty($telefone_usuario) || empty($funcao_usuario)) {
        echo "<script>
            alert('Por favor, preencha todos os campos.');
            window.history.back();
        </script>";
        exit();
    }

    try {
        // Verificar se o email ou nickname já existem no banco de dados
        $sqlVerificar = "SELECT COUNT(*) FROM usuarios WHERE email_usuario = ? OR nick_usuario = ?";
        $stmtVerificar = $conn->prepare($sqlVerificar);
        $stmtVerificar->execute([$email_usuario, $nick_usuario]);
        $contagem = $stmtVerificar->fetchColumn();

        if ($contagem > 0) {
            echo "<script>
                alert('Email ou Usuario já estão em uso. Por favor, escolha outros.');
                window.history.back();
            </script>";
            exit();
        }

        // Criptografar a senha antes de inserir
        $senha_criptografada = password_hash($senha_usuario, PASSWORD_BCRYPT);

        // Consulta SQL para inserir o usuário
        $sql = "INSERT INTO usuarios (nome_usuario, nick_usuario, email_usuario, senha_usuario, telefone_usuario, funcao_usuario) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        
        // Executar a inserção
        if ($stmt->execute([$nome_usuario, $nick_usuario, $email_usuario, $senha_criptografada, $telefone_usuario, $funcao_usuario])) {
            echo "<script>
                alert('Usuário cadastrado com sucesso!');
                window.location.href = '../templateshtml/usuarios.php';
            </script>";
        } else {
            echo "<script>
                alert('Erro ao cadastrar o usuário.');
                window.history.back();
            </script>";
        }
    } catch (PDOException $e) {
        echo "<script>
            alert('Erro ao realizar a operação: " . $e->getMessage() . "');
            window.history.back();
        </script>";
    }
}
?>
