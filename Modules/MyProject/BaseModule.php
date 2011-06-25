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
	public function shortcuts(Table $table)
	{
		$this->view->table = $table;
		$this->view->Bean = $table->getObject()->toUpperCamelCase();
		$this->view->bean = $table->getObject()->toUpperCamelCase();
		$this->view->parent = $table->getParent();
		$this->view->fields = $table->getColumns();
	}
}
