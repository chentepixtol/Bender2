<?php
namespace Modules\ZF\Query;

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
class Query extends BaseModule
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getName()
     */
    public function getName(){
        return 'Query';
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.AbstractModule::init()
     */
    public function init()
    {
        $ns = $this->getApplicationNamespace();
        $classes = $this->getBender()->getClasses();
        $classes->add('BaseQuery', new PhpClass($ns."Query/BaseQuery.php"));
        $this->getBender()->getDatabase()->getTables()->inSchema()->each(function (Table $table) use($classes, $ns){
            $object = $table->getObject().'Query';
            $builder = $table->getObject().'QueryBuilder';
            $classes->add($object, new PhpClass($ns."Query/{$object}.php"));
        });
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getFiles()
     */
    public function getFiles()
    {
        $classes = $this->getBender()->getClasses();

        $files = new FileCollection();
        $files->append(new File($classes->get('BaseQuery')->getRoute(), $this->getView()->fetch('base-query.tpl')));

        $tables = $this->getBender()->getDatabase()->getTables()->inSchema();
        while ( $tables->valid() )
        {
            $table = $tables->read();
            $this->shortcuts($table);
            $files->appendFromArray(array(
                new File($classes->get($table->getObject().'Query')->getRoute(), $this->getView()->fetch('query.tpl')),
            ));
        }

        return $files;
    }

}
