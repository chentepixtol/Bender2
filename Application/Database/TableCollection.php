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
	 * @param Table $table
	 * @return int
	 */
	protected function getIndex($table){
		return $table->getName()->toString();
	}



}