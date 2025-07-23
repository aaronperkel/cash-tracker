<?php
require_once '../config.php';

$sql = "SELECT * FROM charges";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);

$total = 0;
$aaron_owes = 0;
$riley_owes = 0;

foreach ($charges as $charge) {
    if ($charge['settled'] == 0) {
        $owed_amount = $charge['amount'] * ($charge['percentage'] / 100);
        if ($charge['payer'] == 'Aaron') {
            $riley_owes += $owed_amount;
        } else {
            $aaron_owes += $owed_amount;
        }
    }
}

$total = $riley_owes - $aaron_owes;

$to = 'aaron@example.com, riley@example.com';
$subject = 'Weekly Expense Summary';
$message = 'Here is the weekly expense summary:' . "\r\n\r\n";

if ($total > 0) {
    $message .= 'Riley owes Aaron $' . number_format(abs($total), 2);
} elseif ($total < 0) {
    $message .= 'Aaron owes Riley $' . number_format(abs($total), 2);
} else {
    $message .= 'You are all settled up!';
}

$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

echo "Email sent successfully.";
?>
