<?php
namespace Application\Generator\Module;

use Application\Bender\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Generator\Module\Module current
 * @method Application\Generator\Module\Module read
 * @method Application\Generator\Module\Module getOne
 * @method Application\Generator\Module\Module getByPK
 * @method Application\Generator\Module\ModuleCollection intersect
 */
class ModuleCollection extends BaseCollection
{

	/**
	 *
	 *
	 * @param Module $module
	 */
    public function getIndex($module){
		return $module->getName();
   	}

}