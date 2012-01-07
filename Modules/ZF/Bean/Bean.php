<?php
namespace Modules\ZF\Bean;

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
class Bean extends BaseModule
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getName()
     */
    public function getName(){
        return 'Bean';
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.AbstractModule::init()
     */
    public function init()
    {
        $classes = $this->getBender()->getClasses();
        $classes->add('Collectable', new PhpClass($this->getApplicationNamespace().'Model/Bean/Collectable.php'))
                ->add('Bean', new PhpClass($this->getApplicationNamespace()."Model/Bean/Bean.php"))
                ->add('AbstractBean', new PhpClass($this->getApplicationNamespace()."Model/Bean/AbstractBean.php"));

        $self = $this;
        $this->getBender()->getDatabase()->getTables()->inSchema()->each(function (Table $table) use($classes, $self){
            $object = $table->getObject();
            $classes->add($object, new PhpClass($self->getApplicationNamespace()."Model/Bean/{$object}.php"));
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
        $files->append(new File($classes->get('Bean')->getRoute(), $this->getView()->fetch('bean-interface.tpl')));
        $files->append(new File($classes->get('Collectable')->getRoute(), $this->getView()->fetch('collectable.tpl')));
        $files->append(new File($classes->get('AbstractBean')->getRoute(), $this->getView()->fetch('abstract-bean.tpl')));

        while ( $tables->valid() )
        {
            $table = $tables->read();
            $this->shortcuts($table);
            $content = $this->getView()->fetch('bean.tpl');
            $files->append(
                new File($classes->get($table->getObject())->getRoute(), $content)
            );
        }

        return $files;
    }

}
