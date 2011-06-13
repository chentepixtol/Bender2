<?php

namespace Modules\MyProject;

use Application\Database\Table;
use Application\Generator\Module\AbstractModule;

abstract class BaseModule extends AbstractModule
{

	/**
	 *
	 * @param Table $table
	 */
	public function shorcuts(Table $table)
	{
		$this->view->clear();
		$this->view->table = $table;
		$this->view->Bean = $table->getObject()->toUpperCamelCase();
	}
}
