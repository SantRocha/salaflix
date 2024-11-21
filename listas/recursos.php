<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

// Consulta SQL para buscar todos os recursos
$sqlRecursos = "SELECT id_recurso, nome_recurso FROM recursos_disponiveis";
$stmt = $conn->query($sqlRecursos);

// Construir o array associativo para armazenar os recursos, se necessário
$recursos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<table cellspacing="0" cellpadding="9" style="margin: 20px auto; width: 80%; text-align: center;">
    <thead>
        <tr style="font-size: 1.3em; font-weight: bold;">
            <td colspan="4">Recursos Disponíveis</td>
        </tr>
        <tr>
            <th>Nome do Recurso</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // Verificar se há resultados para exibir
        if (count($recursos) > 0) {
            // Iterar sobre cada recurso e exibir as informações na tabela
            foreach ($recursos as $recurso) {
                echo "<tr id='recurso-" . htmlspecialchars($recurso["id_recurso"]) . "'>";
                echo "<td>" . htmlspecialchars($recurso["nome_recurso"]) . "</td>";
                
                echo "<td>";
                echo "<button class='editar tooltip' data-id='" . htmlspecialchars($recurso["id_recurso"]) . "'><img class='imgtooltip' src='../imagens/editar.png' alt='editar'><span class='tooltip-text'>Editar</span></button> ";
                echo "<button class='excluir tooltip' data-id='" . htmlspecialchars($recurso["id_recurso"]) . "'><img class='imgtooltip' src='../imagens/excluir.png' alt='excluir'><span class='tooltip-text'>Excluir</span></button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Nenhum recurso encontrado.</td></tr>";
        }

        // Fechar a conexão com o banco de dados
        $conn = null;
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
                const recursoId = this.getAttribute('data-id');

                // Exibir um alerta de confirmação
                if (confirm('Tem certeza de que deseja excluir este recurso?')) {
                    // Enviar requisição para excluir o recurso
                    fetch('../acoes/excluir_recurso.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_recurso=${recursoId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            // Remover a linha da tabela do recurso excluído
                            const row = document.getElementById(`recurso-${recursoId}`);
                            if (row) {
                                row.remove();
                            }
                            alert('Recurso excluído com sucesso!');
                        } else {
                            if (data.includes('foreign key constraint')) {
                                alert('Erro: Não é possível excluir este recurso, pois ele está vinculado a uma sala.');
                            } else {
                                alert('Erro ao excluir o recurso: ' + data);
                                console.log(data);
                            }
                        }
                    })
                    .catch(error => console.error('Erro ao excluir o recurso:', error));
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
                const recursoId = this.getAttribute('data-id');

                // Redirecionar para a página de edição com o ID do recurso na URL
                window.location.href = `editar_recurso.php?id_recurso=${recursoId}`;
            });
        });
    });
</script>
