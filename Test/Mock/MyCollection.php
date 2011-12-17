<?php

namespace Test\Mock;

use Application\Base\BaseCollection;

/**
 *
 * @author chente
 * @method Test\Mock\Item current
 * @method Test\Mock\Item read
 * @method Test\Mock\Item getOne
 * @method Test\Mock\Item getByPK
 * @method Test\Mock\MyCollection intersect
 */
class MyCollection extends BaseCollection
{
	/**
	 *
	 */
	public function upperCaseNames(){
		$collection = $this;
		return $this->lazyLoad('upperCaseNames', function() use($collection){
			$array = array();
			while ($collection->valid()) {
				$item = $collection->read();
				$array[] = strtoupper($item->getValue());
			}
			return $array;
		});
	}

}
