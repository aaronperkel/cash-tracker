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
        <div class="cash-flow">
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

            <h2>Aaron</h2>
            <?php if ($total < 0): ?>
                <div class="arrow red">&darr; $<?php echo number_format(abs($total), 2); ?> &darr;</div>
            <?php else: ?>
                <div class="arrow green">&uarr; $<?php echo number_format(abs($total), 2); ?> &uarr;</div>
            <?php endif; ?>
            <h2>Riley</h2>
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
</body>
</html>
