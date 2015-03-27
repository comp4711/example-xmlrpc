<?php

/**
 * This is a model for an airline, which knows places it flies
 * between and the schedule of such flights.
 * Flight schedule information is stored in an XML document,
 * The test data came from an Air Canada schedule some time back.
 *
 * @author jim
 */
class Airline extends CI_Model {

    protected $xml = null;

    // Constructor
    public function __construct() {
        parent::__construct();
        $this->xml = simplexml_load_file(DATAPATH . 'flight-schedule.xml');
    }

    // retrieve a list of departure cities
    function airports() {
        $airports = array();
        foreach ($this->xml->flight as $flight) {
            $place = (string) $flight->departure;
            $airports[$place] = $place;
        }
        return $airports;
    }

    // retrieve a list of destinations from a departure airport
    function reachable($code) {
        $airports = array();
        foreach ($this->xml->flight as $flight) {
            if ($code == (string) $flight->departure) {
                $place = (string) $flight->arrival;
                $airports[$place] = $place;
            }
        }
        return $airports;
    }

    // retrieve a list of flights between two places
    function flights($from, $to) {
        $flights = array();
        foreach ($this->xml->flight as $flight) {
            if ($from == (string) $flight->departure)
                if ($to == (string) $flight->arrival) {
                    $record = $this->extract($flight);
                    $flights[] = $record;
                }
        }
        return $flights;
    }

    // build a flight object from the XML element
    function extract($element) {
        $record = new stdClass();
        $record->number = (string) $element['number'];
        $record->type = (string) $element['type'];
        $record->frequency = (string) $element['frequency'];
        $record->stops = (string) $element['stope'];
        $record->from = (string) $element->departure;
        $record->leaves = (string) $element->departure['time'];
        $record->to = (string) $element->arrival;
        $record->arrives = (string) $element->arrival['time'];
        return $record;
    }

}
