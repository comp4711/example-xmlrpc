<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * XML-RPC server.
 * Uses local airline model to satisfy requests.
 */
class Service extends CI_Controller {

    // Constructor
    function __construct()
    {
	parent::__construct();
    }

    // Entry point. Register methods & dispatch request
    function index()
    {
	$data = array();

	$this->load->library('xmlrpc');
	$this->load->library('xmlrpcs');
	$this->xmlrpc->set_debug(TRUE);

	$config['functions']['getOrigins'] = array('function' => 'service.getOrigins');
	$config['functions']['getDestinations'] = array('function' => 'service.getDestinations');
	$config['functions']['getFlights'] = array('function' => 'service.getFlights');
	$config['object'] = $this;

	$this->xmlrpcs->initialize($config);
	$this->xmlrpcs->serve();
    }

    // Retrieve a list of possible flight origin airports
    function getOrigins($request)
    {
	$parameters = $request->output_parameters();

	$this->load->model('airline');
	$list = $this->airline->airports();

	$response = array(
	    $list,
	    'struct'
	);
	return $this->xmlrpc->send_response($response);
    }

    // Retrieve a list of possible flight origin airports
    function getDestinations($request)
    {
	$parameters = $request->output_parameters();
	$from = $parameters[0];

	$this->load->model('airline');
	$list = $this->airline->reachable($from);

	$response = array(
	    $list,
	    'struct'
	);
	return $this->xmlrpc->send_response($response);
    }

    function getFlights($request)
    {
	$parameters = $request->output_parameters();
	$from = $parameters[0];
	$to = $parameters[1];

	$this->load->model('airline');
	$flights = $this->airline->flights($from, $to);

	// massage the array of flight objects for response
	$response = array();
	foreach ($flights as $flight)
	    $response[] = array((array) $flight, 'struct');
	// and wrap it
	$response = array($response, 'array');

	return $this->xmlrpc->send_response($response);
    }

}

/**
 * Debug function
 */
function _p($var, $label = NULL)
{
    if ($label)
    {
	error_log(print_r('DEBUG: ' . $label . ';', 1), 0);
    }
    error_log(print_r($var, 1), 0);
}
