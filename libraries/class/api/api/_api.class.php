<?php
namespace Office_API;
abstract class API{
    /**
     * Property: method
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method = '';
    protected $parameters;
    protected $callfunction;
    protected $file = Null;

    public function __construct($request) {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
        $this->callfunction = $request;

        $this->method = strtoupper($_SERVER['REQUEST_METHOD']);
        switch ($this->method) {
            case 'GET':
                $this->parameters = $_GET;
            break;
            case 'POST':
                $this->parameters = $_POST;
            break;
            case 'PUT':
                parse_str(file_get_contents('php://input'), $this->parameters);
            break;
        }
    }
    public function processAPI() {
        if (method_exists($this, $this->callfunction)) {
            return $this->_response($this->{$this->callfunction}());
        }
        return $this->_response("No Endpoint: $this->callfunction", 404);
    }

    private function _response($data, $status = 200) {
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        return json_encode($data);
    }

    private function _requestStatus($code) {
        $status = array(  
            '100' => 'Continue',
            '200' => 'OK',
            '201' => 'Created',
            '202' => 'Accepted',
            '203' => 'Non-Authoritative Information',
            '204' => 'No Content',
            '205' => 'Reset Content',
            '206' => 'Partial Content',
            '300' => 'Multiple Choices',
            '301' => 'Moved Permanently',
            '302' => 'Found',
            '303' => 'See Other',
            '304' => 'Not Modified',
            '305' => 'Use Proxy',
            '307' => 'Temporary Redirect',
            '400' => 'Bad Request',
            '401' => 'Unauthorized',
            '402' => 'Payment Required',
            '403' => 'Forbidden',
            '404' => 'Not Found',
            '405' => 'Method Not Allowed',
            '406' => 'Not Acceptable',
            '409' => 'Conflict',
            '410' => 'Gone',
            '411' => 'Length Required',
            '412' => 'Precondition Failed',
            '413' => 'Request Entity Too Large',
            '414' => 'Request-URI Too Long',
            '415' => 'Unsupported Media Type',
            '416' => 'Requested Range Not Satisfiable',
            '417' => 'Expectation Failed',
            '500' => 'Internal Server Error',
            '501' => 'Not Implemented',
            '503' => 'Service Unavailable'
        ); 
        return ($status[$code])?$status[$code]:$status[500]; 
    }
}