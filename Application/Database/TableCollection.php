<?php
namespace Application\Database;

use Application\Base\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Database\Table current
 * @method Application\Database\Table read
 * @method Application\Database\Table getOne
 * @method Application\Database\Table getByPK
 * @method Application\Database\TableCollection intersect
 * @method Application\Database\TableCollection filter
 */
class TableCollection extends BaseCollection
{

	/**
	 *
	 * @var TableCollection
	 */
	protected $onlyInSchema;

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
		$this->tablenames[$object->getName()->toString()] = $object->getIndex();
	}

	/**
	 * @return Application\Database\TableCollection
	 */
	public function getByTablename($tablename)
	{
		return $this->getByPK($this->tablenames[$tablename]);
	}

	/**
	 *
	 * @return Application\Database\TableCollection
	 */
	public function onlyInSchema()
	{
		if( null == $this->onlyInSchema ){
			$this->onlyInSchema = $this->filter(function (Table $table){
				return $table->inSchema();
			});
		}
		$this->onlyInSchema->rewind();
		return $this->onlyInSchema;
	}

}