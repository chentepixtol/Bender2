<?php

namespace Modules\Core;

use Application\Generator\Module\ModuleCollection;
use Application\Generator\ProjectInterface;
use Modules\Core\Schema\Schema;


/**
 *
 * @author chente
 *
 */
class Project implements ProjectInterface
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator.ProjectInterface::getModules()
     */
    public function getModules(){
        $modules = new ModuleCollection();
        $modules->append(new Schema());
        return $modules;
    }

}
