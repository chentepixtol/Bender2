<?php
namespace Application\Database;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;

/**
 *
 *
 * @author chente
 *
 */
class Column
{

	/**
	 *
	 *
	 * @var DoctrineColumn
	 */
	protected $doctrineColumn;

	/**
	 *
	 * @var Table
	 */
	protected $table;

	/**
	 *
	 *
	 * @param DoctrineColumn $column
	 */
	public function __construct(DoctrineColumn $column){
		$this->doctrineColumn = $column;
	}

	/**
	 *
	 * @return string
	 */
	public function getName(){
		return $this->doctrineColumn->getName();
	}

	/**
	 * @return Table
	 */
	public function getTable() {
		return $this->table;
	}

	/**
	 * @param Table $table
	 */
	public function setTable(Table $table) {
		$this->table = $table;
	}

}