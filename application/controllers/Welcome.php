<?php

/**
 * Our homepage. Show the most recently added quote.
 * 
 * controllers/Welcome.php
 *
 * ------------------------------------------------------------------------
 */
define('LOCAL', false);   // control whether we access our model locally, or over XML-RPC
define('RPCSERVER', ('example.local/service')); // endpoint fo the XML-RPC server
define('RPCPORT', 80); // port the XML-RPC service is listening on

class Welcome extends Application {

	function __construct()
	{
		parent::__construct();
	}

	//-------------------------------------------------------------
	//  Homepage: show a list of places to fly from
	//-------------------------------------------------------------

	function index()
	{
		// get the list of airports that can be flown from
		$list = array();
		if (LOCAL)
		{
			// totally local operation
			$this->load->model('airline');
			$list = $this->airline->airports();
		} else
		{
			// use XML-RPC to get the list
			$this->load->library('xmlrpc');
			$this->xmlrpc->server(RPCSERVER, RPCPORT);
			$this->xmlrpc->method('getOrigins');
			$request = array();
			$this->xmlrpc->request($request);

			if (!$this->xmlrpc->send_request())
			{
				echo $this->xmlrpc->display_error();
				echo '<br/>' . var_dump($this->xmlrpc->response) . '<br/>';
			}

			$list = $this->xmlrpc->display_response();
		}

		// prepare the list for presentation
		$airports = array();
		foreach ($list as $key => $value)
		{
			$row = array('airport' => $value, 'name' => ucfirst($value));
			$airports[] = $row;
		}
		sort($airports);
		$this->data['airports'] = $airports;

		// Present the list to choose from
		$this->data['pagebody'] = 'homepage';
		$this->render();
	}

	function jav()
	{
		$this->load->library('xmlrpc');
//		$this->xmlrpc->server('nfl.local/rpc', 80);
		$this->xmlrpc->server('nfl.jlparry.com/rpc', 80);
		$this->xmlrpc->method('since');
//		$this->xmlrpc->set_debug(true);

		$request = array('20150830');
		$this->xmlrpc->request($request);

		if (!$this->xmlrpc->send_request())
		{
			echo $this->xmlrpc->display_error();
			echo '<br/>' . var_dump($this->xmlrpc->response) . '<br/>';
		}

		$list = $this->xmlrpc->display_response();

		var_dump($list);
		die();
	}

	//-------------------------------------------------------------
	//  Show a list of places to fly to
	//-------------------------------------------------------------

	function whereto($from)
	{
		// get the list of airports that can be flown to
		$list = array();
		if (LOCAL)
		{
			// totally local operation
			$this->load->model('airline');
			$list = $this->airline->reachable($from);
		} else
		{
			// use XML-RPC to get the list
			$this->load->library('xmlrpc');
			$this->xmlrpc->server(RPCSERVER, RPCPORT);
			$this->xmlrpc->method('getDestinations');

			$request = array($from);
			$this->xmlrpc->request($request);

			if (!$this->xmlrpc->send_request())
			{
				echo $this->xmlrpc->display_error();
				echo '<br/>' . var_dump($this->xmlrpc->response) . '<br/>';
			}

			$list = $this->xmlrpc->display_response();
		}

		// prepare the list for presentation
		$airports = array();
		foreach ($list as $key => $value)
		{
			$row = array('from' => $from, 'to' => $value, 'name' => ucfirst($value));
			$airports[] = $row;
		}
		sort($airports);
		$this->data['airports'] = $airports;
		$this->data['origin'] = ucfirst($from);
		$this->data['from'] = $from;

		// Present the list to choose from
		$this->data['pagebody'] = 'target';
		$this->render();
	}

	//-------------------------------------------------------------
	// Show the flights between these two airports
	//-------------------------------------------------------------
	function results($from, $to)
	{
		// get the list of airports that can be flown to
		$list = array();
		if (LOCAL)
		{
			// totally local operation
			$this->load->model('airline');
			$list = $this->airline->flights($from, $to);
		} else
		{
			// use XML-RPC to get the list
			$this->load->library('xmlrpc');
			$this->xmlrpc->server(RPCSERVER, RPCPORT);
			$this->xmlrpc->method('getFlights');

			$request = array($from, $to);
			$this->xmlrpc->request($request);

			if (!$this->xmlrpc->send_request())
			{
				echo $this->xmlrpc->display_error();
				echo '<br/>' . var_dump($this->xmlrpc->response) . '<br/>';
			}

			$list = $this->xmlrpc->display_response();
		}

		// prepare the list for presentation
		$flights = array();
		foreach ($list as $key => $value)
		{
			$row = (array) $value;
			$flights[] = $row;
		}
		$this->data['flights'] = $flights;
		$this->data['origin'] = ucfirst($from);
		$this->data['destination'] = ucfirst($to);

		// Present the list to choose from
		$this->data['pagebody'] = 'results';
		$this->render();
	}

}
