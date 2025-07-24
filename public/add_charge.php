<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payer = $_POST['payer'];
    $paid_for = $_POST['paid_for'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $percentage = $_POST['percentage'];
    $pay_down_balance = isset($_POST['pay_down_balance']) ? 1 : 0;

    $sql = "INSERT INTO charges (payer, paid_for, amount, description, percentage, pay_down_balance) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssdsdi", $payer, $paid_for, $amount, $description, $percentage, $pay_down_balance);

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
