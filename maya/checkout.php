<?php

require_once "./lib/class.maya.php";

$maya = new Maya();
$prsUrl = dirname($_SERVER['REQUEST_URI']);
$currentUri = explode("/",$_SERVER["REQUEST_URI"]);

$requestReferenceNumber = uniqid();
$payload = [
    "totalAmount" => [
        "value" => 1000,
        "currency" => "PHP"
    ],
    "requestReferenceNumber" => $requestReferenceNumber,
    "buyer" => [
        "firstName" => "Firstname",
        "middleName" => "Middlename",
        "lastName" => "Lastname",
        "contact" => [
            "phone" => "+63123456789",
            "email" => "test@example.com"
        ]
    ],
    "redirectUrl" => [
        "failure" => "http://$maya->host/$prsUrl/retrievecheckout.php?ref=".$requestReferenceNumber,
        "success" => "http://$maya->host/$prsUrl/retrievecheckout.php?ref=".$requestReferenceNumber,
        "cancel" => "http://$maya->host/$prsUrl/retrievecheckout.php?ref".$requestReferenceNumber,
    ]
    
];

$mayaCheckout = $maya->createCheckout($payload);
foreach($mayaCheckout as $key => $value)
{
    echo $key ." : ". $value ."</br>";
}

echo '</br><a href="'.$mayaCheckout['redirectUrl'].'" target="_blank">Checkout</a>';
?>

