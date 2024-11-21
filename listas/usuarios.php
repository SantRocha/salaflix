<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Consulta SQL para buscar todos os usuários
$sqlUsuarios = "SELECT id_usuario, nome_usuario, nick_usuario, email_usuario, funcao_usuario FROM usuarios";
$stmt = $conn->query($sqlUsuarios);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<table cellspacing="0" cellpadding="9" style="margin: 20px auto; width: 80%; text-align: center;">
    <thead>
        <tr style="font-size: 1.3em; font-weight: bold;">
            <td colspan="6">Usuários</td>
        </tr>
        <tr>
            <th>Nome do Usuário</th>
            <th>Nickname</th>
            <th>Email</th>
            <th>Função</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // Verificar se há resultados para exibir
        if (count($usuarios) > 0) {
            // Iterar sobre cada usuário e exibir as informações na tabela
            foreach ($usuarios as $usuario) {
                echo "<tr id='usuario-" . htmlspecialchars($usuario["id_usuario"]) . "'>";
                echo "<td>" . htmlspecialchars($usuario["nome_usuario"]) . "</td>";
                echo "<td>" . htmlspecialchars($usuario["nick_usuario"]) . "</td>";
                echo "<td>" . htmlspecialchars($usuario["email_usuario"]) . "</td>";
                echo "<td>" . ucfirst(htmlspecialchars($usuario["funcao_usuario"])) . "</td>";
                
                echo "<td>";
                if ($usuario["funcao_usuario"] == "funcionario" or $usuario["funcao_usuario"] == "adm") {
                    if ($permissao_adm) {
                        echo "<button class='editar tooltip' data-id='" . htmlspecialchars($usuario["id_usuario"]) . "'><img class='imgtooltip' src='../imagens/editar.png' alt='editar'><span class='tooltip-text'>Editar</span></button> ";
                        echo "<button class='excluir tooltip' data-id='" . htmlspecialchars($usuario["id_usuario"]) . "'><img class='imgtooltip' src='../imagens/excluir.png' alt='excluir'><span class='tooltip-text'>Excluir</span></button>";
                    }
                } else {
                    echo "<button class='editar tooltip' data-id='" . htmlspecialchars($usuario["id_usuario"]) . "'><img class='imgtooltip' src='../imagens/editar.png' alt='editar'><span class='tooltip-text'>Editar</span></button> ";
                    if ($permissao_adm) {
                        echo "<button class='excluir tooltip' data-id='" . htmlspecialchars($usuario["id_usuario"]) . "'><img class='imgtooltip' src='../imagens/excluir.png' alt='excluir'><span class='tooltip-text'>Excluir</span></button>";
                    }
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Nenhum usuário encontrado.</td></tr>";
        }
        ?>
    </tbody>
</table>

<!-- Adicionar o script JavaScript para lidar com exclusão -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selecionar todos os botões de exclusão
        const excluirButtons = document.querySelectorAll('.excluir');

        // Adicionar evento de clique a cada botão
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                const usuarioId = this.getAttribute('data-id');

                // Exibir um alerta de confirmação
                if (confirm('Tem certeza de que deseja excluir este usuário?')) {
                    // Enviar requisição para excluir o usuário
                    fetch('../acoes/excluir_usuario.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_usuario=${usuarioId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            // Remover a linha da tabela do usuário excluído
                            const row = document.getElementById(`usuario-${usuarioId}`);
                            if (row) {
                                row.remove();
                            }
                            alert('Usuário excluído com sucesso!');
                        } else {
                            alert('Erro ao excluir o usuário.');
                        }
                    })
                    .catch(error => console.error('Erro ao excluir o usuário:', error));
                }
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selecionar todos os botões de edição
        const editarButtons = document.querySelectorAll('.editar');

        // Adicionar evento de clique a cada botão de edição
        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const usuarioId = this.getAttribute('data-id');

                // Redirecionar para a página de edição com o ID do usuário na URL
                window.location.href = `editar_usuario.php?id_usuario=${usuarioId}`;
            });
        });
    });
</script>
