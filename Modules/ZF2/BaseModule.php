<?php

namespace Modules\ZF2;

use Application\Native\String;
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
		$this->getView()->Bean = $bean = $classes->get($table->getObject()->toString());
		$this->getView()->bean = $bean->getName()->toCamelCase();

		$sufixes = array('Collection', 'Factory', 'Catalog', 'Exception',
				         'Query', 'Criteria', 'Form', 'Validator', 'Filter');
		foreach( $sufixes as $suffix ){
			$this->addShorcutBySuffix($table, $suffix);
		}
	}

	/**
	 *
	 * @param Table $table
	 * @param string $suffix
	 */
	protected function addShorcutBySuffix(Table $table, $suffix = '')
	{
		$variableName = $table->getObject()->toCamelCase();
		$className = $table->getObject()->toUpperCamelCase();
		$classes = $this->getBender()->getClasses();
		$varname = new String($suffix, String::UPPERCAMELCASE);
		$this->getView()->assign($suffix, $classes->get($className.$suffix));
		$this->getView()->assign($varname->toCamelCase(), $variableName.$suffix);
	}

	/**
	 * (non-PHPdoc)
	 * @see Application\Generator\Module.Module::getSubscriber()
	 */
	public function getSubscriber(){
		return new Subscriber();
	}
}
