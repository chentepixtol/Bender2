<?php
namespace Application\Database;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Application\Base\Collectable;

/**
 *
 *
 * @author chente
 *
 */
class Column implements Collectable
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
	 * (non-PHPdoc)
	 * @see Application\Base.Collectable::getIndex()
	 */
	public function getIndex(){
		return $this->getName();
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