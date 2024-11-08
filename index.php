<?php
// calendario_investimento.php
session_start();

$days = 200; // Total number of days
$targetTotal = array_sum(range(1, $days)); // Target total amount based on day sums
$customDeposits = isset($_SESSION['customDeposits']) ? $_SESSION['customDeposits'] : [];
$currentTotal = array_sum($customDeposits);
$remainingAmount = $targetTotal - $currentTotal;
$progressPercentage = ($currentTotal / $targetTotal) * 100;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selectedDay = (int)$_POST['selectedDay'];
    if (isset($_POST['clear_history'])) {
        // Clear investment history
        session_unset();
        session_destroy();
        session_start();
        $customDeposits = [];
        $currentTotal = 0;
        $remainingAmount = $targetTotal;
        $progressPercentage = 0;
    } elseif ($selectedDay > 0 && $selectedDay <= $days) {
        if (isset($customDeposits[$selectedDay])) {
            // If the day is already marked, unmark it
            unset($customDeposits[$selectedDay]);
        } else {
            // Mark the day with the respective value
            $customDeposits[$selectedDay] = $selectedDay;
        }
        $_SESSION['customDeposits'] = $customDeposits;
        $currentTotal = array_sum($customDeposits);
        $remainingAmount = $targetTotal - $currentTotal;
        $progressPercentage = ($currentTotal / $targetTotal) * 100;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Investimento Interativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: black;
        }
        h3{
            text-align: center;
        }
        .container {
            margin-top: 50px;
        }
        .calendar {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 2px;
        }
        .calendar-day {
            width: 100%;
            aspect-ratio: 1/1;
            padding: 10px;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            text-align: center;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }
        .calendar-day:hover {
            background-color: #d4edda;
        }
        .calendar-day.invested {
            background-color: #28a745;
            color: #fff;
        }
        footer {
            margin-top: 30px;
            text-align: center;
            background-color: gray;
            padding: 15px;
        }
        .whatsapp-link {
            color: #25d366;
            text-decoration: none;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3>Calendário de Investimento Interativo</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progressPercentage; ?>%;" aria-valuenow="<?php echo $progressPercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <p><strong>Saldo atual:</strong> R$ <?php echo number_format($currentTotal, 2, ',', '.'); ?></p>
                    <p><strong>Faltando para a meta:</strong> R$ <?php echo number_format($remainingAmount, 2, ',', '.'); ?></p>
                </div>
                <div class="calendar">
                    <?php for ($i = 1; $i <= $days; $i++): ?>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Deseja marcar/desmarcar o dia <?php echo $i; ?>?');">
                            <input type="hidden" name="selectedDay" value="<?php echo $i; ?>">
                            <button type="submit" class="calendar-day <?php echo isset($customDeposits[$i]) ? 'invested' : ''; ?>">
                                Dia <?php echo $i; ?><br>
                                <small><?php echo isset($customDeposits[$i]) ? 'X' : ('R$ ' . number_format($i, 2, ',', '.')); ?></small>
                            </button>
                        </form>
                    <?php endfor; ?>
                </div>
            </div>
        </div>

        <form method="post">
            <button type="submit" name="clear_history" class="btn btn-danger">Limpar Histórico e Reiniciar</button>
        </form>

        <footer>
            <p>Desenvolvido por Danilo Donizete</p>
            <p>Telefone: <a href="https://wa.me/55199936699811" target="_blank" class="whatsapp-link"><i class="bi bi-whatsapp"></i> 19 99366-99811</a></p>
            <p>&copy; <?php echo date('Y'); ?> Todos os direitos reservados.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.js"></script>
</body>
</html>
