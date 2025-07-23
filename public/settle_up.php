<?php
require_once '../config.php';

$sql = "DELETE FROM charges";

if (mysqli_query($link, $sql)) {
    header("location: index.php");
} else {
    echo "Something went wrong. Please try again later.";
}

mysqli_close($link);
?>
