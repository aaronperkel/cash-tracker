<?php
require_once '../config.php';

$sql = "SELECT * FROM charges ORDER BY charge_date DESC";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);

$total = 0;
$aaron_owes = 0;
$riley_owes = 0;

foreach ($charges as $charge) {
    if ($charge['settled'] == 0) {
        $owed_amount = $charge['amount'] * ($charge['percentage'] / 100);
        if ($charge['payer'] == 'Aaron') {
            $riley_owes += $owed_amount;
        } else {
            $aaron_owes += $owed_amount;
        }
    }
}

$total = $riley_owes - $aaron_owes;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>Expense Tracker</h1>
    </header>

    <div class="container">
        <div class="balance">
            <?php if ($total > 0): ?>
                <h2>Riley owes Aaron $<?php echo number_format(abs($total), 2); ?></h2>
            <?php elseif ($total < 0): ?>
                <h2>Aaron owes Riley $<?php echo number_format(abs($total), 2); ?></h2>
            <?php else: ?>
                <h2>All settled up!</h2>
            <?php endif; ?>
        </div>

        <div class="charges">
            <div class="charge-form">
                <form action="add_charge.php" method="post">
                    <select name="payer" id="payer" required>
                        <option value="">Who paid?</option>
                        <option value="Aaron">Aaron</option>
                        <option value="Riley">Riley</option>
                    </select>
                    <input type="number" name="amount" id="amount" step="0.01" placeholder="Amount" required>
                    <input type="number" name="percentage" id="percentage" step="0.01" placeholder="Percentage (default: 50)" value="50">
                    <p>Owed: $<span id="owed-amount">0.00</span></p>
                    <input type="text" name="description" placeholder="Description" required>
                    <button type="submit">Add Charge</button>
                </form>
            </div>

            <ul class="charge-list">
                <?php foreach ($charges as $charge): ?>
                    <li class="<?php echo $charge['settled'] ? 'settled' : ''; ?>">
                        <span><?php echo htmlspecialchars($charge['description']); ?></span>
                        <span><?php echo htmlspecialchars($charge['payer']); ?>: $<?php echo number_format($charge['amount'], 2); ?> (<?php echo number_format($charge['percentage'], 0); ?>%)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="settle-up">
            <a href="settle_up.php">Settle Up</a>
        </div>
    </div>

    <script>
        const amountInput = document.getElementById('amount');
        const percentageInput = document.getElementById('percentage');
        const owedAmountSpan = document.getElementById('owed-amount');

        function updateOwedAmount() {
            const amount = parseFloat(amountInput.value) || 0;
            const percentage = parseFloat(percentageInput.value) || 0;
            const owedAmount = (amount * (percentage / 100)).toFixed(2);
            owedAmountSpan.textContent = owedAmount;
        }

        amountInput.addEventListener('input', updateOwedAmount);
        percentageInput.addEventListener('input', updateOwedAmount);
    </script>
</body>
</html>
