<?php
namespace Modules\MyProject\Unit;

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
class Unit extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Unit';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$classes = $this->getBender()->getClasses();
		$classes->add('BaseTest', new PhpClass("Test/Unit/BaseTest.php"));

		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject().'Test';
			$classes->add($object, new PhpClass("Test/Unit/{$object}.php"));
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
		$files->append(new File($classes->get('BaseTest')->getRoute(), $this->getView()->fetch('base-test.tpl')));

		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('bean.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Test')->getRoute(), $content)
			);
		}

		return $files;
	}

}
