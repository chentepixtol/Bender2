<?php
namespace Application\Database;

use Application\Config\Schema;
use Doctrine\DBAL\Schema\AbstractSchemaManager;


class Database {

	/**
	 *
	 * @var Application\Database\TableCollection
	 */
	protected $tables;

	/**
	 *
	 */
	public function __construct(){
		$this->tables = new TableCollection();
	}

	/**
	 *
	 *
	 */
	public function inspect(AbstractSchemaManager $schemaManager, Schema $schema)
	{
		$tables = $schemaManager->listTables();
		foreach ($tables as $doctrineTable){
			$table = new Table($doctrineTable);
			$table->setDatabase($this);
			$configuration = $schema->createConfiguration($table->getName()->toString());
			$table->setConfiguration($configuration);
			$this->tables->append($table);
		}
	}

	/**
	 *
	 * @return Application\Database\TableCollection
	 */
	public function getTables(){
		$this->tables->rewind();
		return $this->tables;
	}

}