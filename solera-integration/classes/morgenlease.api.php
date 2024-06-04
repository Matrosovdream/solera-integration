<?php
class Morgenlease_API {

    public $key = get_option('morgenlease_api_key');
    public $base_url = 'https://calculator.morgenlease.nl/platform/';

    public function __construct() {}

    public function get_monthly_payment( $price=0 ) {

        if( !$price ) { return false; }

        $data = array(
            'calculator_key' => $this->key,
            'car_price' => $price
        );

        $path = "/api/monthly-amount/";
        return $this->request( $data, $path );

    }

    private function request( $data, $path ) {

        $url = $this->base_url.$path;

        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => $data,
          CURLOPT_HTTPHEADER => array(
            //'Content-Type:application/json',
          ),
        ));
        
        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode( $response, true );

    }

}