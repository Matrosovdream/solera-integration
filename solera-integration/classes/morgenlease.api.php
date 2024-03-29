<?php
class Morgenlease_API {

    private $key = '68938103';
    private $api_url = 'https://calculator.morgenlease.nl/platform/api/monthly-amount';

    public function __construct() {}

    public function get_monthly_payment( $price=0 ) {

        if( !$price ) { return false; }

        $data = array(
            'calculator_key' => $this->key,
            'car_price' => $price
        );

    }

    private function request( $data ) {

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $this->api_url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => ,
          CURLOPT_HTTPHEADER => array(),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
        

    }

}