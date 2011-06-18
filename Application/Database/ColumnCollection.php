<?php
namespace Application\Database;

use Application\Base\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Database\Column current
 * @method Application\Database\Column read
 * @method Application\Database\Column getOne
 * @method Application\Database\Column getByPK
 * @method Application\Database\ColumnCollection intersect
 */
class ColumnCollection extends BaseCollection
{

	/**
	 * @param Column $table
	 * @return int
	 */
	protected function getIndex($column){
		return $column->getName();
	}

}