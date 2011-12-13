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
		$classes->add('BaseCollectionTest', new PhpClass("Test/Unit/Collection/BaseCollectionTest.php"));

		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject();
			$classes->add($object.'Test', new PhpClass("Test/Unit/Bean/{$object}Test.php"));
			$classes->add($object.'FactoryTest', new PhpClass("Test/Unit/Factory/{$object}FactoryTest.php"));
			$classes->add($object.'CatalogTest', new PhpClass("Test/Unit/Catalog/{$object}CatalogTest.php"));
			$classes->add($object.'QueryTest', new PhpClass("Test/Unit/Query/{$object}QueryTest.php"));
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
		$files->append(new File($classes->get('BaseCollectionTest')->getRoute(), $this->getView()->fetch('base-collection-test.tpl')));
		$files->append(new File('Test/bootstrap.php', $this->getView()->fetch('bootstrap.tpl')));
		$files->append(new File('phpunit.xml', $this->getView()->fetch('phpunit.tpl')));

		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$files->appendFromArray(array(
				new File($classes->get($table->getObject().'Test')->getRoute(), $this->getView()->fetch('bean.tpl')),
				new File($classes->get($table->getObject().'FactoryTest')->getRoute(), $this->getView()->fetch('factory.tpl')),
				new File($classes->get($table->getObject().'CatalogTest')->getRoute(), $this->getView()->fetch('catalog.tpl')),
				new File($classes->get($table->getObject().'QueryTest')->getRoute(), $this->getView()->fetch('query.tpl')),
			));
		}

		return $files;
	}

}
