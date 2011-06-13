<?php
namespace Modules\MyProject\Bean;

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
	 * @see Application\Generator\Module.Module::getFiles()
	 */
	public function getFiles()
	{
		$tables = $this->getBender()->getDatabase()->getTables();

		$files = new FileCollection();
		while ( $tables->valid() )
		{
			$table = $tables->read();
			$this->shorcuts($table);

			$content = $this->view->fetch('bean.tpl');
			$files->append(
				new File('application/models/beans/'.$table->getObject()->toUpperCamelCase().'.php', $content)
			);
		}

		return $files;
	}

}
