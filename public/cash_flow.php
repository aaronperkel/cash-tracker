<?php
require_once '../config.php';

$sql = "SELECT * FROM charges ORDER BY charge_date DESC";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cash Flow - Expense Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>Cash Flow</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="cash_flow.php">Cash Flow</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>

    <div class="container">
        <?php
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
        <div class="cash-flow">
            <div class="cash-flow-person">
                <h2>Aaron</h2>
            </div>
            <div class="cash-flow-arrow">
                <?php if ($total < 0): ?>
                    <span class="red">&larr; $<?php echo number_format(abs($total), 2); ?> &larr;</span>
                <?php elseif ($total > 0): ?>
                    <span class="green">&rarr; $<?php echo number_format(abs($total), 2); ?> &rarr;</span>
                <?php else: ?>
                    <span>&#8644; All settled up! &#8644;</span>
                <?php endif; ?>
            </div>
            <div class="cash-flow-person">
                <h2>Riley</h2>
            </div>
        </div>

        <ul class="charge-list">
            <?php foreach ($charges as $charge): ?>
                <?php if ($charge['settled'] == 0): ?>
                    <li>
                        <span><?php echo htmlspecialchars($charge['description']); ?></span>
                        <span><?php echo htmlspecialchars($charge['payer']); ?>: $<?php echo number_format($charge['amount'], 2); ?> (<?php echo number_format($charge['percentage'], 0); ?>%)</span>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
