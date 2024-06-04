<?php
class DV_init {

    public function __construct() {

        // Classes
        $this->include_classes();

        // API endpoints
        $this->include_rest_api();

        // Hooks
        $this->include_hooks();

        // Shortcodes
        $this->include_shortcodes();

    }

    private function include_classes() {

        // Post types
        require_once( DV_PLUGIN_DIR_ABS.'/classes/posttypes.class.php');
        
        // API
        require_once( DV_PLUGIN_DIR_ABS.'/classes/morgenlease.api.php');

        // Process XML retrieved from API
        require_once( DV_PLUGIN_DIR_ABS.'/classes/process_xml.class.php');
        require_once( DV_PLUGIN_DIR_ABS.'/classes/process_product.class.php');

        // Helpers
        require_once( DV_PLUGIN_DIR_ABS.'/classes/product.helpers.php');
        require_once( DV_PLUGIN_DIR_ABS.'/classes/catalog.helpers.php');

        // Logs
        require_once( DV_PLUGIN_DIR_ABS.'/classes/logs.class.php');

        // SEO
        require_once( DV_PLUGIN_DIR_ABS.'/classes/urls.class.php');
        require_once( DV_PLUGIN_DIR_ABS.'/classes/seo.class.php');

        // Settings
        require_once( DV_PLUGIN_DIR_ABS.'/classes/settings.admin.php');
    
    }

    private function include_shortcodes() {
        require_once( DV_PLUGIN_DIR_ABS.'/shortcodes/lease_calculator.php');
    }

    private function include_rest_api() {
        require_once( DV_PLUGIN_DIR_ABS.'/classes/endpoints.class.php' );
    }

    private function include_hooks() {
        require_once( DV_PLUGIN_DIR_ABS.'/hooks/product.hooks.php' );
    }

}

new DV_init();