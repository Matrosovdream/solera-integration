<?php
class DV_product_helpers {

    public static function prepare_for_lease_calc( $product_id ) {

        $meta = get_post_meta($product_id, 'api_data', true);
        
        // Price
        if( !isset($meta['verkoopprijs_particulier']['prijs']['bedrag']) ) {
            $price = $meta['verkoopprijs_particulier']['prijs'][0]['bedrag'];
        } else {
            $price = $meta['verkoopprijs_particulier']['prijs']['bedrag'];
        }

        $data = array(
            "calculator_key" => (new Morgenlease_API)->key,
            "car_price" => $price,
            "btw_marge" => $meta['btw_marge'],
            "verkoopprijs_particulier_btw" => "",
            "merk" => $meta['merk'],
            "model" => $meta['model'],
            "type" => $meta['type'],
            "kleur" => "",
            "tellerstand" => $meta['tellerstand'],
            "kenteken" => $meta['kenteken'],
            "bouwjaar" => $meta['bouwjaar'],
            "massa" => $meta['massa'],
            "car_url" => get_post_permalink( $post->ID )
        );

        return $data;

    }

}