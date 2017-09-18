<?php
namespace itsrb\classes;
class router {
	public  $path_to_template;
	private $path, $route, $ident;
	/* 
	* Mapping route name to filepath 
	*/
	private $map = [
		'default' => 'templates/content/home.tpl',
		'doc' => 'templates/content/doc.tpl',
		'clients' => 'templates/content/client.tpl'
	];
	/*
	* @param $ident string
	* @param $route string 
	* return $this
	*/
	public function __construct($ident, $route) {
		$this->ident = $ident;
		$this->path = $this->route = $route;
		return $this;
	}

	/*
	* send POST request to 1c
	* 
	* return $jn Array
	*/
	private function send() {
		/* $s stdObject aka json */
		$s = new http_client("POST", http_client::instantiate()->to_str($this->ident)->get());
		/* $jn Array */
		$jn = http_client::instantiate()->to_json($s->data)->get();
		$_SESSION['clients'] = $jn['result']['Clients'];
		$jn['result']['Template'] = $this->path_to_template;
		return $jn;
	}

	/*
	* return $jn Array 
	*/
	public function resolve_template() {
		$this->path = explode("/", $this->route)[1];
		$this->path_to_template = $this->map[$this->path];
		return $this->send();
	}
}
?>