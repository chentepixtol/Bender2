<?php

namespace Application\Database;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Application\Bender\Event\Event;
use Application\Config\Schema;
use Application\Database\Database;

/**
 *
 *
 * @author chente
 *
 */
class DatabaseBuilder
{

	/**
	 *
	 *
	 * @var Doctrine\DBAL\Schema\AbstractSchemaManager
	 */
	protected $schemaManager;

	/**
	 *
	 *
	 * @var Application\Config\Schema
	 */
	protected $schema;

	/**
	 *
	 *
	 * @var Symfony\Component\EventDispatcher\EventDispatcher
	 */
	protected $eventDispatcher;

	/**
	 *
	 *
	 * @param AbstractSchemaManager $schemaManager
	 * @param Schema $schema
	 * @param EventDispatcher $eventDispatcher
	 */
	public function __construct(AbstractSchemaManager $schemaManager, Schema $schema, EventDispatcher $eventDispatcher)
	{
		$this->schemaManager = $schemaManager;
		$this->schema = $schema;
		$this->eventDispatcher = $eventDispatcher;
	}


	/**
	 *
	 * @return Application\Database\Database
	 */
	public function build()
	{
		$database = new Database();

		$this->eventDispatcher->dispatch(Event::DATABASE_BEFORE_INSPECT, new Event());
		$this->inspect($database);
		$this->eventDispatcher->dispatch(Event::DATABASE_AFTER_INSPECT, new Event(array('database' => $database)));

		$this->eventDispatcher->dispatch(Event::DATABASE_BEFORE_CONFIGURE, new Event(array('database' => $database)));
		$this->configure($database);
		$this->eventDispatcher->dispatch(Event::DATABASE_AFTER_CONFIGURE, new Event(array('database' => $database)));

		return $database;
	}


	/**
	 *
	 *
	 * @param Application\Database\Database $database
	 */
	private function inspect($database)
	{
		$tableBuilder = new TableBuilder($this->schema);

		$tables = $this->schemaManager->listTables();
		foreach ($tables as $doctrineTable){
			$table = $tableBuilder->build($doctrineTable);

			$table->setDatabase($database);
			$database->getTables()->append($table);
		}
	}

	/**
	 *
	 *
	 * @param Application\Database\Database $database
	 */
	private function configure($database)
	{
		$tables = $database->getTables();

		while ( $tables->valid() ) {
			$table = $tables->read();

			// Herencia
			$extends = $table->getConfiguration()->get('extends', false);
			if( $extends && $tables->contains($extends) ){
				$parent = $tables->getByPK($extends);
				$table->setParent($parent);
			}

			// Llaves Foraneas
			$foreignKeys = new ForeignKeyCollection();
			foreach ( $table->getDataForeignKeys() as $data ){
				$local = $table->getColumns()->getByPK($data['local']);
				$foreignTable =$tables->getByTablename($data['foreignTable']);
				$foreign = $foreignTable->getColumns()->getByPK($data['foreign']);
				$foreignKey = new ForeignKey($data['name'], $local, $foreign, $table, $foreignTable);
				$foreignKeys->append($foreignKey);
			}
			$table->setForeignKeys($foreignKeys);

		}
		$tables->rewind();
	}

}