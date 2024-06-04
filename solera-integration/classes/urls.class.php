<?php
class DV_rewrite {

    public function __construct() {
        
        add_action('init', array($this, 'add_custom_rewrite_rule'));
        add_filter('query_vars', array($this, 'add_query_vars_filter'));
        //add_action('init', array($this, 'remove_rewrite_rules_init'));

    }

    public function add_custom_rewrite_rule() {

        // Common rule for links like /occasions/{merk}/{model}
        add_rewrite_rule('^occasions/([^/]+)/([^/]+)/?$', 'index.php?post_type=occasions&merk=$matches[1]&model=$matches[2]', 'top');
        //add_rewrite_rule('^occasions/([^/]+)/?$', 'index.php?post_type=occasions&merk=$matches[1]', 'top');
    
        // Rule for links like /occasions/{merk}
        foreach ($this->get_all_brands() as $slug=>$brand_title) {
            add_rewrite_rule('^occasions/' . $slug . '/?$', 'index.php?post_type=occasions&merk=' . $slug, 'top');
        }
    
        // Flush rewrite cache
        flush_rewrite_rules();
        
    }    
    
    function add_query_vars_filter($vars) {
        $vars[] = "merk";
        $vars[] = "model";
        return $vars;
    }
    
    public static function get_all_brands() {
    
        global $wpdb;
        // For the best performance
        $results = $wpdb->get_results("SELECT DISTINCT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'merk'", ARRAY_A);
        
        $data = array();
        foreach( $results as $item ) {
            $data[ sanitize_title($item['meta_value']) ] = $item['meta_value'];
        }
    
        return $data;
        
    }
    
    public static function get_all_models() {
    
        global $wpdb;
        // For the best performance
        $results = $wpdb->get_results("SELECT DISTINCT meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key = 'model'", ARRAY_A);
        
        $data = array();
        foreach( $results as $item ) {
            $data[ sanitize_title($item['meta_value']) ] = $item['meta_value'];
        }

        return $data;
    
    }

}

new DV_rewrite();