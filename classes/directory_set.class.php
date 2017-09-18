<?php
namespace itsrb\classes;
define("DOC_ROOT", "{$_SERVER['DOCUMENT_ROOT']}/documentation/");

class directory_set {
	private $file, $dir, $clients, $idx, $j, $result_set;

	/*
	*	Mapping extensions to filepath
	*/
	static private $map = 
		[ 
			"xlsx" => "img/icons/xls.png",
			"docx" => "img/icons/doc.png",
			"zip" => "img/icons/zip.png",
			"pdf" => "img/icons/zip.png",
			"empty" => "img/icons/empty.png"
		];

	/*
	*	@param $filename string
	*/
	static private function __download($filename) {
		$fdir  = self::get_part_path($filename, null);
		$fname = self::get_part_path($filename, 1);
            if(file_exists(DOC_ROOT . "${fdir}/{$fname}")) {
                    header("Content-Type: application/octeat-stream");
                    header("Content-Disposition: attachment; filename=\"{$fname}\"");
                    header("X-Accel-Redirect: /documentation/{$fdir}/{$fname}");
            }
	}

	/*
	*	Public interface for $this->__download
	*	@param $fn string
	*/
	static public function download($fn) {
		return self::__download($fn);
	}

	/*
	*	Two argument function is null then return last directory name.
	*	@param $fp string 
	*	@param $idx number. 
	*	return $t[index] string
	*/
	static public function get_part_path($fp, $idx) {
		$t = explode('/', urldecode(base64_decode($fp)));
			return $t[ (is_null($idx)) ? count($t) - 2 : $idx];
	}

	/*
	*	@param $arr is $_SESSION
	*	return $arr['clients']
	*/
	private function prepare($arr) {
		if(is_array($arr) && array_key_exists('clients', $arr)) {
			// Common direcotry for all partners
			$arr['clients'][] = [ 'Code' => 'common', 'Name' => 'undefined' ]; 
			return $this->clients = $arr['clients'];
		}
	}

	/*
	*	Assemble Array 
	*	[ 
			Name  => Client Name
			Dir   => Directory Name
			Files => [
					somefile,
					....
			]
		]
	*/
	public function iteration() {	
		$len = count($this->clients);
		$this->j = $i = 0;
			while ($i < $len) {
				$this->idx = $i;
				if(!is_null($dir = $this->check_dir(DOC_ROOT . $this->clients[$i]['Code']))) {
					$this->result_set[ $this->clients[$i]['Code'] ] = [
						'Name' => $this->clients[$i]['Name'], 
						'Dir'  => $this->clients[$i]['Code'], 
							'Files' => [$this->j => []]
					];
					$this->try_open_dir($dir);
				}
				$i++;
				$this->j = 0;
			}
		return $this->result_set;// = array_values($this->result_set);
	}

	/*
	*	@param $dir string
	*	return $this->dir or null
	*/
	private function check_dir($dir) {
		return (is_dir($dir)) ? $this->dir = $dir : null;
	}

	/*
	*	@param $dir string
	*	return $this->result_set or null
	*/
	private function try_open_dir($dir) {
		$dd = opendir($dir);
		return ($dd) ? $this->try_traverse_file($dd) : null;  
	}

	/*
	*	@param $dd resource (d_entry)
	*	return $this->result_set
	*/
	private function try_traverse_file($dd) {
		$code = $this->clients[$this->idx]['Code'];
		while(false !== ($curr = readdir($dd))) {
			if(substr($curr, 0, 1) !== '.'){
				$this->result_set[$code]['Files'][$this->j]['fname'] = $this->get_filename($curr);
				$this->result_set[$code]['Files'][$this->j]['ext'] = $this->get_file_extension($curr);
				$this->j++;
			}
		}
		$this->result_set;
	}

	/* 
	*	@param $filename string
	*	return $filename extension
	*/
	private function get_file_extension($filename) {
		return explode('.', $filename)[count($filename)];
	}

	/*
	*	@param $filename string
	*	return $filename without extension
	*/
	private function get_filename($filename) {
		$tmp = explode('.', $filename);
		array_pop($tmp);
		return implode('.', $tmp);
	}

	/*
	*	@param $a (Builtin) Array i.e. $_SESSION
	*/
	public function __construct($a) {
		$this->prepare($a);
		return $this;
	}

	/*
	*	@param $arg string 
	*	return $map[$arg] or $arg
	*/
	public function get_ext_for_out($arg) {
		return (isset(self::$map[ $arg ])) ? self::$map[ $arg ] : self::$map['empty'];
	}

}
?>
