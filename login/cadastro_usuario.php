<?php
require '../conexao/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = $_POST['senha'];

    // Verificar se o email ou nome de usuário já existem
    $sqlVerificar = "SELECT COUNT(*) FROM usuarios WHERE email_usuario = ? OR nick_usuario = ?";
    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->execute([$email, $usuario]);
    $contagem = $stmtVerificar->fetchColumn();

    if ($contagem > 0) {
        echo "<script>alert('Email ou nome de usuário já estão em uso.'); window.history.back();</script>";
        exit();
    }

    // Criptografar a senha antes de salvar
    $senhaCriptografada = password_hash($senha, PASSWORD_BCRYPT);

    // Inserir novo usuário no banco
    $sqlInserir = "INSERT INTO usuarios (nome_usuario, nick_usuario, email_usuario, telefone_usuario, senha_usuario) VALUES (?, ?, ?, ?, ?)";
    $stmtInserir = $conn->prepare($sqlInserir);

    if ($stmtInserir->execute([$nome, $usuario, $email, $telefone, $senhaCriptografada])) {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar.'); window.history.back();</script>";
    }
}
?>
