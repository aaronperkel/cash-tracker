<?php
require_once '../config.php';

$sql = "SELECT * FROM transactions ORDER BY transaction_date DESC";
$result = mysqli_query($link, $sql);
$transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);

$balance = 0;

foreach ($transactions as $transaction) {
    if ($transaction['settled'] == 0) {
        if ($transaction['from_person'] == 'Aaron') {
            $balance -= $transaction['amount'];
        } else {
            $balance += $transaction['amount'];
        }
    }
}
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
            <?php if ($balance > 0): ?>
                <h2>Aaron owes Riley $<?php echo number_format(abs($balance), 2); ?></h2>
            <?php elseif ($balance < 0): ?>
                <h2>Riley owes Aaron $<?php echo number_format(abs($balance), 2); ?></h2>
            <?php else: ?>
                <h2>All settled up!</h2>
            <?php endif; ?>
        </div>

        <div class="transaction-form">
            <form action="add_transaction.php" method="post">
                <label for="description">Description</label>
                <input type="text" name="description" required>
                <label for="amount">Amount</label>
                <input type="number" name="amount" step="0.01" required>
                <label for="payer">Payer</label>
                <select name="payer">
                    <option value="Aaron">Aaron</option>
                    <option value="Riley">Riley</option>
                </select>
                <label for="type">Type</label>
                <select name="type">
                    <option value="split">Split</option>
                    <option value="direct">Direct Payment</option>
                </select>
                <button type="submit">Add Transaction</button>
            </form>
        </div>

        <ul class="transaction-list">
            <?php foreach ($transactions as $transaction): ?>
                <li class="<?php echo $transaction['settled'] ? 'settled' : ''; ?>">
                    <span><?php echo htmlspecialchars($transaction['description']); ?></span>
                    <span><?php echo $transaction['from_person']; ?> &rarr; <?php echo $transaction['to_person']; ?>: $<?php echo number_format($transaction['amount'], 2); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="settle-up">
            <a href="settle_up.php">Settle Up</a>
        </div>
    </div>
</body>
</html>
