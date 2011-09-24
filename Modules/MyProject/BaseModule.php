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
		$classes = $this->getBender()->getClasses();

		$this->getView()->table = $table;
		$this->getView()->parent = $table->getParent();
		$this->getView()->fields = $table->getColumns();
		$this->getView()->foreignKeys = $table->getForeignKeys();
		$this->getView()->primaryKey = $table->getPrimaryKey();

		$variableName = $table->getObject()->toCamelCase();
		$className = $table->getObject()->toUpperCamelCase();

		$this->getView()->Bean = $classes->get($className);
		$this->getView()->bean = $variableName;
		$this->getView()->Collection = $classes->get($className.'Collection');
		$this->getView()->collection = $variableName.'Collection';
		$this->getView()->Factory = $classes->get($className .'Factory');
		$this->getView()->factory = $variableName .'Factory';
		$this->getView()->Catalog = $classes->get($className .'Catalog');
		$this->getView()->catalog = $variableName.'Catalog';
		$this->getView()->Exception = $classes->get($className .'Exception');
		$this->getView()->exception = $variableName.'Exception';
		$this->getView()->Query = $classes->get($className .'Query');
		$this->getView()->query = $variableName.'Query';
		$this->getView()->Criteria = $classes->get($className .'Criteria');
		$this->getView()->criteria = $variableName.'Criteria';
	}
}
