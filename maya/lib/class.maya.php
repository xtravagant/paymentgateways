<?php

require __DIR__ . '/../vendor/autoload.php';
/*
Author: Lodel Geraldo
Info: Maya payment gateway sample integration

Maya API Reference: https://developers.maya.ph/reference/quickstart-enterprise-payments

Card Type	Number	Expiry Month	Expiry Year	CSC/CVV	3-D Secure Password
MASTERCARD	5123456789012346	12	2025	111	Not enabled
MASTERCARD	5453010000064154	12	2025	111	secbarry1
VISA	4123450131001381	12	2025	123	mctest1
VISA	4123450131001522	12	2025	123	mctest1
VISA	4123450131004443	12	2025	123	mctest1
VISA	4123450131000508	12	2025	111	Not enabledster   5313581000123430 05/2024 123

*/

class Maya{

    public $secret_key;
    public $public_key;
    public $baseUrl;
    public $host;

    function __construct(){
        
        $this->host = $_SERVER["HTTP_HOST"];

        if(strstr($this->host,'localhost') || strstr($this->host,'test')){
            //For sandbox credentials
            $this->secret_key   = "";
            $this->public_key   = "";
            $this->baseUrl      = "";
        }
        else
        {
            //For production credentials
        }

    }

    public function createCheckout($payload){        
        $client = new GuzzleHttp\Client();
        try{
            $url = $this->baseUrl."/checkout/v1/checkouts";            
            $response = $client->post($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Basic " . base64_encode($this->public_key.':')
                ],
                'body' => json_encode($payload)
            ]);

            return json_decode( (string)$response->getBody(), true );                        
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }

    public function retrievePaymentInfo($checkoutId){
        $client = new GuzzleHttp\Client();
        try{
            $url = implode("/", [$this->baseUrl."/payments/v1/payment-rrns", $checkoutId]);
            $response = $client->get($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Basic " . base64_encode($this->secret_key.':')
                ],
                'body' => ''
            ]);

            return json_decode( (string)$response->getBody(), true );                        
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }

    //Need to use production API
    public function cancelPaymentByID($paymentId){
        $client = new GuzzleHttp\Client();
        try{
            $url = $this->baseUrl."/payments/v1/payments/".$paymentId."/cancel";
            $response = $client->post($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Basic " . base64_encode($this->secret_key.':')
                ],
                'body' => ''
            ]);

            return json_decode( (string)$response->getBody(), true );                        
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }

    //For Maya Vault - https://developers.maya.ph/reference/introduction-payment-vault
    public function createPaymentToken($params){
        $client = new GuzzleHttp\Client();
        try{
            $url = $this->baseUrl."/payments/v1/payment-tokens";            
            $response = $client->post($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Basic " . base64_encode($this->public_key.':')
                ],
                'body' => json_encode($params)
            ]);

            return json_decode( (string)$response->getBody(), true );                        
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }

    public function createPaymentByToken($params){
        $client = new GuzzleHttp\Client();
        try{
            $url = $this->baseUrl."/payments/v1/payments";            
            $response = $client->post($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => "Basic " . base64_encode($this->secret_key.':')
                ],
                'body' => json_encode($params)
            ]);

            return json_decode( (string)$response->getBody(), true );                        
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }



}
?>