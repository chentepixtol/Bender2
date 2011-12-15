<?php
namespace Modules\ZF2\Collection;

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
class Collection extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Collection';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$ns = $this->getApplicationNamespace();
		$classes = $this->getBender()->getClasses();
		$classes->add('Collection', new PhpClass($ns."Model/Collection/Collection.php"));

		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes, $ns){
			$object = $table->getObject() .'Collection';
			$classes->add($object, new PhpClass($ns."Model/Collection/{$object}.php"));
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
		$files->append(
			new File($classes->get('Collection')->getRoute(), $this->getView()->fetch('base-collection.tpl'))
		);

		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('collection.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Collection')->getRoute(), $content)
			);
		}

		return $files;
	}

}
