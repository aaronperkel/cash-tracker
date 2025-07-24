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
                    if ($charge['settled'] == 0) {
                        $owed_amount = $charge['amount'] * ($charge['percentage'] / 100);
                        if ($charge['payer'] == 'Aaron') {
                            $aaron_balance += $charge['amount'] - $owed_amount;
                            $riley_balance -= $owed_amount;
                        } else {
                            $riley_balance += $charge['amount'] - $owed_amount;
                            $aaron_balance -= $owed_amount;
                        }
                    }
                ?>
                    <tr>
                        <td><?php echo date('Y-m-d', strtotime($charge['charge_date'])); ?></td>
                        <td><?php echo htmlspecialchars($charge['description']); ?></td>
                        <td class="<?php echo ($charge['payer'] == 'Aaron') ? 'green' : 'red'; ?>">
                            <?php
                            if ($charge['payer'] == 'Aaron') {
                                echo '$' . number_format($charge['amount'], 2);
                            } else {
                                echo '-$' . number_format($charge['amount'] * ($charge['percentage'] / 100), 2);
                            }
                            ?>
                        </td>
                        <td class="<?php echo ($charge['payer'] == 'Riley') ? 'green' : 'red'; ?>">
                            <?php
                            if ($charge['payer'] == 'Riley') {
                                echo '$' . number_format($charge['amount'], 2);
                            } else {
                                echo '-$' . number_format($charge['amount'] * ($charge['percentage'] / 100), 2);
                            }
                            ?>
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
