<?php
namespace Application\Database;

use Application\Bender\BaseCollection;
use Application\Database\ForeignKey;

/**
 *
 *
 * @author chente
 * @method Application\Database\ForeignKey current
 * @method Application\Database\ForeignKey read
 * @method Application\Database\ForeignKey getOne
 * @method Application\Database\ForeignKey getByPK
 * @method Application\Database\ForeignKeyCollection intersect
 */
class ForeignKeyCollection extends BaseCollection
{

	/**
	 * @param Application\Database\ForeignKey $table
	 * @return int
	 */
	protected function getIndex($foreignKey){
		return $foreignKey->getName();
	}

}