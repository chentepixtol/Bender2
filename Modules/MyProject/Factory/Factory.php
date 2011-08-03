<?php
namespace Modules\MyProject\Factory;

use Application\Database\ColumnCollection;

use Application\Generator\PhpClass;

use Application\Generator\BaseClass;
use Application\Generator\Classes;
use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Database\Table;
use Modules\MyProject\BaseModule;

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
		$classes = $this->getBender()->getClasses();
		$classes->add('Factory', new PhpClass("Application/Base/Factory.php"));
		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject().'Factory';
			$classes->add($object, new PhpClass("Application/Model/Factory/{$object}.php"));
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
		$files->append(new File($classes->get('Factory')->getRoute(), $this->getView()->fetch('factory-interface.tpl')));
		while ( $tables->valid() )
		{
			$fields = new ColumnCollection();

			$table = $tables->read();
			$route = $classes->get($table->getObject().'Factory')->getRoute();
			$this->shortcuts($table);

			$fields->merge($table->getColumns());
		  	while ( $table->hasParent() ){
		  		$table = $table->getParent();
		  		$fields->merge($table->getColumns());
		  	}

		  	$this->getView()->fields = $fields;
			$content = $this->getView()->fetch('factory.tpl');
			$files->append(
				new File($route, $content)
			);
		}

		return $files;
	}

}
