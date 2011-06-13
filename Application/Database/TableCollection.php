<?php
namespace Application\Database;

use Application\Bender\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Database\Table current
 * @method Application\Database\Table read
 * @method Application\Database\Table getOne
 * @method Application\Database\Table getByPK
 * @method Application\Database\TableCollection intersect
 */
class TableCollection extends BaseCollection
{

	/**
	 * @var array
	 */
	protected $tablenames = array();


	/**
	 *
	 */
	public function append($object)
	{
		parent::append($object);
		$this->tablenames[$object->getName()->toString()] = $this->getIndex($object);
	}

	/**
	 * @param Table $table
	 * @return int
	 */
	protected function getIndex($table){
		return $table->getObject()->toString();
	}

	/**
	 * @return Application\Database\TableCollection
	 */
	public function getByTablename($tablename){
		return $this->getByPK($this->tablenames[$tablename]);
	}

}