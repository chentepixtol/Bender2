<?php
namespace Modules\ZF2\Query;

use Application\Generator\PhpClass;

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
class Query extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Query';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		$classes = $this->getBender()->getClasses();
		$classes->add('BaseQuery', new PhpClass("Application/Query/BaseQuery.php"));
		$this->getBender()->getDatabase()->getTables()->onlyInSchema()->each(function (Table $table) use($classes){
			$object = $table->getObject().'Query';
			$criteria = $table->getObject().'Criteria';
			$classes->add($object, new PhpClass("Application/Query/{$object}.php"));
			$classes->add($criteria, new PhpClass("Application/Query/{$criteria}.php"));
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
		$files->append(new File($classes->get('BaseQuery')->getRoute(), $this->getView()->fetch('base-query.tpl')));
		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('query.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Query')->getRoute(), $content)
			);
		}

		$tables->rewind();
		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shortcuts($table);
			$content = $this->getView()->fetch('criteria.tpl');
			$files->append(
				new File($classes->get($table->getObject().'Criteria')->getRoute(), $content)
			);
		}

		return $files;
	}

}
