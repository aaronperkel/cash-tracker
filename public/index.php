<?php
require_once '../require_login.php';
require_once '../config.php';

$sql = "SELECT * FROM charges ORDER BY charge_date DESC";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);

$total = 0;
$payer1_total = 0;
$payer2_total = 0;

foreach ($charges as $charge) {
    if ($charge['payer'] == 'Payer1') {
        $payer1_total += $charge['amount'];
    } else {
        $payer2_total += $charge['amount'];
    }
}

$total = $payer1_total - $payer2_total;
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
                <h2>Payer2 owes Payer1 $<?php echo number_format(abs($total) / 2, 2); ?></h2>
            <?php elseif ($total < 0): ?>
                <h2>Payer1 owes Payer2 $<?php echo number_format(abs($total) / 2, 2); ?></h2>
            <?php else: ?>
                <h2>All settled up!</h2>
            <?php endif; ?>
        </div>

        <div class="charges">
            <div class="charge-form">
                <form action="add_charge.php" method="post">
                    <select name="payer" required>
                        <option value="">Who paid?</option>
                        <option value="Payer1">Payer1</option>
                        <option value="Payer2">Payer2</option>
                    </select>
                    <input type="number" name="amount" step="0.01" placeholder="Amount" required>
                    <input type="text" name="description" placeholder="Description" required>
                    <button type="submit">Add Charge</button>
                </form>
            </div>

            <ul class="charge-list">
                <?php foreach ($charges as $charge): ?>
                    <li>
                        <span><?php echo htmlspecialchars($charge['description']); ?></span>
                        <span><?php echo htmlspecialchars($charge['payer']); ?>: $<?php echo number_format($charge['amount'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="settle-up">
            <a href="settle_up.php">Settle Up</a>
        </div>
    </div>
</body>
</html>
