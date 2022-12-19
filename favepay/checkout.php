<?php

require_once "./lib/class.favepay.php";

$favepay = new Favepay();
$prsUrl = dirname($_SERVER['REQUEST_URI']);

$uniId = uniqid();
$amount = 100;
$payload = [
    'omni_reference'=> (string)$favepay->prefix."-".$uniId,
    'total_amount_cents'=> (int)((float)($amount) * 100), //amount should be in cents
    'app_id'=> (string)$favepay->app_id,
    'outlet_id'=> (int)$favepay->outlet_id,  
    'redirect_url' => "http://$favepay->host/$prsUrl/retrievecheckout.php?ref=".$uniId,          
    'callback_url' => "http://$favepay->host/$prsUrl/retrievecheckout.php?ref=".$uniId,          
    'test' => 'true',
];

$payload['sign'] = $favepay->favepaySignature($payload,$favepay->private_api_key);
$faveResponse = $favepay->createCheckout($payload);

foreach($faveResponse as $key => $value)
{
    echo $key ." : ". $value ."</br>";
}
//error_log(json_encode($faveResponse,JSON_PRETTY_PRINT));
/*
Sample response
"code": "https:\/\/omni.app.fave.ninja\/web_views\/favepay_online\/test?omni_ref=eyJfer343iJIUzI1NiJ9.lHAiOE2NzE0NDI4NjEsIm9tbmlfcmVmZXJlbmNlIj6oiRF2BPLTxcfdzYTAzMDQ0NzYxZGYiLCJjb3VudHJ5X2NvZGUiOiJzZyIsInJlZGlyZWN0X3VybCI6Imh0dHBzOi8vd3d3LmV4YW1wbGUuY29tL3JfZGasdlyZWN0LnBocCJ9.asdfasd23423-GveBdirPX_iOw0dNsdfs321M",
"format": "base64",
"expires_in": 300
*/

echo '</br><a href="'.$faveResponse['code'].'" tartget="_blank">Checkout</a>';

?>

