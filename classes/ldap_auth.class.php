<?php
namespace itsrb\classes;
require_once "{$_SERVER['DOCUMENT_ROOT']}/config/ldap.php";

class ldap_auth {
	private $resource, $result;
	public $ldap_err, $raw_sam, $sam, $cn;
	static private $attrs = ["sAMAccountName", "cn"];

	/*
	*	@param $host string
	*	@param $port integer
	*/
	private function _init($host, $port) {
		$this->resource = ldap_connect($host, $port);
	}

	/*
	* @param $dn bool; is true escape for bind, is false escape for search
	* return string 
	*/
	protected function _escape($is_same = true) {
		return ldap_escape($is_same ? $this->cn : $this->sam, null, $is_same ? LDAP_ESCAPE_DN : LDAP_ESCAPE_FILTER);
	}
	
	/*
	*	@param $res resource
	*/
	private function _errmsg($res) {
		return ldap_error($res);
	}

	/*
	*	@param $rdn string as base_dn
	*	@param $l	 string $attr value
	*	@param $passwd string
	*/
	private function _bind($rdn, $l, $passwd) {
		return ($retval = ldap_bind($this->resource, $this->_check_login($rdn, $l), $passwd))
				? $retval : ($this->ldap_err = $this->_errmsg($this->resource));
	}
	
	/*
	*	@param $opt string
	*	@param $val mixed
	*/
	private function _set_option($opt, $val) {
		return ldap_set_option($this->resource, $opt, $val);
	}

	/*
	*	@param $base_dn string 
	*	@param $login string
	*/
	private function _check_login($base_dn, $login) {
		$login = $this->_escape();
			return "CN=${login},${base_dn}";
	}


	/*
	*	return $result resource
	*/
	private function _search($sam) {
		return $this->result = ldap_search(
					$this->resource,
					/* RDN === BASE_DN */
					RDN,
					"sAMAccountName=${sam}",
					self::$attrs
				);
	}
	/*
	*	return array ([attr] => [value]) * count(self::attrs)
	*/
	protected function get_entry() {
		$this->raw_sam = $this->_escape(false);
		$this->_search($this->raw_sam);
		$f = ldap_first_entry($this->resource, $this->result);
		$i = count(self::$attrs) - 1;
			while($i >= 0) {
			 	$arr[self::$attrs[$i]] = ldap_get_values($this->resource, $f, self::$attrs[$i]);
			 	$i--;
			}

		return $arr;
	}

	/* 
	* 	Fetching client common name
	* 	@param $sam string
	* 	return $this->cn string
	*/
	private function _fetch_client_cn($sam) {
		if(!$this->resource) return;
			
		$this->cn = LDAP_LOGIN;
		$this->_bind(RDN, LDAP_LOGIN, LDAP_PASSWORD);
		$this->sam = $sam;
			return $this->cn = $this->get_entry()[self::$attrs[1]][0];
	}

	/*
	*	@param $login string CN=$login
	*	@param $passwd string attr userPassword
	*/
	public function __construct($login, $passwd) {
		$this->_init(LDAP_HOST, LDAP_PORT);
		/* Unicode support */
		$this->_set_option(LDAP_OPT_PROTOCOL_VERSION, 3);
		$this->_set_option(LDAP_OPT_NETWORK_TIMEOUT, 10);
	
		$this->_bind(RDN, $this->_fetch_client_cn($login), $passwd);
	}
}
?>
