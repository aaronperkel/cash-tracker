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
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Aaron</th>
                    <th>Riley</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $aaron_balance = 0;
                $riley_balance = 0;
                foreach ($charges as $charge):
                    $aaron_transaction = 0;
                    $riley_transaction = 0;
                    if ($charge['settled'] == 0) {
                        if ($charge['paid_for'] == 'Both') {
                            $owed_amount = $charge['amount'] * ($charge['percentage'] / 100);
                            if ($charge['payer'] == 'Aaron') {
                                $aaron_transaction = $charge['amount'] - $owed_amount;
                                $riley_transaction = -$owed_amount;
                            } else {
                                $riley_transaction = $charge['amount'] - $owed_amount;
                                $aaron_transaction = -$owed_amount;
                            }
                        } else {
                            if ($charge['payer'] == 'Aaron' && $charge['paid_for'] == 'Riley') {
                                $aaron_transaction = $charge['amount'];
                                $riley_transaction = -$charge['amount'];
                            } elseif ($charge['payer'] == 'Riley' && $charge['paid_for'] == 'Aaron') {
                                $riley_transaction = $charge['amount'];
                                $aaron_transaction = -$charge['amount'];
                            }
                        }
                    }
                    $aaron_balance += $aaron_transaction;
                    $riley_balance += $riley_transaction;
                ?>
                    <tr>
                        <td><?php echo date('Y-m-d', strtotime($charge['charge_date'])); ?></td>
                        <td><?php echo htmlspecialchars($charge['description']); ?> (Paid by <?php echo $charge['payer']; ?> for <?php echo $charge['paid_for']; ?>)</td>
                        <td class="<?php echo ($aaron_transaction >= 0) ? 'green' : 'red'; ?>">
                            $<?php echo number_format($aaron_transaction, 2); ?>
                        </td>
                        <td class="<?php echo ($riley_transaction >= 0) ? 'green' : 'red'; ?>">
                            $<?php echo number_format($riley_transaction, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th class="<?php echo ($aaron_balance >= 0) ? 'green' : 'red'; ?>">$<?php echo number_format($aaron_balance, 2); ?></th>
                    <th class="<?php echo ($riley_balance >= 0) ? 'green' : 'red'; ?>">$<?php echo number_format($riley_balance, 2); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
