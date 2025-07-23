<?php
require_once '../config.php';

$sql = "SELECT * FROM charges ORDER BY charge_date DESC";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Expense Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>Admin</h1>
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
                    <th>Payer</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Percentage</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($charges as $charge): ?>
                    <tr>
                        <td><?php echo date('Y-m-d', strtotime($charge['charge_date'])); ?></td>
                        <td><?php echo htmlspecialchars($charge['payer']); ?></td>
                        <td><?php echo htmlspecialchars($charge['description']); ?></td>
                        <td>$<?php echo number_format($charge['amount'], 2); ?></td>
                        <td><?php echo number_format($charge['percentage'], 0); ?>%</td>
                        <td>
                            <a href="edit_charge.php?id=<?php echo $charge['id']; ?>">Edit</a>
                            <a href="delete_charge.php?id=<?php echo $charge['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
