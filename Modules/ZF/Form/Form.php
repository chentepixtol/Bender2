<?php
namespace Modules\ZF\Form;

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
class Form extends BaseModule
{

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getName()
     */
    public function getName(){
        return 'Form';
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.AbstractModule::init()
     */
    public function init()
    {
        $classes = $this->getBender()->getClasses();
        $classes->add('BaseForm', new PhpClass($this->getApplicationNamespace()."Form/BaseForm.php"));
        $classes->add('BaseValidator', new PhpClass($this->getApplicationNamespace()."Validator/BaseValidator.php"));
        $classes->add('BaseFilter', new PhpClass($this->getApplicationNamespace()."Filter/BaseFilter.php"));

        $self = $this;
        $this->getBender()->getDatabase()->getTables()->filterUseForm()
        ->each(function (Table $table) use($classes, $self){
            $object = $table->getObject();
            $classes->add($object.'Form', new PhpClass($self->getApplicationNamespace()."Form/{$object}Form.php"));
            $classes->add($object.'Validator', new PhpClass($self->getApplicationNamespace()."Validator/{$object}Validator.php"));
            $classes->add($object.'Filter', new PhpClass($self->getApplicationNamespace()."Filter/{$object}Filter.php"));
        });
    }

    /**
     * (non-PHPdoc)
     * @see Application\Generator\Module.Module::getFiles()
     */
    public function getFiles()
    {
        $classes = $this->getBender()->getClasses();
        $tables = $this->getBender()->getDatabase()->getTables()->filterUseForm();

        $files = new FileCollection();
        $files->append(new File($classes->get('BaseForm')->getRoute(), $this->getView()->fetch('base-form.tpl')));
        $files->append(new File($classes->get('BaseValidator')->getRoute(), $this->getView()->fetch('base-validator.tpl')));
        $files->append(new File($classes->get('BaseFilter')->getRoute(), $this->getView()->fetch('base-filter.tpl')));

        while ( $tables->valid() )
        {
            $table = $tables->read();
            $this->shortcuts($table);
            $files->appendFromArray(array(
                new File($classes->get($table->getObject().'Form')->getRoute(), $this->getView()->fetch('form.tpl')),
                new File($classes->get($table->getObject().'Validator')->getRoute(), $this->getView()->fetch('validator.tpl')),
                new File($classes->get($table->getObject().'Filter')->getRoute(), $this->getView()->fetch('filter.tpl')),
            ));
        }

        return $files;
    }

}
