<?php
namespace Modules\MyProject\Bean;

use Application\Generator\File\Routes;

use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Database\Table;
use Modules\MyProject\BaseModule;

/**
 *
 * @author chente
 *
 */
class Bean extends BaseModule
{

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getName()
	 */
	public function getName(){
		return 'Bean';
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.AbstractModule::init()
	 */
	public function init()
	{
		parent::init();

		$routes = $this->getBender()->getRoutes();
		$this->getBender()->getDatabase()->getTables()->each(function (Table $table) use($routes){
			if( $table->inSchema() ){
				$object = $table->getObject()->toString();
				$routes->addRoute($object, "application/models/beans/{$object}.php");
			}
		});
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$routes = $this->getBender()->getRoutes();
		$tables = $this->getBender()->getDatabase()->getTables();

		$files = new FileCollection();
		while ( $tables->valid() )
		{
			$table = $tables->read();
			if( $table->inSchema() ){
				$this->shortcuts($table);
				$content = $this->view->fetch('bean.tpl');
				$files->append(
					new File($routes->getRoute($table->getObject()->toString()), $content)
				);
			}
		}

		return $files;
	}

}
