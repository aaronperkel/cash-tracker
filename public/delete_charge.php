<?php
require_once '../config.php';

$id = $_GET['id'];

$sql = "DELETE FROM charges WHERE id = ?";

if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        header("location: admin.php");
    } else {
        echo "Something went wrong. Please try again later.";
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($link);
?>
