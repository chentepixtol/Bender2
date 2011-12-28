<?php
namespace Modules\ZF2\CRUD;

use Modules\ZF2\PhpClass;

use Application\Generator\BaseClass;
use Application\Generator\Classes;
use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Database\Table;
use Modules\ZF2\BaseModule;

/**
 *
 * @author chente
 *
 */
class CRUD extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'CRUD';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$classes = $this->getBender()->getClasses();
		$self = $this;
		$this->getBender()->getDatabase()->getTables()->filterUseCRUD()->each(function (Table $table) use($classes, $self){
			$object = $table->getObject().'Controller';
			$classes->add($object, new PhpClass($self->getApplicationNamespace()."Controller/{$object}.php"));
		});
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$classes = $this->getBender()->getClasses();
		$tables = $this->getBender()->getDatabase()->getTables()->filterUseCRUD();

		$files = new FileCollection();
		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$controllerClass = $classes->get($table->getObject().'Controller');
			$tplDirectory = 'views/'.str_replace('-controller', '', $controllerClass->getName()->toSlug());
			$files->appendFromArray(array(
				new File($controllerClass->getRoute(), $this->getView()->fetch('controller.tpl')),
			    new File($tplDirectory.'/list.tpl', $this->getView()->fetch('list.tpl')),
			    new File($tplDirectory.'/new.tpl', $this->getView()->fetch('new.tpl')),
			));
		}

		return $files;
	}

}
