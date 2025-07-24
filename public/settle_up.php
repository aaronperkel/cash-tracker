<?php
require_once '../config.php';

$sql = "UPDATE transactions SET settled = 1 WHERE settled = 0";

if (mysqli_query($link, $sql)) {
    header("location: index.php");
} else {
    echo "Something went wrong. Please try again later.";
}

mysqli_close($link);
?>
