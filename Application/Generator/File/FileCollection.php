<?php
namespace Application\Generator\File;

use Application\Bender\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Generator\File\File current
 * @method Application\Generator\File\File read
 * @method Application\Generator\File\File getOne
 * @method Application\Generator\File\File getByPK
 * @method Application\Generator\File\FileCollection intersect
 */
class FileCollection extends BaseCollection
{

    /**
	 * @param File $table
	 * @return int
	 */
	protected function getIndex($column){
		return $column->getFullPath();
	}

}