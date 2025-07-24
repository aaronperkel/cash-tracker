<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $payer = $_POST['payer'];
    $type = $_POST['type'];

    if ($type == 'split') {
        $from_person = ($payer == 'Aaron') ? 'Aaron' : 'Riley';
        $to_person = ($payer == 'Aaron') ? 'Riley' : 'Aaron';
        $transaction_amount = $amount / 2;
    } else {
        $from_person = $payer;
        $to_person = ($payer == 'Aaron') ? 'Riley' : 'Aaron';
        $transaction_amount = $amount;
    }

    $sql = "INSERT INTO transactions (description, amount, from_person, to_person) VALUES (?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sdss", $description, $transaction_amount, $from_person, $to_person);

        if (mysqli_stmt_execute($stmt)) {
            header("location: index.php");
        } else {
            echo "Something went wrong. Please try again later.";
        }

        mysqli_stmt_close($stmt);
    }
}

mysqli_close($link);
?>
