<?php
namespace Application\Database;

use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Application\Database\Table;
use Application\Config\Schema;

/**
 *
 *
 * @author chente
 *
 */
class TableBuilder
{

	/**
	 *
	 *
	 * @var Application\Config\Schema
	 */
	protected $schema;

	/**
	 *
	 *
	 * @param Application\Config\Schema $schema
	 */
	public function __construct(Schema $schema)
	{
		$this->schema = $schema;

	}

	/**
	 *
	 * @param DoctrineTable $doctrineTable
	 * @return Application\Database\Table
	 */
	public function build(DoctrineTable $doctrineTable)
	{
		$table = new Table($doctrineTable);
		$configuration = $this->schema->createConfiguration($table->getName()->toString());
		$table->setConfiguration($configuration);

		foreach($doctrineTable->getColumns() as $doctrineColumn){
			$column = new Column($doctrineColumn);
			$column->setTable($table);
			$table->getColumns()->append($column);
		}
		return $table;
	}
}