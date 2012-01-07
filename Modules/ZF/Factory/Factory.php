<?php
namespace Modules\ZF\Factory;

use Modules\ZF\PhpClass;
use Application\Generator\BaseClass;
use Application\Generator\Classes;
use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Database\Table;
use Modules\ZF\BaseModule;

/**
 *
 * @author chente
 *
 */
class Factory extends BaseModule
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getName()
     */
    public function getName(){
        return 'Factory';
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.AbstractModule::init()
     */
    public function init()
    {
        $ns = $this->getApplicationNamespace();
        $classes = $this->getBender()->getClasses();
        $classes->add('Factory', new PhpClass($ns."Model/Factory/Factory.php"));
        $this->getBender()->getDatabase()->getTables()->inSchema()->each(function (Table $table) use($classes, $ns){
            $object = $table->getObject().'Factory';
            $classes->add($object, new PhpClass($ns."Model/Factory/{$object}.php"));
        });
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getFiles()
     */
    public function getFiles()
    {
        $classes = $this->getBender()->getClasses();
        $tables = $this->getBender()->getDatabase()->getTables()->inSchema();

        $files = new FileCollection();
        $files->append(new File($classes->get('Factory')->getRoute(), $this->getView()->fetch('factory-interface.tpl')));
        while ( $tables->valid() )
        {
            $table = $tables->read();
            $route = $classes->get($table->getObject().'Factory')->getRoute();

            $this->shortcuts($table);
              //$this->getView()->fields = $table->getFullColumns();

            $files->append(
                new File($route, $this->getView()->fetch('factory.tpl'))
            );
        }

        return $files;
    }

}
