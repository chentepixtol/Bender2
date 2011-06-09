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
	 * @param AbstractSchemaManager $schemaManager
	 * @param Schema $schema
	 */
	public function inspect(AbstractSchemaManager $schemaManager, Schema $schema)
	{
		$tables = $schemaManager->listTables();
		foreach ($tables as $doctrineTable){
			$table = new Table($doctrineTable);

			$configuration = $schema->createConfiguration($table->getName()->toString());
			$table->setConfiguration($configuration);

			$table->setDatabase($this);
			$this->tables->append($table);
		}
		$this->tables->rewind();
	}

	/**
	 *
	 *
	 */
	public function configure()
	{
		$this->tables->rewind();
		while ($this->tables->valid()) {
			$table = $this->tables->read();

			$extends = $table->getConfiguration()->get('extends', false);
			if( $extends && $this->tables->contains($extends) ){
				$parent = $this->tables->getByPK($extends);
				$table->setParent($parent);
			}
		}
		$this->tables->rewind();
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