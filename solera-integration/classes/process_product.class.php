<?php
class DV_process_product {

    private $post_type = "occasions";
    private $lease;

    public function __construct() {

        // For better performance
        $this->set_server_settings();
        
        // Lease API
        $this->lease = new Morgenlease_API();
        
    }

    public function add_product($data) {

        $isset_post = $this->get_post_by_code( $data['voertuignr_hexon'] );
        $post_id = $isset_post->ID;

        if( $post_id ) {
            $this->update_product($data);
            return false;
        }

        $post = $this->prepare_data( $data );
        $post_id = wp_insert_post( $post );

        if( $post_id ) {
            $this->download_images( $post_id, $data );
            $this->set_product_meta( $post_id, $data );
        }

    }

    public function update_product($data) {

        $isset_post = $this->get_post_by_code( $data['voertuignr_hexon'] );
        $post_id = $isset_post->ID;

        if( $post_id ) {

            $this->remove_all_attachments( $post_id );

            $post = $this->prepare_data( $data );
            $post['ID'] = $post_id;
            wp_update_post( $post );

            if( $post_id ) {
                $this->download_images( $post_id, $data );
                $this->set_product_meta( $post_id, $data );
            }

        } else {
            $this->add_product($data);
        }

    }

    public function delete_product($data) {

        $isset_post = $this->get_post_by_code( $data['voertuignr_hexon'] );
        $post_id = $isset_post->ID;

        if( $post_id ) {
            $this->remove_all_attachments( $post_id );
            wp_delete_post( $post_id );
        }

    }

    private function remove_all_attachments( $post_id ) {

        $media = get_attached_media('image', $post_id);

        foreach( $media as $id=>$post ) {

            // Delete from Database
            wp_delete_attachment( $id, $force_delete=true );

            // Delete from Server
            $file_path = get_attached_file( $id );

            if (file_exists($file_path)) {
                unlink($file_path);
            }

        }

    }

    private function prepare_data( $data ) {

        $title = $this->remove_emojies($data['titel']);

        $set = array(
            'post_title' => $title,
            'post_content' => $data['opmerkingen'],
            'post_status' => 'publish',
            'post_date' => date('Y-m-d H:i:s'),
            'post_author' => get_current_user_id(),
            'post_type' => $this->post_type,
            'post_category' => array(0)
        );

        return $set;

    } 

    private function set_product_meta( $post_id, $data ) {

        // We take just a few fields
        $fields = array("voertuignr_hexon", "voertuignr", "klantnummer", "merk", "model", "");

        foreach( $fields as $field ) {
            update_post_meta( $post_id, $field, $data[ $field ] );
        }

        // Custom field names
        update_post_meta( $post_id, "brandstof", $data['autotelex']['brandstof'] );
        update_post_meta( $post_id, "transmission", $data['voertuigsoort'] );
        update_post_meta( $post_id, "mileage", $data['tellerstand'] );
        update_post_meta( $post_id, "year", $data['bouwjaar'] );

        // Price
        $price = $data['verkoopprijs_particulier']['prijs']['bedrag'];
        update_post_meta( $post_id, 'price', $price );

        // Lease payment 
        $lease_monthly_sum = $this->calc_lease_payment( $price );
        update_post_meta( $post_id, 'lease_monthly_payment', $lease_monthly_sum );

        // A lot of data, easier to save this way
        update_post_meta( $post_id, 'api_data', $data );

    }

    private function calc_lease_payment( $price ) {

        $res = $this->lease->get_monthly_payment( $price );
        return $res['monthly_amount'];

    }

    public function get_post_by_code( $code ) {

        $args = array(
            'post_type' => 'occasions', 
            'posts_per_page' => -1, 
            'meta_query' => array(
                array(
                    'key' => 'voertuignr_hexon',
                    'value' => $code,
                    'compare' => '=',
                ),
            ),
        );
        
        // Create a new instance of WP_Query
        $query = new WP_Query($args);

        if( count($query->posts) > 0 ) {
            return $query->posts[0];
        }

    }

    private function download_images( $post_id, $data ) {

        $images = $data['afbeeldingen']['afbeelding'];

        $attachments = $this->download_all_images( $post_id, $images );

        if( is_array($attachments) && count($attachments) > 0 ) {

            // Featured image
            $this->set_featured_image( $post_id, $attachments[0] );

            // Gallery
            $this->set_product_gallery( $post_id, $attachments );

            // Hide images from gallery
            $this->bulk_media_draft( $post_id );
        }

    }

    private function download_all_images( $post_id, $images ) {

        if( count( $images ) == 0 ) { return false; }

        // Required classes
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $ids = [];
        foreach( $images as $key=>$image ) {

            // We don't create extra sizes for gallery images but for Main image
            if( $key != 0 ) {
                add_filter( 'intermediate_image_sizes', '__return_empty_array', 999 );
            }

            // Upload the remote image and get the attachment ID
            $attachment_id = media_sideload_image($image['url'], $post_id, '', 'id');

            // Check if the image was uploaded successfully
            if (!is_wp_error($attachment_id)) {

                // Set post status to draft, because we don't want to show the images in the media library
                if( $key != 0 ) {
                    wp_update_post(array('ID' => $attachment_id, 'post_status' => 'draft'));
                }

                $ids[] = $attachment_id;
            }

        }

        return $ids;

    }

    public function bulk_media_draft( $post_id ) {

        $gallery = get_post_meta( $post_id, "gallery", true );
        if( !is_iterable($gallery) ) { return ; }

        global $wpdb;
        $query = "UPDATE $wpdb->posts SET post_status = 'draft' WHERE post_type = 'attachment' AND `ID` IN (".implode(',', $gallery).")";
        $wpdb->query( $query );

    }

    private function set_server_settings() {

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '1024M');

        set_time_limit(0);
        ignore_user_abort(true);

    }

    private function set_featured_image( $post_id, $attachment_id ) {
        set_post_thumbnail( $post_id, $attachment_id) ;
    }

    private function set_product_gallery( $post_id, $attachments ) {
        update_post_meta( $post_id, "gallery", $attachments );
    }

    private function remove_emojies($input) {
        // Use a regular expression to remove emojis
        $cleanedString = preg_replace('/[\x{1F600}-\x{1F64F}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}]/u', ' ', $input);
    
        // Return the cleaned string
        return $cleanedString;
    }   

}