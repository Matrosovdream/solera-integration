<?php
/**
 * DVcars integration
 *
 * Plugin Name: DVcars integration
 * Plugin URI:  
 * Description: DVcars integration
 * Version:     1.0
 * Author:      Stanislav Matrosov
 * Author URI:  
 * License:     
 * License URI: 
 * Text Domain: classic-editor
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

require_once('classes/endpoints.class.php');
require_once('classes/process_xml.class.php');
require_once('classes/process_product.class.php');

add_action('init', 'init22');
function init22() {

	if( $_GET['postt'] ) {

		echo "<pre>";
		print_r( get_post_meta($_GET['postt'], 'api_data', true) );
		echo "</pre>";

		die();

	}

}