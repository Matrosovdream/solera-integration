<?php
class DV_process_xml {

    private $xml_data;
    private $product;

    public function __construct( $xml_data ) {

        $this->xml_data = $xml_data;
        $this->product = new DV_process_product();

    }

    public function process() {

        $data = $this->parse();

        // Welke actie moeten we uitvoeren (add/change/delete)
        switch( (string) $data['@attributes']['actie'] ) {
            case 'add':
                $this->product->add_product( $data );
                break;
            case 'change':
                $this->product->update_product( $data );
                break;
            case 'delete':
                $this->product->delete_product( $data );
                break;
        }

    }

    private function to_array( $data ) {
        return json_decode( json_encode( $data ), true );
    }

    // No need for now
    private function parse() {
        return $this->to_array( simplexml_load_string( $this->xml_data ) );
    }

}