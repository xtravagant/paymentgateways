<?php

require_once "./lib/class.maya.php";

$ccpayload = [
    "card" => [
        "number" => "5123456789012346",
        "expMonth" => "12",
        "expYear" => "2025",
        "cvc" => "111"
    ]           
];
$maya = new Maya();
$paymentToken = $maya->createPaymentToken($ccpayload);

/*
Example response data:
{
    "paymentTokenId": "plYsIDy5mbufjnMd584bXACyyAGvCJLJsHSVQr6TGouIpUDdbO1B6nYqwDnK4J2irFUdcvknVK6JqGE39zilxKHjWCSCojkl7cjYU1FzisPROLGB4jyV6y0JoUyKCE26BwhqoHV4zgpcQ4bGrOQvkff24mMbTFrXenhpWMn8",
    "state": "AVAILABLE",
    "createdAt": "2022-12-15T08:21:51.000Z",
    "updatedAt": "2022-12-15T08:21:51.000Z",
    "issuer": "Others"
}
*/

$requestReferenceNumber = uniqid();
$params = [
    "paymentTokenId" => $paymentToken['paymentTokenId'],
    "totalAmount" => [
        "amount" => 1000,
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
];

$createPayment = $maya->createPaymentByToken($params);
error_log(json_encode($createPayment,JSON_PRETTY_PRINT));
/*
Example response data:
{
    "id": "1e3bc56a-6bff-4b21-a415-39c458fa9d25",
    "isPaid": false,
    "status": "FOR_AUTHENTICATION",
    "amount": "1000",
    "currency": "PHP",
    "canVoid": false,
    "canRefund": false,
    "canCapture": false,
    "createdAt": "2022-12-15T08:23:29.000Z",
    "updatedAt": "2022-12-15T08:23:29.000Z",
    "description": "Charge for test@example.com",
    "paymentTokenId": "QTaibYQT81V17No08YFxrzXdCVfUFWEAnTuEK2KogBMphKtcaJ8dQCdcozkUlpO8SqEzvgX8nXw8q6tfMCGBDCiRbzKMp0bGLRZ3g7ZiFNURIaY36gNCqpAkixGjpT7ZGEzjKpRwrtkXD7lHPYKzyOS8Rpd8dDTsQ9RLa0",
    "requestReferenceNumber": "639ad980ca1b2",
    "verificationUrl": "https:\/\/payments-web-sandbox.paymaya.com\/authenticate?id=1e3bcd56a-6bff-4b21-a415-39c458fa9d25"
}
*/

echo '<a href="'.$createPayment['verificationUrl'].'">Checkout</a>'
?>

