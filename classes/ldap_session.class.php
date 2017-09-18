<?php
namespace itsrb\classes;
define("SESSION_NAME", "customer_id");
define("ORIGIN", "/");
define("PATH_TO", "/main.php");
define("COMMON_DIR", "common");
define("INDEX", "{$_SERVER['DOCUMENT_ROOT']}/index.php");
define("ACTIVITY", 1200);
define("MSG_SESSION_TIMEOUT", "Ваша сессия истекла");

class ldap_session extends ldap_auth {
	public $msg;
	static private $lifetime = 2 * 60 * 60, /* 2 hour */
				   $flag_is_auth = 1;
	/*
	*	Initial session
	*/
	static private function _start() {
		self::_set_param();
		session_start();
	}

	static private function _set_param() {
		session_set_cookie_params(self::$lifetime, ORIGIN, null, /* TODO fix */false, true);
		session_name(SESSION_NAME);
	}
	/*
	*	return $_SESSION['err'] string 
	*/
	public function set() {
		if(!is_null($this->_get_session_error())) return $_SESSION['err'] = $this->_get_session_error();
		if(!is_null($this->_get_ldap_error())) return $_SESSION['err'] = $this->_get_ldap_error();
									/* Sanity check */ 
		if(isset($this->sam) && ($this->sam === $this->raw_sam) && isset($_SESSION['oid'])) {
			$_SESSION['is_auth'] = self::$flag_is_auth;
			$_SESSION['user'] = $this->get_entry();
			$_SESSION['h'] = md5(rand() % (rand() / 2 >> 4));
			$_SESSION['redirect'] = true;
			self::_set_activity($_SESSION['oid']);
			self::_redirect("{$_SERVER['PHP_SELF']}?redirect=true&h={$_SESSION['h']}");
		} 
	}

	/*
	*	return $_SESSION['oid'] string
	*/  
	static public function get() {
		self::_start();
		/* Detecting AJAX request */
		$a = self::detecting_request();
		if(isset($_SESSION['is_auth']) && $_SERVER['SCRIPT_FILENAME'] === INDEX && !$a['is_ajax'] && (!isset($_SESSION['redirect'])) ) {
			self::_redirect(PATH_TO);
		} else if(!(isset($_SESSION['is_auth'])) && $_SERVER['REQUEST_URI'] !== ORIGIN && !$a['is_ajax']) {
			self::_redirect(ORIGIN);
		} else if(!(isset($_SESSION['is_auth'])) && $a['is_ajax']) {
			$a['timeout'] = false;
			return $a;
		} else {
			return (PHP_SESSION_ACTIVE === session_status()) ? $_SESSION['oid'] = session_id() : null;
		}
	}

	/*
	*	return const string or null
	*/
	static private function helper() {
		return ($_SERVER['REQUEST_URI'] === ORIGIN) ? null : ORIGIN;
	}

	static public function destroy() {
		session_destroy();
		self::_redirect(self::helper());
	}

	/*
	*	@param $location string
	*/
	static private function _redirect($location) {
		if(!is_null($location)) {
			header("Location: ${location}");
		}
	}

	/*
	*	@param $login string
	*	@param $password string
	*	return $msg string or null
	*/
	private function _check_userdata($login, $password) {
		return ( (!isset($login) && !isset($password)) || (empty($login) && empty($password)) ) ? ($this->msg = "Data is illegal") : null;
	}
	/*
	*	return ldap_err string
	*/
	private function _get_ldap_error() {
		return (!$this->ldap_err) ? null : $this->ldap_err;
	}

	/*
	*	return msg string (i.e. session error) 
	*/
	private function _get_session_error() {
		return (!$this->msg) ? null : $this->msg;
	}

	/*
	*	param $oid string
	*/
	static public function check_session_time($oid) {
		$retval = self::detecting_request();
			if( (isset($_SESSION['activity']) && $_SESSION['activity'] + ACTIVITY < time()) || session_id() !== $oid) {
					$_SESSION['err'] = MSG_SESSION_TIMEOUT;
					unset($_SESSION['is_auth']);
					$retval['timeout'] = true;
						if(!$retval['is_ajax']) {
							self::_redirect(ORIGIN);
						} else {
							return $retval;
						}
			} else {
				self::_set_activity($oid);
			}

	}

	/*
	*	return $_SESSION['activity'] or null
	*/
	static private function _set_activity($oid) {
		return ($oid) ? $_SESSION['activity'] = time() : null;
	}

	static public function check_session_err() {
		if(isset($_SESSION['err']) && !empty($_SESSION['err'])) {
			self::destroy();
		}
	}

	/*
	*	Detecting AJAX req from clientside
	*	return $a Array
	*/
	static private function detecting_request() {
		$a = [  'timeout' => null,
				'is_ajax' => null  ];

			$a['is_ajax'] = (isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
							 && $_SERVER['HTTP_X_REQUESTED_WITH'] === "xmlhttprequest") ? true : false;
		return $a;
	}

	/*
	*	Checking permission for downloading a file
	*	@param $d string Directory name
	*/
	static public function check_perm($d) {
		foreach ($_SESSION['clients'] as $k => $v) {
			if(is_array($v) && ($v['Code'] === $d || $d === COMMON_DIR)) {
				return $d;
			}
		}
		return null;
	}

	/*
	*	@param $l login string
	*	@param $p password string
	*/
	public function __construct($l, $p) {
			if(is_null($this->_check_userdata($l, $p))) {
				return parent::__construct($l, $p);
			} else {
				return false;
			}
	}
}	
?>