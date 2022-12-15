<?php

require_once "./lib/class.maya.php";

$maya = new Maya();

$cancelPayment = $maya->cancelPaymentByID($paymentId);

//Just checking response data 
print_r($cancelPayment);



?>

