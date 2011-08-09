<?php
namespace Modules\MyProject\Catalog;

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
class Catalog extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Catalog';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$classes = $this->getBender()->getClasses();
		$classes->add('Catalog', new PhpClass('Application/Base/Catalog.php'))
				->add('AbstractCatalog', new PhpClass('Application/Database/AbstractCatalog.php'))
				->add('DBAO', new PhpClass('Application/Database/DBAO.php'))
				->add('Singleton', new PhpClass('Application/Base/Singleton.php'));
		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject() .'Catalog';
			$classes->add($object, new PhpClass("Application/Model/Catalog/{$object}.php"));
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
		$files->append(new File($classes->get('Catalog')->getRoute(), $this->getView()->fetch('catalog-interface.tpl')));
		$files->append(new File($classes->get('AbstractCatalog')->getRoute(), $this->getView()->fetch('abstract-catalog.tpl')));
		$files->append(new File($classes->get('DBAO')->getRoute(), $this->getView()->fetch('dbao.tpl')));
		$files->append(new File($classes->get('Singleton')->getRoute(), $this->getView()->fetch('singleton.tpl')));

		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('catalog.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Catalog')->getRoute(), $content)
			);
		}

		return $files;
	}

}
