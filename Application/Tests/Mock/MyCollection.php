<?php

namespace Application\Tests\Mock;

use Application\Bender\BaseCollection;

/**
 *
 * @author chente
 * @method Application\Tests\Mock\Item current
 * @method Application\Tests\Mock\Item read
 * @method Application\Tests\Mock\Item getOne
 * @method Application\Tests\Mock\Item getByPK
 * @method Application\Tests\Mock\MyCollection intersect
 */
class MyCollection extends BaseCollection
{
	/**
	 * (non-PHPdoc)
	 * @see Application\Bender.BaseCollection::getIndex()
	 */
	protected function getIndex($object)
	{
		return $object->getId();
	}
}
