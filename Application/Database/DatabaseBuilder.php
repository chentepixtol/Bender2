<?php

namespace Application\Database;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Application\Bender\Event\Event;
use Application\Config\Schema;
use Application\Database\Database;


class DatabaseBuilder
{

	/**
	 *
	 * @var Database
	 */
	protected $database;

	/**
	 *
	 *
	 * @param AbstractSchemaManager $schemaManager
	 * @param Schema $schema
	 */
	public function __construct(AbstractSchemaManager $schemaManager, Schema $schema, EventDispatcher $eventDispatcher)
	{
		$this->database = new Database();

		$eventDispatcher->dispatch(Event::DATABASE_BEFORE_INSPECT, new Event());
		$this->inspect($schemaManager, $schema);
		$eventDispatcher->dispatch(Event::DATABASE_AFTER_INSPECT, new Event(array('database' => $this->database)));

		$eventDispatcher->dispatch(Event::DATABASE_BEFORE_CONFIGURE, new Event(array('database' => $this->database)));
		$this->configure();
		$eventDispatcher->dispatch(Event::DATABASE_AFTER_CONFIGURE, new Event(array('database' => $this->database)));
	}

	/**
	 *
	 *
	 * @param AbstractSchemaManager $schemaManager
	 * @param Schema $schema
	 */
	private function inspect(AbstractSchemaManager $schemaManager, Schema $schema)
	{
		$tables = $schemaManager->listTables();
		foreach ($tables as $doctrineTable){
			$table = new Table($doctrineTable);

			$configuration = $schema->createConfiguration($table->getName()->toString());
			$table->setConfiguration($configuration);

			$table->setDatabase($this->database);
			$this->database->getTables()->append($table);
		}
	}

	/**
	 *
	 *
	 */
	private function configure()
	{
		$tables = $this->database->getTables();

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

	/**
	 *
	 * @return Application\Database\Database
	 */
	public function getDatabase(){
		return $this->database;
	}

}