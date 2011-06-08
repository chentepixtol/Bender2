<?php
namespace Application\Database;


use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Application\Native\String;

/**
 *
 *
 * @author chente
 *
 */
class Table
{

	/**
	 *
	 *
	 * @var DoctrineTable
	 */
	protected $doctrineTable;

	/**
	 *
	 * @var Database
	 */
	protected $database;

	/**
	 *
	 *
	 * @var ColumnCollection
	 */
	protected $columns;

	/**
	 *
	 *
	 * @param DoctrineTable $table
	 */
	public function __construct(DoctrineTable $table){
		$this->doctrineTable = $table;
		$this->createColumns();
	}

	/**
	 *
	 * @var Application\Native\String
	 */
	protected $name;

	/**
	 *
	 *
	 */
	private function createColumns(){
		$this->columns = new ColumnCollection();
		foreach($this->doctrineTable->getColumns() as $doctrineColumn){
			$column = new Column($doctrineColumn);
			$column->setTable($this);
			$this->columns->append($column);
		}
	}

	/**
	 *
	 * @return string
	 */
	public function getName()
	{
		if( !($this->name instanceof String) ){
			$this->name = new String($this->doctrineTable->getName(), String::UNDERSCORE);
		}
		return $this->name;
	}

	/**
	 * @return Database
	 */
	public function getDatabase() {
		return $this->database;
	}

	/**
	 * @param Database $database
	 */
	public function setDatabase(Database $database) {
		$this->database = $database;
	}




}