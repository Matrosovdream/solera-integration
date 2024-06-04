<?php
/**
 * Solera integration
 *
 * Plugin Name: Solera integration
 * Plugin URI:  
 * Description: Solera integration
 * Version:     1.0
 * Author:      Stanislav Matrosov
 * Author URI:  matrosovdream@gmail.com
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

// defines
define('DV_PLUGIN_DIR_ABS', WP_PLUGIN_DIR . '/api-cars');
define('DV_PLUGIN_DIR', plugin_dir_url( __FILE__ ));

// Initial class
require_once('classes/init.class.php');




