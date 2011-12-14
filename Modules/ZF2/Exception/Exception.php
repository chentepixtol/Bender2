<?php
namespace Modules\ZF2\Exception;

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
class Exception extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Exception';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$classes = $this->getBender()->getClasses();

		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject().'Exception';
			$classes->add($object, new PhpClass("Application/Model/Exception/{$object}.php"));
		});
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$classes = $this->getBender()->getClasses();
		$tables = $this->getBender()->getDatabase()->getTables()->onlyInSchema();

		$files = new FileCollection();
		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('exception.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Exception')->getRoute(), $content)
			);
		}

		return $files;
	}

}
