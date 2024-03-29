<?php
class DV_process_product {

    private $post_type = "occasions";

    public function __construct() {

        // For better performance
        $this->set_server_settings();
        
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

        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */

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

        }

        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        

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
            wp_delete_attachment( $id, $force_delete=true );
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
        $fields = array("voertuignr_hexon", "voertuignr", "klantnummer");

        foreach( $fields as $field ) {
            update_post_meta( $post_id, $field, $data[ $field ] );
        }

        update_post_meta( $post_id, 'price', $data['verkoopprijs_particulier']['prijs']['bedrag'] );

        // A lot of data, easier to save this way
        update_post_meta( $post_id, 'api_data', $data );

    }

    private function get_post_by_code( $code ) {

        $meta = get_post_meta(1004694);
        /*
        echo "<pre>";
        print_r($meta);
        echo "</pre>";
        */

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

        /*
        echo "<pre>";
        print_r($query);
        echo "</pre>";
        */

    }

    private function download_images( $post_id, $data ) {

        $images = $data['afbeeldingen']['afbeelding'];

        $attachments = $this->download_all_images( $post_id, $images );

        if( is_array($attachments) && count($attachments) > 0 ) {

            // Featured image
            $this->set_featured_image( $post_id, $attachments[0] );

            // Gallery
            $this->set_product_gallery( $post_id, $attachments );
        }

    }

    private function download_all_images( $post_id, $images ) {

        if( count( $images ) == 0 ) { return false; }

        // Let's slice a bit
        //$images = array_slice( $images, 0, 1 );

        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        $ids = [];
        foreach( $images as $image ) {

            // Upload the remote image and get the attachment ID
            $attachment_id = media_sideload_image($image['url'], $post_id, '', 'id');

            // Check if the image was uploaded successfully
            if (!is_wp_error($attachment_id)) {
                $ids[] = $attachment_id;
            }

        }

        return $ids;

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