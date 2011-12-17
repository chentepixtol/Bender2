<?php
namespace Modules\ZF2\Catalog;

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

		$self = $this;
		$this->getBender()->getDatabase()->getTables()->inSchema()->each(function (Table $table) use($classes, $self){
			$object = $table->getObject() .'Catalog';
			$classes->add($object, new PhpClass($self->getApplicationNamespace()."Model/Catalog/{$object}.php"));
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
		$ns = $this->getApplicationNamespace();
		return array(
			'Catalog' => array(
				'route' => $ns.'Model/Catalog/Catalog.php',
				'template' => 'catalog-interface.tpl',
			),'TransactionalCatalog' => array(
				'route' => $ns.'Model/Catalog/TransactionalCatalog.php',
				'template' => 'transactional-catalog.tpl',
			),'Storage' => array(
				'route' => $ns.'Storage/Storage.php',
				'template' => 'storage-interface.tpl',
			),'FactoryStorage' => array(
				'route' => $ns.'Storage/StorageFactory.php',
				'template' => 'storage-factory.tpl',
			),'AbstractCatalog' => array(
				'route' => $ns.'Model/Catalog/AbstractCatalog.php',
				'template' => 'abstract-catalog.tpl',
			),'DBAO' => array(
				'route' => $ns.'Database/DBAO.php',
				'template' => 'dbao.tpl',
			),'Singleton' => array(
				'route' => $ns.'Base/Singleton.php',
				'template' => 'singleton.tpl',
			),'MemoryStorage' => array(
				'route' => $ns.'Storage/Memory.php',
				'template' => 'memory-storage.tpl',
			),'NullStorage' => array(
				'route' => $ns.'Storage/Null.php',
				'template' => 'null-storage.tpl',
			),'FileStorage' => array(
				'route' => $ns.'Storage/File.php',
				'template' => 'file-storage.tpl',
			),'ChainStorage' => array(
				'route' => $ns.'Storage/Chain.php',
				'template' => 'chain-storage.tpl',
			),
		);
	}

}
