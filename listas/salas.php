<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include '../conexao/db_connection.php';

    // Consulta SQL para buscar as salas
    $sqlSalas = "SELECT id_sala, nome_sala, tipo_sala, largura_sala, comprimento_sala, capacidade_sala, acessibilidade_sala_para_cadeirantes, descricao_sala, localizacao_sala, valor_por_hora, valor_por_mes FROM salas ORDER BY id_sala DESC";
    $stmtSalas = $conn->query($sqlSalas);
    $resultSalas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

    // Consulta SQL para buscar os recursos e suas relações com as salas
    $sqlRecursos = "
        SELECT sr.id_sala, rd.nome_recurso
        FROM sala_recurso sr
        INNER JOIN recursos_disponiveis rd ON sr.id_recurso = rd.id_recurso";
    $stmtRecursos = $conn->query($sqlRecursos);
    $resultRecursos = $stmtRecursos->fetchAll(PDO::FETCH_ASSOC);

    // Consulta SQL para buscar os tipos de sala e associar às salas
    $sqlTipo = "
        SELECT s.id_sala, ts.nome_tipo_sala
        FROM salas s
        INNER JOIN tipo_sala ts ON s.tipo_sala = ts.id_tipo_sala";
    $stmtTipo = $conn->query($sqlTipo);
    $resultTipo = $stmtTipo->fetchAll(PDO::FETCH_ASSOC);

    // Organizar os recursos por sala em um array associativo
    $salaRecursos = [];
    foreach ($resultRecursos as $row) {
        $salaRecursos[$row['id_sala']][] = $row['nome_recurso'];
    }

    // Construir o array associativo para armazenar os tipos de sala
    $salaTipo = [];
    foreach ($resultTipo as $row) {
        $salaTipo[$row['id_sala']] = $row['nome_tipo_sala'];
    }
?>

<table cellspacing="0" cellpadding="9">
    <thead>
        <tr style="font-size: 1.3em; font-weight: bold;">
            <td colspan="11">Salas</td>
        </tr>
        <tr>
            <th>Nome da Sala</th>
            <th>Tipo</th>
            <th>Tamanho</th>
            <th>Capacidade</th>
            <th>Recursos Disponíveis</th>
            <th>Acessibilidade</th>
            <th>Descrição</th>
            <th>Localização</th>
            <th>Valor por Hora</th>
            <th>Valor por Mês</th>
            <th>Ações</th>
        </tr>
    </thead>

    <tbody>
        <?php
        if (count($resultSalas) > 0) {
            foreach($resultSalas as $row) {
                echo "<tr id='sala-" . $row["id_sala"] . "'>";
                echo "<td>" . $row["nome_sala"] . "</td>";
                
                $tipo = isset($salaTipo[$row["id_sala"]]) ? $salaTipo[$row["id_sala"]] : "Nenhum";
                echo "<td>" . $tipo . "</td>";
                
                echo "<td>" . $row["largura_sala"] . " X " . $row["comprimento_sala"] . "</td>";
                echo "<td>" . $row["capacidade_sala"] . " pessoas</td>";
                
                $recursos = isset($salaRecursos[$row["id_sala"]]) ? implode(", ", $salaRecursos[$row["id_sala"]]) : "Nenhum";
                echo "<td>" . $recursos . "</td>";
                
                $acessibilidade = $row["acessibilidade_sala_para_cadeirantes"] ? "Sim" : "Não";
                echo "<td>" . $acessibilidade . "</td>";
                
                echo "<td>" . $row["descricao_sala"] . "</td>";
                echo "<td>" . $row["localizacao_sala"] . "</td>";
        
                // Exibir os valores por hora e por mês
                echo "<td>R$ " . number_format($row["valor_por_hora"], 2, ',', '.') . "</td>";
                echo "<td>R$ " . number_format($row["valor_por_mes"], 2, ',', '.') . "</td>";
        
                // Botões de ação
                echo "<td>";
        
                // Botão "Reservar" visível para todos
                echo "<button class='reservar tooltip' data-id='" . $row["id_sala"] . "'>
                    <img class='imgtooltip' src='../imagens/agenda.png' alt='reservar'>
                    <span class='tooltip-text'>Reservar</span>
                </button> ";
        
                // Verificar se o usuário tem permissão para ver os botões "Editar" e "Excluir"
                if ($permissao_funcionario) {
                    echo "<button class='editar tooltip' data-id='" . $row["id_sala"] . "'>
                            <img class='imgtooltip' src='../imagens/editar.png' alt='editar'>
                            <span class='tooltip-text'>Editar</span>
                          </button> ";
        
                    echo "<button class='excluir tooltip' data-id='" . $row["id_sala"] . "'>
                            <img class='imgtooltip' src='../imagens/excluir.png' alt='excluir'>
                            <span class='tooltip-text'>Excluir</span>
                          </button>";
                }
        
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='11'>Nenhuma sala encontrada.</td></tr>";
        }
        ?>
    </tbody>
</table>



<!-- Script para lidar com exclusão de sala -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selecionar todos os botões de exclusão
        const excluirButtons = document.querySelectorAll('.excluir');

        // Adicionar evento de clique a cada botão
        excluirButtons.forEach(button => {
            button.addEventListener('click', function () {
                const salaId = this.getAttribute('data-id');

                // Exibir um alerta de confirmação
                if (confirm('Tem certeza de que deseja excluir esta sala?')) {
                    // Enviar requisição para excluir a sala
                    fetch('../acoes/excluir_sala.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `id_sala=${salaId}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            // Remover a linha da tabela da sala excluída
                            const row = document.getElementById(`sala-${salaId}`);
                            if (row) {
                                row.remove();
                            }
                            alert('Sala excluída com sucesso!');
                        } else {
                            alert('Erro ao excluir a sala.');
                        }
                    })
                    .catch(error => console.error('Erro ao excluir a sala:', error));
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const editarButtons = document.querySelectorAll('.editar');

        editarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const salaId = this.getAttribute('data-id');
                window.location.href = `editar_sala.php?id_sala=${salaId}`;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Selecionar todos os botões de reserva
        const reservarButtons = document.querySelectorAll('.reservar');

        // Adicionar evento de clique a cada botão de reserva
        reservarButtons.forEach(button => {
            button.addEventListener('click', function () {
                const salaId = this.getAttribute('data-id');
                // Redirecionar para a página de reserva com o ID da sala
                window.location.href = `reservar_sala.php?id_sala=${salaId}`;
            });
        });
    });
</script>
