<?php
namespace Modules\Bean;


use Application\Generator\File\FileCollection;
use Application\Generator\File\File;
use Application\Generator\Module\AbstractModule;
use Application\Database\Table;

/**
 *
 * @author chente
 *
 */
class Bean extends AbstractModule
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
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$tables = $this->getBender()->getDatabase()->getTables();
		$view =  $this->getBender()->getView($this);

		$files = new FileCollection();
		$tables->each(function (Table $table) use ($view, $files) {
			$view->clear();
			$view->table = $table;
			$content = $view->fetch('bean.tpl');
			$files->append(
				new File('application/models/beans/'.$table->getName()->toUpperCamelCase().'.php', $content)
			);
		});

		return $files;
	}




}
