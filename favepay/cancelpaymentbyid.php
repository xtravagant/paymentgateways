<?php

require_once "./lib/class.favepay.php";

$favepay = new Favepay();

$omni_ref = ""; //Value should be the omni_reference 
$cancelRefundPayment = $favepay->cancelPaymentByID($omni_ref);

//Just checking response data 
print_r($cancelRefundPayment);



?>

