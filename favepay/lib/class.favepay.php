<?php

require __DIR__ . '/../vendor/autoload.php';

class Favepay{

    public $private_api_key;
    public $app_id;
    public $baseUrl;
    public $host;
    public $merchant_name;
    public $outlet_name;
    public $prefix;

    function __construct(){
        
        $this->host = $_SERVER["HTTP_HOST"];

        if(strstr($this->host,'localhost')){
            //For sandbox credentials
            $this->private_api_key = "";
            $this->app_id = ""; 
            $this->baseUrl = "https://omni.app.fave.ninja/api/fpo/v1/sg";
            $this->merchant_name = ""; 
            $this->outlet_name = ""; 
            $this->outlet_id = ""; 
            $this->prefix = "";
        }
        else
        {
            //For production credentials
        }

    }

    public function createCheckout($payload){        
        $client = new GuzzleHttp\Client();
        try{
            $url = $this->baseUrl."/qr_codes?".$this->favepayCreateUrl($payload);  
            
            $rqCall = $client->post($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',
                ],
                'body' => json_encode($payload)
            ]);

            $responseApi = json_decode( (string)$rqCall->getBody(), true );

            return $responseApi;                                  
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }

    public function favepaySignature($params,$private_api_key){

        $retval = "";
        $appendAmp = 0;

        foreach($params as $key => $value)
        {
            if($appendAmp == 0){
                $retval .= urlencode($key) . "=" . urlencode($value);
                $appendAmp = 1;
            }
            else{
                $retval .= "&" . urlencode($key) . "=" . urlencode($value);
            }
            
        }
        
        $hashhmac = hash_hmac('sha256', $retval, $private_api_key);

        return $hashhmac;
    }

    public function decryptOmniRef($encrypted_data){ 
        
        $ciphering = 'aes-128-cbc';
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decrypt_iv = '';
        $decrypt_key = $this->private_api_key; 
        $decryption = openssl_decrypt($encrypted_data, $ciphering, $decrypt_key, $options, $decrypt_iv);  

        return $decryption;
    }

    private function favepayCreateUrl($params){
        $urlParams = "";
        $appendAmp = 0;

        foreach($params as $key => $value)
        {
            if (strlen($value) > 0) {
                if ($appendAmp == 0) {
                    $urlParams .= urlencode($key) . '=' . urlencode($value);
                    $appendAmp = 1;
                } else {
                    if($key == 'sign')
                        $urlParams .= '&' . urlencode($key) . "=" . $value;
                    else
                        $urlParams .= '&' . urlencode($key) . "=" . urlencode($value);
                }
            }
            
        }

        return $urlParams;
    }

    public function retrievePaymentInfo($omni_ref){

        $payload = [
            'omni_reference'=> $omni_ref,            
            'app_id'=> (string)$this->app_id,
        ];
        $payload['sign'] = $this->favepaySignature($payload, $this->private_api_key);

        $client = new GuzzleHttp\Client();
        try{
            $url = $url = $this->baseUrl."/transactions?".$this->favepayCreateUrl($payload);

            $rqCall = $client->get($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',                  
                ],
                'body' => json_encode($payload)
            ]);

            return json_decode( (string)$rqCall->getBody(), true );            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }
    
    public function cancelPaymentByID($omni_reference){

        $payload = [
            'omni_reference' => $omni_reference,            
            'app_id' => (string)$this->app_id,
            'status' => 'refunded',
        ];
        $payload['sign'] = $this->favepaySignature($payload, $this->private_api_key);
        
        $client = new GuzzleHttp\Client();
        try{            

			$url = $url = $this->baseUrl."/transactions?".$this->favepayCreateUrl($payload);

            $rqCall = $client->get($url,[
                'headers'   => [
                    'Content-Type'  => 'application/json',                  
                ],
                'body' => json_encode($payload)
            ]);

            return json_decode( (string)$rqCall->getBody(), true );            
            
        } catch (GuzzleHttp\Exception\RequestException $e) {               
            die($e->getMessage());
        }
    }
}
?>