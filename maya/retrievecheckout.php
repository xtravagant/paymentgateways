<?php

require_once "./lib/class.maya.php";

$maya = new Maya();

//Sample checkoutID = 6399e8a86f0a3
$requestReferenceNumber = $_REQUEST['ref'];
$checkoutInfo = $maya->retrievePaymentInfo($requestReferenceNumber);

//Just checking response data 
//print_r($checkoutInfo);
foreach($checkoutInfo[0] as $key => $value){
    echo $key . ' : ' . $value. '</br>';
}


?>

