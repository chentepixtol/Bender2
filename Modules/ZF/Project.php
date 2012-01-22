<?php

namespace Modules\ZF;

use Application\Generator\Module\ModuleCollection;
use Application\Generator\ProjectInterface;
use Modules\ZF\Unit\Unit;
use Modules\ZF\Query\Query;
use Modules\ZF\Form\Form;
use Modules\ZF\Factory\Factory;
use Modules\ZF\CRUD\CRUD;
use Modules\ZF\Collection\Collection;
use Modules\ZF\Catalog\Catalog;
use Modules\ZF\Exception\Exception;
use Modules\ZF\Bean\Bean;

/**
 *
 * @author chente
 *
 */
class Project implements ProjectInterface
{

    /**
     *
     * @var ModuleCollection
     */
    private $modules;

    /**
     *
     */
    public function __construct(){
        $this->modules = new ModuleCollection();
        $this->modules->appendFromArray(array(
            new Bean(),
            new Catalog(),
            new Collection(),
            new CRUD(),
            new Exception(),
            new Factory(),
            new Form(),
            new Query(),
            new Unit(),
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator.ProjectInterface::getModules()
     */
    public function getModules(){
        return $this->modules;
    }

}
