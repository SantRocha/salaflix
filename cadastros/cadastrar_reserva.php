<?php
// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão com o banco de dados e a proteção da página
include '../conexao/db_connection.php';
require '../login/proteger_pagina.php';

// Proteger a página e obter o ID do usuário logado
$usuario = proteger_pagina();
$id_usuario = $usuario->id;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_sala = $_POST['id_sala'];
    $data_aluguel = $_POST['data_aluguel']; // Data do aluguel
    $hora_aluguel = $_POST['hora_aluguel']; // Hora do aluguel
    $data_entrega = $_POST['data_entrega']; // Data da entrega
    $hora_entrega = $_POST['hora_entrega']; // Hora da entrega

    // Concatenar data e hora para criar o formato DATETIME
    $data_hora_aluguel = $data_aluguel . ' ' . $hora_aluguel;
    $data_hora_entrega = $data_entrega . ' ' . $hora_entrega;

    // Validar se as datas possuem um formato correto
    try {
        $inicio_reserva = new DateTime($data_hora_aluguel);
        $fim_reserva = new DateTime($data_hora_entrega);
    } catch (Exception $e) {
        echo "<script>
            alert('Formato de data inválido. Por favor, verifique as datas e tente novamente.');
            window.history.back();
        </script>";
        exit;
    }

    // Verificar se a data e hora da reserva estão no passado
    $data_atual = new DateTime();

    if ($inicio_reserva < $data_atual) {
        echo "<script>
            alert('Você não pode reservar uma sala para o passado. Selecione uma data futura.');
            window.history.back();
        </script>";
        exit;
    }

    // Verificar horário de funcionamento (segunda a sexta das 08:00 às 18:00, sábado das 08:00 às 12:00)
    $dia_semana_aluguel = $inicio_reserva->format('w'); // 0 = domingo, 6 = sábado
    $dia_semana_entrega = $fim_reserva->format('w');

    $horario_inicio_validade = new DateTime($inicio_reserva->format('Y-m-d') . ' 08:00');
    $horario_fim_validade = ($dia_semana_aluguel == 6) 
        ? new DateTime($inicio_reserva->format('Y-m-d') . ' 12:00') // Sábado até 12:00
        : new DateTime($inicio_reserva->format('Y-m-d') . ' 18:00'); // Dias úteis até 18:00

    // Verificar se o horário de início está fora do horário permitido
    if ($inicio_reserva < $horario_inicio_validade || $inicio_reserva > $horario_fim_validade) {
        echo "<script>
            alert('Horário de funcionamento inválido. As salas funcionam de segunda a sexta das 08:00 às 18:00 e aos sábados das 08:00 às 12:00.');
            window.history.back();
        </script>";
        exit;
    }

    // Verificar horário de entrega com base no dia da semana
    $horario_inicio_validade_entrega = new DateTime($fim_reserva->format('Y-m-d') . ' 08:00');
    $horario_fim_validade_entrega = ($dia_semana_entrega == 6) 
        ? new DateTime($fim_reserva->format('Y-m-d') . ' 12:00') // Sábado até 12:00
        : new DateTime($fim_reserva->format('Y-m-d') . ' 18:00'); // Dias úteis até 18:00

    if ($fim_reserva < $horario_inicio_validade_entrega || $fim_reserva > $horario_fim_validade_entrega) {
        echo "<script>
            alert('Horário de funcionamento inválido para a entrega.');
            window.history.back();
        </script>";
        exit;
    }

    // Verificar se a sala já está reservada nesse período
    $sqlVerificar = "
        SELECT data_hora_aluguel, data_hora_entrega FROM reservas
        WHERE id_sala = ? 
        AND (
            (data_hora_aluguel <= ? AND data_hora_entrega >= ?) OR 
            (data_hora_aluguel <= ? AND data_hora_entrega >= ?)
        )
    ";

    $stmtVerificar = $conn->prepare($sqlVerificar);
    $stmtVerificar->execute([$id_sala, $data_hora_entrega, $data_hora_aluguel, $data_hora_aluguel, $data_hora_entrega]);
    $reserva = $stmtVerificar->fetch(PDO::FETCH_ASSOC);

    if ($reserva) {
        $reserva_aluguel_formatada = date('d/m/Y H:i', strtotime($reserva['data_hora_aluguel']));
        $reserva_entrega_formatada = date('d/m/Y H:i', strtotime($reserva['data_hora_entrega']));

        echo "<script>
            alert('Esta sala já está reservada do dia $reserva_aluguel_formatada até o dia $reserva_entrega_formatada.');
            window.history.back();
        </script>";
        exit;
    }

    // Calcular duração e valor
    $intervalo = $inicio_reserva->diff($fim_reserva);

    if ($intervalo->days >= 30) {
        $duracao = 'mes';
        $sqlValor = "SELECT valor_por_mes FROM salas WHERE id_sala = ?";
    } else {
        $duracao = 'hora';
        $sqlValor = "SELECT valor_por_hora FROM salas WHERE id_sala = ?";
    }

    $stmtValor = $conn->prepare($sqlValor);
    $stmtValor->execute([$id_sala]);
    $valor_unitario = $stmtValor->fetchColumn();

    // Calcular o valor total
    if ($duracao === 'mes') {
        $meses_completos = floor($intervalo->days / 30);
        $dias_extras = $intervalo->days % 30;
        $valor_total = $meses_completos * $valor_unitario;
        if ($dias_extras > 0) {
            $valor_total += ($dias_extras / 30) * $valor_unitario;
        }
    } else {
        $horas_totais = calcular_horas_validas($inicio_reserva, $fim_reserva);
        $valor_total = $horas_totais * $valor_unitario;
    }

    // Inserir a reserva
    $sqlInserir = "
        INSERT INTO reservas (id_usuario, id_sala, data_hora_aluguel, data_hora_entrega, duracao, valor)
        VALUES (?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($sqlInserir);
    if ($stmt->execute([$id_usuario, $id_sala, $data_hora_aluguel, $data_hora_entrega, $duracao, $valor_total])) {
        echo "<script>
            alert('Reserva feita com sucesso!');
            window.location.href = '../templateshtml/minhasReservas.php';
        </script>";
    } else {
        echo "<script>
            alert('Erro ao fazer a reserva: " . $stmt->errorInfo()[2] . "');
            window.history.back();
        </script>";
    }
}


/**
 * Função para calcular o número de horas válidas entre duas datas, considerando o horário de funcionamento da sala
 * (de 8:00 às 18:00 de segunda a sexta e 8:00 às 12:00 aos sábados)
 */
function calcular_horas_validas(DateTime $inicio, DateTime $fim) {
    $horas_totais = 0;

    // Criar intervalos diários para cálculo
    $intervalo = new DateInterval('P1D');
    $fim_periodo = (clone $fim)->modify('+1 day'); // Incluir o último dia no cálculo
    $periodo = new DatePeriod($inicio, $intervalo, $fim_periodo);

    foreach ($periodo as $dia) {
        $dia_semana = $dia->format('w'); // Dia da semana: 0 = domingo, 6 = sábado

        // Se for domingo, continuar
        if ($dia_semana == 0) {
            continue;
        }

        // Horários de abertura e fechamento para o dia
        $horario_abertura = new DateTime($dia->format('Y-m-d') . ' 08:00');
        if ($dia_semana == 6) {
            $horario_fechamento = new DateTime($dia->format('Y-m-d') . ' 12:00'); // Sábado
        } else {
            $horario_fechamento = new DateTime($dia->format('Y-m-d') . ' 18:00'); // Dias úteis
        }

        // Ajustar o horário de abertura e fechamento para o primeiro e último dia da reserva
        if ($dia->format('Y-m-d') === $inicio->format('Y-m-d')) {
            $horario_abertura = $inicio; // Início da reserva
        }
        if ($dia->format('Y-m-d') === $fim->format('Y-m-d')) {
            $horario_fechamento = $fim; // Fim da reserva
        }

        // Calcular as horas válidas deste dia
        if ($horario_abertura < $horario_fechamento) {
            $intervalo_dia = $horario_abertura->diff($horario_fechamento);
            $horas_totais += $intervalo_dia->h + ($intervalo_dia->i / 60); // Incluir minutos na contagem
        }
    }

    return $horas_totais;
}
?>
