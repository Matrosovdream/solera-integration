<?php
class DV_rest extends WP_REST_Controller {

    public function __construct() {

        add_action( 'rest_api_init', array($this, 'register_routes') );

    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        $namespace = 'occasions/v1';
        $base = 'receive/';

        // http://www.staging.next-move.nl/wp-json/occasions/v1/receive/
        register_rest_route( $namespace, '/' . $base, array(
            array(
            'methods'             => WP_REST_Server::EDITABLE,
            'callback'            => array( $this, 'receive_item' ),
            'permission_callback' => '__return_true',
            //'show_in_index' => true,
            //'args' => array(),
            ),
        ));
        
    }
  
    public function receive_item( $request ) {

        $xmldoc = file_get_contents('php://input');

        $dv = new DV_process_xml($xmldoc);
        $dv->process();
        
        return new WP_REST_Response( 1, 200 );

    }

  
    public function get_item_permissions_check( $request ) {
        return true;
    }
  
    public function get_rest_apis() {

        $routes = rest_get_server()->get_routes();

		$endpoints = array();

		// Iterate through routes and extract endpoint information
		foreach ($routes as $route => $route_data) {
			$endpoints[] = array(
				'route'     => $route,
				'methods'   => $route_data['methods'],
				'args'      => $route_data['args'],
			);
		}

        return $endpoints;

    }




}

new DV_rest();


