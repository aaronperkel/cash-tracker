<?php
require_once '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $payer = $_POST['payer'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];

    $sql = "INSERT INTO charges (payer, amount, description) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "sds", $payer, $amount, $description);

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
