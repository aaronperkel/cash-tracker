<?php
require_once '../config.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payer = $_POST['payer'];
    $paid_for = $_POST['paid_for'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $percentage = $_POST['percentage'];

    $sql = "UPDATE charges SET payer = ?, paid_for = ?, amount = ?, description = ?, percentage = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssdsdi", $payer, $paid_for, $amount, $description, $percentage, $id);

        if (mysqli_stmt_execute($stmt)) {
            header("location: admin.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
} else {
    $sql = "SELECT * FROM charges WHERE id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $charge = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Charge - Expense Tracker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <header>
        <h1>Edit Charge</h1>
    </header>

    <div class="container">
        <div class="charges">
            <div class="charge-form">
                <form action="edit_charge.php?id=<?php echo $id; ?>" method="post">
                    <label for="payer">Payer</label>
                    <select name="payer" required>
                        <option value="Aaron" <?php echo $charge['payer'] == 'Aaron' ? 'selected' : ''; ?>>Aaron</option>
                        <option value="Riley" <?php echo $charge['payer'] == 'Riley' ? 'selected' : ''; ?>>Riley</option>
                    </select>
                    <label for="paid_for">Paid for</label>
                    <select name="paid_for" required>
                        <option value="Both" <?php echo $charge['paid_for'] == 'Both' ? 'selected' : ''; ?>>Both</option>
                        <option value="Aaron" <?php echo $charge['paid_for'] == 'Aaron' ? 'selected' : ''; ?>>Aaron</option>
                        <option value="Riley" <?php echo $charge['paid_for'] == 'Riley' ? 'selected' : ''; ?>>Riley</option>
                    </select>
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" step="0.01" value="<?php echo $charge['amount']; ?>" required>
                    <label for="percentage">Percentage</label>
                    <input type="number" name="percentage" step="0.01" value="<?php echo $charge['percentage']; ?>" required>
                    <label for="description">Description</label>
                    <input type="text" name="description" value="<?php echo htmlspecialchars($charge['description']); ?>" required>
                    <button type="submit">Update Charge</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
