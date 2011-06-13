<?php
namespace Application\Database;

use Application\Database\TableCollection;

/**
 *
 * @author chente
 *
 */
class Database
{

	/**
	 *
	 * @var Application\Database\TableCollection
	 */
	protected $tables;

	/**
	 *
	 */
	public function __construct(){
		$this->tables = new TableCollection();
	}

	/**
	 *
	 * @return Application\Database\TableCollection
	 */
	public function getTables(){
		$this->tables->rewind();
		return $this->tables;
	}

}