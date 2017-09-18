<?php
namespace itsrb\classes;
require_once "{$_SERVER['DOCUMENT_ROOT']}/config/http.php";

trait auxiliary {
	static private $response, $instance;
		   private $temp;

	public function show() {
		if($this->data) {
			$this->set_head(['name' => 'Content-Type', 'value' => 'application/json']);
			echo $this->data;
		}
	}

	/*
	*	Implements Singleton
	*/
	static public function instantiate() {
		return (!self::$instance) ? self::$instance = new self : self::$instance;
	}

	/*
	*	Read raw data from input
	*	return $this; 
	*/
	static public function get_req_body() {
		self::$response = file_get_contents("php://input");
		return self::instantiate();
	}

	/* 
	*	@param string or null
	*	return $this
	*/	
	public function to_json(/*string*/){										
		$this->temp = (func_num_args() !== 0) ? 
						json_decode($this->trim_BOM(func_get_arg(0)), true) 
						: 
						json_decode($this->trim_BOM(self::$response), true);
		return $this;
	}

	/*
	*	@param stdObject or null
	*	return $this
	*/
	public function to_str(/*stdObject*/) {
		$this->temp = (func_num_args() !== 0) ? 
						json_encode(func_get_arg(0)) 
						:
						json_encode($this->temp);
		return $this;
	}

	/*
	* Trim BOM UTF-8 
	* @param $s string
	* return string
	*/
	public function trim_BOM($s){
		return trim($s, "\xef\xbb\xbf");
	}

	/*
	*	Getting body from http response
	*	return $this->data
	*/
	public function get() {
		return $this->data = $this->repsonse = $this->temp;
	} 
}

class http_client {
	use auxiliary;
	private $method, $ctype, $body, $res;

	/*
	*	Build http request 
	*	return $this->res resource
	*/
	private function build_query() {
		$authBase64 = base64_encode(AUTH);

		$opt = [
			"http" => [
				"method" => $this->method,
				"header" => "Content-Type: $this->ctype\r\nAuthorization: Basic ${authBase64}\r\n",
				"content" => $this->payload
			]
		];
		return $this->res = stream_context_create($opt);
	}
	/*
	*	return $this->data string 
	*/
	private function request() {
		$url = SCHEME . HTTP_HOST . HTTP_PORT . PATH;
		return $this->data = file_get_contents($url, false, $this->build_query());
	}

	/*
	* 	Getting http body
	* 	return $this or null
	*/
	public function get_body() {
		return($this->data) ? $this : null;
	}

	/*
	* 	Getting http headers
	* 	return $this
	*/
	public function get_head() {
		if($this->res) {
			$this->data = stream_get_meta_data($this->res);
		} 
		return $this;
	}

	/*
	*	Set http header 
	*	@param $header Array
	*/
	public function set_head($header) {
		header($header['name'] . ": " . $header['value']);
	}

	/*
	*	@param $method string valid HTTP method
	*	@param $payload json  
	*/
	public function __construct(/*$meth, $payload*/) {
		if(func_num_args() === 2) {
			$this->method = func_get_arg(0);
			$this->ctype = "application/json";
			$this->payload = func_get_arg(1);
			$this->request();
		}
	}

}
?>
