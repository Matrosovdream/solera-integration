<?php
class API_posttypes {

    public function __construct() {
        add_action( 'init', [$this, 'cptui_register_my_cpts'] );
        add_action( 'add_meta_boxes', [$this, 'custom_metafields_brands'] );
        add_action( 'save_post', [$this, 'custom_metafields_brands_save_postdata'] );
    }

    public function cptui_register_my_cpts() {
        /**
         * Post Type: Occasions.
         */

        $labels = [
            "name" => esc_html__( "Occasions", "custom-post-type-ui" ),
            "singular_name" => esc_html__( "Occasion", "custom-post-type-ui" ),
        ];

        $args = [
            "label" => esc_html__( "Occasions", "custom-post-type-ui" ),
            "labels" => $labels,
            "description" => "",
            "public" => true,
            "publicly_queryable" => true,
            "show_ui" => true,
            "show_in_rest" => true,
            "rest_base" => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "rest_namespace" => "wp/v2",
            "has_archive" => "occasions",
            "show_in_menu" => true,
            "show_in_nav_menus" => true,
            "delete_with_user" => false,
            "exclude_from_search" => false,
            "capability_type" => "post",
            "map_meta_cap" => true,
            "hierarchical" => false,
            "can_export" => false,
            "rewrite" => [ "slug" => "occasions", "with_front" => true ],
            "query_var" => true,
            "supports" => [ "title", "editor", "thumbnail" ],
            "taxonomies" => [ "category", "post_tag" ],
            "show_in_graphql" => false,
        ];
        register_post_type( "occasions", $args );

        /**
        * Post Type: Brands.
        */
   
       $labels = [
           "name" => esc_html__( "Brands", "custom-post-type-ui" ),
           "singular_name" => esc_html__( "Brand", "custom-post-type-ui" ),
       ];
   
       $args = [
           "label" => esc_html__( "Brands", "custom-post-type-ui" ),
           "labels" => $labels,
           "description" => "",
           "public" => true,
           "publicly_queryable" => true,
           "show_ui" => true,
           "show_in_rest" => true,
           "rest_base" => "",
           "rest_controller_class" => "WP_REST_Posts_Controller",
           "rest_namespace" => "wp/v2",
           "has_archive" => false,
           "show_in_menu" => true,
           "show_in_nav_menus" => true,
           "delete_with_user" => false,
           "exclude_from_search" => false,
           "capability_type" => "post",
           "map_meta_cap" => true,
           "hierarchical" => false,
           "can_export" => false,
           "rewrite" => [ "slug" => "brands", "with_front" => true ],
           "query_var" => true,
           "supports" => [ "title", "editor", "thumbnail" ],
           "show_in_graphql" => false,
       ];
       register_post_type( "brands", $args );

    }

    public function custom_metafields_brands() {
        $screens = array( 'brands' );
        add_meta_box( 'custom_metafields_brands_settings', 'Brand settings', [$this, 'custom_metafields_brands_callback'], $screens, "advanced", "high" );
        add_meta_box( 'custom_metafields_brands_seo', 'SEO settings', [$this, 'custom_metafields_brands_seo_callback'], $screens, "advanced", "high" );
    }

    public function custom_metafields_brands_callback( $post, $meta ) {
        $screens = $meta['args'];

        // Nonce for verification
        wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

        echo "<br/>";
        $value = get_post_meta( $post->ID, 'brand_name', true );
        echo '<label for="brand_name">Brand title</label><br/>';
        echo '<input type="text" id="brand_name" name="brand_name" value="'. esc_attr( $value ) .'" size="25" />';

        $value = get_post_meta( $post->ID, 'model_name', true );
        echo "<br/><br/>";
        echo '<label for="model_name">Model title (optional)</label><br/>';
        echo '<input type="text" id="model_name" name="model_name" value="'. esc_attr( $value ) .'" size="25" />';
    }

    public function custom_metafields_brands_seo_callback( $post, $meta ) {
        $screens = $meta['args'];

        // Nonce for verification
        wp_nonce_field( plugin_basename(__FILE__), 'myplugin_noncename' );

        echo "<br/>";
        $value = get_post_meta( $post->ID, 'seo_title', true );
        echo '<label for="seo_title">Title</label><br/>';
        echo '<input type="text" id="seo_title" name="seo_title" value="'. esc_attr( $value ) .'" size="25" />';

        $value = get_post_meta( $post->ID, 'seo_description', true );
        echo "<br/><br/>";
        echo '<label for="seo_description">Description</label><br/>';
        echo '<input type="text" id="seo_description" name="seo_description" value="'. esc_attr( $value ) .'" size="25" />';

        $value = get_post_meta( $post->ID, 'seo_h1', true );
        echo "<br/><br/>";
        echo '<label for="seo_h1">H1</label><br/>';
        echo '<input type="text" id="seo_h1" name="seo_h1" value="'. esc_attr( $value ) .'" size="25" />';

    }

    public function custom_metafields_brands_save_postdata( $post_id ) {
        // Check nonce
        if ( ! isset( $_POST['myplugin_noncename'] ) || ! wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) ) {
            return;
        }

        // Decline auto-save
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Update fields
        $fields = ['brand_name', 'model_name', 'seo_title', 'seo_description', 'seo_keywords', 'seo_h1'];
        foreach( $fields as $field ) {
            if( isset( $_POST[$field] ) ) {
                update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
            }
        }

        // Slugs of titles
        update_post_meta( $post_id, 'brand_name_slug', sanitize_title( $_POST['brand_name'] ) );
        update_post_meta( $post_id, 'model_name_slug', sanitize_title( $_POST['model_name'] ) );


    }
}

new API_posttypes();