<?php
namespace itsrb\classes;
require_once "{$_SERVER['DOCUMENT_ROOT']}/config/mysql.php";

/* DML operations */
interface IAnsi_SQL {
	/* $arr is a key => value storage */
	public function insert($arr);
	public function select($arr);
	public function update($arr);
	public function delete($arr);
}

class pdo_ext extends \PDO implements IAnsi_SQL {
	/* query, table_name, where expression */ 
	private $q, $t, $w;
	/* key => value */
	private $k, $v;
	public $result;

	public function __construct() {
		return parent::__construct("mysql:dbname=". DB .";host=localhost", MYSQL_USER, MYSQL_PASSWORD);
	}

	/*
	*	@param $meth string
	*/
	private function __get_method_name($meth) {
		return (new \ReflectionClass(__CLASS__))->getMethod($meth)->name;
	}

	/* 
	*	Cut "class_name::method_name" 
	*	@param $m string
	*	return method_name
	*/
	private function get_method_name($m) {
		return $this->__get_method_name(str_replace(__CLASS__."::", '', $m));
	}

	/* 
	*	@param $op string
	*/
	private function build_query($op) {
		if(!$op) return;

		switch($op) {
			case 'select':
				$this->q = "SELECT * FROM {$this->t} WHERE {$this->w['key']} = {$this->w['value']};";
				break;
			case 'update':
				$this->q = "UPDATE {$this->t} SET {$this->k} = {$this->v} WHERE {$this->w['key']} = {$this->w['value']};";
				break;
			case 'insert':
				break;
			case 'delete':
				break;
			default: 
		}
	}

	/* 
	*	@param $a Array
	*	return $this
	*/
	public function select($a){
		$this->t = $a['table'];
		$this->w = $a['where'];
		$this->build_query($this->get_method_name(__METHOD__));
		$this->result = $this->query($this->q);
		return $this;
	}

	/* 
	* 	TODO
	* 	Implements update, create and delete op
	*/ 
	public function update($a){}
	public function insert($a){}
	public function delete($a){}

	/*
	*	@param $col
	*	return $this->result->fetch()->column_name
	*/
	public function fetching($col){
		return $this->result->fetch(\PDO::FETCH_LAZY)->$col;
	}

	/*
	*	@param $col integer [index]
	*	return $this->result->fetch()->column_name
	*/
	public function fetchingAll($col){
		return $this->result->fetchAll(\PDO::FETCH_COLUMN, $col);
	}

}
?>
