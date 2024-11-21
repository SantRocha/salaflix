<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados
include '../conexao/db_connection.php';

require_once '../login/proteger_pagina.php';

// Protege a página e obtém os dados do usuário logado
$usuario = proteger_pagina();
$id_usuario_logado = $usuario->id;

// Consulta SQL para buscar todas as reservas do usuário logado e as respectivas salas
$sqlReservas = "
    SELECT r.id_reserva, s.nome_sala, r.data_hora_aluguel, r.data_hora_entrega, r.duracao, r.valor
    FROM reservas r
    INNER JOIN salas s ON r.id_sala = s.id_sala
    WHERE r.id_usuario = ?
";
$stmt = $conn->prepare($sqlReservas);
$stmt->execute([$id_usuario_logado]);
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<table cellspacing="0" cellpadding="9" style="margin: 20px auto; width: 90%; text-align: center;">
    <thead>
        <tr style="font-size: 1.3em; font-weight: bold;">
            <td colspan="6">Minhas Reservas</td>
        </tr>
        <tr>
            <th>Sala</th>
            <th>Data e Hora do Aluguel</th>
            <th>Data e Hora da Entrega</th>
            <th>Duração</th>
            <th>Valor</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        // Verificar se há resultados para exibir
        if (count($reservas) > 0) {
            // Iterar sobre cada reserva e exibir as informações na tabela
            foreach ($reservas as $reserva) {
                // Formatar as datas no formato dd/mm/aa hh:mm
                $dataAluguel = date('d/m/Y H:i', strtotime($reserva["data_hora_aluguel"]));
                $dataEntrega = date('d/m/Y H:i', strtotime($reserva["data_hora_entrega"]));

                echo "<tr id='reserva-" . $reserva["id_reserva"] . "'>";
                echo "<td>" . $reserva["nome_sala"] . "</td>";
                echo "<td>" . $dataAluguel . "</td>";
                echo "<td>" . $dataEntrega . "</td>";
                echo "<td>" . ucfirst($reserva["duracao"]) . "</td>";
                echo "<td>R$ " . number_format($reserva["valor"], 2, ',', '.') . "</td>";

                echo "<td>";
                echo "<button class='excluir tooltip' data-id='" . $reserva["id_reserva"] . "'><img class='imgtooltip' src='../imagens/excluir.png' alt='excluir'><span class='tooltip-text'>Excluir</span></button>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Nenhuma reserva encontrada.</td></tr>";
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
                const reservaId = this.getAttribute('data-id');

                // Exibir um alerta de confirmação
                if (confirm('Tem certeza de que deseja excluir esta reserva?')) {
                    // Enviar requisição para excluir a reserva
                    fetch('../acoes/excluir_reserva.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_reserva=${reservaId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            // Remover a linha da tabela da reserva excluída
                            const row = document.getElementById(`reserva-${reservaId}`);
                            if (row) {
                                row.remove();
                            }
                            alert('Reserva excluída com sucesso!');
                        } else {
                            // Verificar possíveis erros conhecidos
                            alert('Erro ao excluir a reserva: ' + data);
                        }
                    })
                    .catch(error => console.error('Erro ao excluir a reserva!', error));
                }
            });
        });
    });
</script>
