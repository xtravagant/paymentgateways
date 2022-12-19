<?php

require_once "./lib/class.favepay.php";

$favepay = new Favepay();

$omni_ref = $_REQUEST['omni_ref'];

 //Need to replace + sign to url encoded value %2B
 $urirplc =  str_replace("+","%2B",$_SERVER['REQUEST_URI']);
 $prstr = parse_str($urirplc,$omniref);    
 $omni_ref = $omniref['omni_ref'];

$decryptOmniRef = $favepay->decryptOmniRef($omni_ref);
parse_str($decryptOmniRef, $output);

$omniReference = $output['omni_reference'];
$checkoutInfo = $favepay->retrievePaymentInfo($omniReference);

foreach($output as $key => $value){
    echo $key . ' : ' . $value. '</br>';
}
/*
Sample decrypted values
receipt_id : 0521-675713
omni_reference : PRE-5467khfgry37c
status : successful
total_amount_cents : 10000
*/

//Just checking response data 
error_log(json_encode($checkoutInfo, JSON_PRETTY_PRINT));
echo "</br>";
foreach($checkoutInfo as $key => $value){
    echo $key . ' : ' . $value. '</br>';
}

/*
Sample retrieve payment info

id : FP_123456
receipt_id : 0521-675713
outlet_name : OutletNameHere
total_amount_cents : 10000
currency : SGD
outlet_id : 12345
mid : 1234
omni_reference : PRE-5467khfgry37c
status : successful
status_code : 2
created_at : 2022-12-19T18:10:13.254+08:00
campaign_credit_amount_cents : 0
campaign_funded_by : N/A
aabig_points_used_amount_cents : 0
merchant_cashback_amount_cents :
fave_credits_amount_cents : 0
promo_code_value_cents : 0
e_card_credits_used_cents : 0
charged_amount_cents : 10000
cashback_rate :
merchant_cashback_issued_cents :
promo_code : N/A
promo_code_cashback_value :
promo_code_cashback_type :
promo_code_cashback_issued_cents : 0
promo_code_cashback_funded_by :
fave_fees_percentage : 0.0
fave_fees_cents : 0
sst_on_total_fees_cents : 0
merchant_takeback_cents : 10000
user_id : 2820439
fpl_transaction : false
fpl_fees_percentage : N/A
fpl_fees_cents : N/A
fpl_merchant_cashback_reduction_percentage : N/A
*/

?>

