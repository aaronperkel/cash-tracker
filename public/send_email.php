<?php
require_once '../config.php';

$sql = "SELECT * FROM charges";
$result = mysqli_query($link, $sql);
$charges = mysqli_fetch_all($result, MYSQLI_ASSOC);

$total = 0;
$payer1_total = 0;
$payer2_total = 0;

foreach ($charges as $charge) {
    if ($charge['payer'] == 'Payer1') {
        $payer1_total += $charge['amount'];
    } else {
        $payer2_total += $charge['amount'];
    }
}

$total = $payer1_total - $payer2_total;

$to = 'payer1@example.com, payer2@example.com';
$subject = 'Weekly Expense Summary';
$message = 'Here is the weekly expense summary:' . "\r\n\r\n";

if ($total > 0) {
    $message .= 'Payer2 owes Payer1 $' . number_format(abs($total) / 2, 2);
} elseif ($total < 0) {
    $message .= 'Payer1 owes Payer2 $' . number_format(abs($total) / 2, 2);
} else {
    $message .= 'You are all settled up!';
}

$headers = 'From: webmaster@example.com' . "\r\n" .
    'Reply-To: webmaster@example.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($to, $subject, $message, $headers);

echo "Email sent successfully.";
?>
