<?php
class DV_seo {

    public function __construct() {
        
        add_action('init', array($this, 'init_set_seo'));

    }

    public function init_set_seo() {

        if( is_plugin_active('seo-by-rank-math/rank-math.php') || is_plugin_active('wordpress-seo/wp-seo.php') ) {
            add_filter('rank_math/frontend/title', array($this, 'set_title'));
            add_filter('rank_math/frontend/description', array($this, 'set_description'));
            //add_filter('rank_math/frontend/keywords', array($this, 'set_keywords'));
        } else {
            add_filter('wp_title', array($this, 'set_title'));
        }
        
    }

    public function set_title($title) {
        global $post; 

        if ( is_archive() && strpos($_SERVER['REQUEST_URI'], '/occasions/') !== false ) {
            $seo = $this->prepare_seo_data();
            $title = $seo['title'];
        }

        return $title;
    }

    public function set_description($description) {
        global $post; 
        
        if ( is_archive() && strpos($_SERVER['REQUEST_URI'], '/occasions/') !== false ) {
            $seo = $this->prepare_seo_data();
            $description = $seo['description'];
        }
        return $description;
    }

    public function set_keywords($keywords) {
        global $post; 
        
        if ( is_archive() && strpos($_SERVER['REQUEST_URI'], '/occasions/') !== false ) {
            $seo = $this->prepare_seo_data();
            $keywords = $seo['keywords'];
        }
        return $keywords;
    }

    public function prepare_seo_data() {

        // We get brand and model from the URL
        $url_parse = explode( "/", $_SERVER['SCRIPT_URL'] );
        if( isset($url_parse[2]) ) {
            $seo = DV_catalog::get_brand_seo( $url_parse[2], $url_parse[3] );
        }

        return $seo;

    }

}

new DV_seo();