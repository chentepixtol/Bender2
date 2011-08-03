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
		$this->getView()->table = $table;
		$this->getView()->Bean = $table->getObject()->toUpperCamelCase();
		$this->getView()->bean = $table->getObject()->toCamelCase();
		$this->getView()->Collection = $table->getObject()->toUpperCamelCase().'Collection';
		$this->getView()->collection = $table->getObject()->toCamelCase().'Collection';
		$this->getView()->parent = $table->getParent();
		$this->getView()->fields = $table->getColumns();
	}
}
