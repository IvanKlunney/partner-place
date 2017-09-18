<?php
namespace itsrb\classes;
use itsrb\classes\ldap_session as session; 
use itsrb\classes\pdo_ext as pdo; 
use itsrb\classes\http_client as req;
use itsrb\classes\router as r;
use itsrb\classes\directory_set as dir;

interface IApp {
	/* Session interface */
	public function make_session($login, $password);
	public function get_session();
	public function destroy_session();
	public function check_session_error();
	public function check_session_time();
	public function check_perm($d);

	/* Database interface */
	public function make_db();

	/* Traverse directory interface */
	public function make_dir_object($global_session);
	public function make_dir_iterator();
	public function download($filename);

	/* Http request interface */
	public function read_json_object();
	public function write_json_object($o);
	public function make_http_request($o);

	/* Generic interface */
	public function _require($t);
	static public function make_app();
}

class application implements IApp {
	static private $app;
	private $dir;

	public function make_session($login, $password) {
		return (new session($login, $password))->set();
	}

	public function get_session() {
		return session::get();
	}

	public function check_session_error() {
		return session::check_session_err();
	}

	public function check_session_time() {
		return session::check_session_time($this->get_session());
	}

	public function check_perm($fp) {
		return session::check_perm(dir::get_part_path($fp, null));
	}

	public function destroy_session() {
		return session::destroy();
	}

	public function make_db() {
		return new pdo;
	}

	public function make_dir_object($global_session) {
		return $this->dir = new dir($global_session);
	}

	public function make_dir_iterator() {
		return $this->dir->iteration();
	}

	public function download($filename) {
		return (!is_null($this->check_perm($filename))) ? dir::download($filename) : null;
	}

	public function read_json_object() {
		return req::get_req_body()->to_json()->get();
	}

	public function write_json_object($o) {
		req::instantiate()->to_str($o)->get();
		req::instantiate()->show();
	}

	public function make_http_request($o) {	
		$route = new r($o['ident'], $o['query']);
		req::instantiate()->to_str($route->resolve_template())->get();
		req::instantiate()->show();
	}

	public function _require($template) {
		return require_once($template);
	}

	static public function make_app() {
		return (!self::$app) ? self::$app = new self : self::$app;
	}
}
?>
