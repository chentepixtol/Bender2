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
		foreach ($this->getLibraries() as $object => $data){
			$classes->add($object, new PhpClass($data['route']));
		}

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
		foreach ($this->getLibraries() as $object => $data){
			$files->append(new File($classes->get($object)->getRoute(), $this->getView()->fetch($data['template'])));
		}

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

	/**
	 *
	 * @return array
	 */
	protected function getLibraries(){
		return array(
			'Catalog' => array(
				'route' => 'Application/Model/Catalog/Catalog.php',
				'template' => 'catalog-interface.tpl',
			),'Storage' => array(
				'route' => 'Application/Cache/Storage.php',
				'template' => 'storage-interface.tpl',
			),'AbstractCatalog' => array(
				'route' => 'Application/Model/Catalog/AbstractCatalog.php',
				'template' => 'abstract-catalog.tpl',
			),'DBAO' => array(
				'route' => 'Application/Database/DBAO.php',
				'template' => 'dbao.tpl',
			),'Singleton' => array(
				'route' => 'Application/Base/Singleton.php',
				'template' => 'singleton.tpl',
			),'MemoryStorage' => array(
				'route' => 'Application/Cache/Memory.php',
				'template' => 'memory-storage.tpl',
			),'NullStorage' => array(
				'route' => 'Application/Cache/Null.php',
				'template' => 'null-storage.tpl',
			),
		);
	}

}
