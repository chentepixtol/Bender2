<?php
namespace Application\Database;

use Application\Native\String;

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
	 * @var Doctrine\DBAL\Schema\Column
	 */
	protected $doctrineColumn;

	/**
	 *
	 * @var Table
	 */
	protected $table;

	/**
	 *
	 * @var Application\Native\String
	 */
	protected $name;

	/**
	 *
	 * @var boolean
	 */
	protected $isPrimaryKey;

	/**
	 *
	 * @var boolean
	 */
	protected $isUnique;

	/**
	 *
	 *
	 * @param Doctrine\DBAL\Schema\Column $column
	 */
	public function __construct(DoctrineColumn $column){
		$this->doctrineColumn = $column;
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Base.Collectable::getIndex()
	 */
	public function getIndex(){
		return $this->getName()->toString();
	}

	/**
	 *
	 * @return Application\Native\String
	 */
	public function getName(){
		if( null == $this->name ){
			$this->name = new String($this->doctrineColumn->getName(), String::UNDERSCORE);
		}
		return $this->name;
	}

	/**
	 * @return boolean
	 */
	public function isPrimaryKey() {
		return $this->isPrimaryKey;
	}

	/**
	 * @param boolean $isPrimaryKey
	 */
	public function setIsPrimaryKey($isPrimaryKey) {
		$this->isPrimaryKey = $isPrimaryKey;
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

	/**
	 * @param boolean $isUnique
	 */
	public function setIsUnique($isUnique) {
		$this->isUnique = $isUnique;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isUnique(){
		return $this->isUnique;
	}

}