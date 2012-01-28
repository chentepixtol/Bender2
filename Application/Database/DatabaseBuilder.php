<?php


namespace Application\Database;

use Application\Config\Configuration;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Application\Event\Event;
use Application\Config\Schema;
use Application\Database\Database;
use Application\Database\ConnectionHolder;

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
     * @var ConnectionHolder
     */
    protected $connectionHolder;

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
     * @var Database
     */
    protected $database;

    /**
     *
     * @var \Zend_Cache_Frontend
     */
    protected $cache;

    /**
     *
     *
     * @param ConnectionHolder $connectionHolder
     * @param Schema $schema
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(ConnectionHolder $connectionHolder, Schema $schema, EventDispatcher $eventDispatcher)
    {
        $this->connectionHolder = $connectionHolder;
        $this->schema = $schema;
        $this->eventDispatcher = $eventDispatcher;
        $frontendOptions = array(
           'lifetime' => 7200*10,
           'automatic_serialization' => true
        );
        $cacheDir = './cache/';
        if( !is_dir($cacheDir) ){
            @mkdir($cacheDir);
        }
        $backendOptions = array(
            'cache_dir' => $cacheDir
        );
        $this->cache = \Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
    }


    /**
     *
     * @return Application\Database\Database
     */
    public function build()
    {
        $this->database = $this->cache->load('database');

        if( null == $this->database ){
            $this->database = new Database();

            $this->eventDispatcher->dispatch(Event::DATABASE_BEFORE_INSPECT, new Event());
            $this->inspect($this->database);
            $this->eventDispatcher->dispatch(Event::DATABASE_AFTER_INSPECT, new Event(array('database' => $this->database)));

            $this->eventDispatcher->dispatch(Event::DATABASE_BEFORE_CONFIGURE, new Event(array('database' => $this->database)));
            $this->configure($this->database);
            $this->eventDispatcher->dispatch(Event::DATABASE_AFTER_CONFIGURE, new Event(array('database' => $this->database)));

            $this->cache->save($this->database, 'database');
        }

        return $this->database;
    }


    /**
     *
     *
     * @param Application\Database\Database $database
     */
    private function inspect($database)
    {
        $tableBuilder = new TableBuilder($this->schema);

        $tables = $this->connectionHolder->getConnection()->getSchemaManager()->listTables();
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
            if( $extends && $tables->containsIndex($extends) ){
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

        $this->setupRelations($tables);

        $tables->rewind();
    }

    /**
     *
     * @param TableCollection $tables
     */
    protected function setupRelations(TableCollection $tables)
    {
        $tables->rewind();
        while( $tables->valid() )
        {
            $table = $tables->read();

            // ManyToMany Relations
            $manyToManytables = $table->getConfiguration()->getByDotNotation('relations.many_to_many');
            if( $manyToManytables  instanceof Configuration ){
                foreach( $manyToManytables->toArray() as $manyToManytable ){
                    $manyToManytable = $tables->getByTablename($manyToManytable);
                    if( $manyToManytable instanceof Table ){

                        $foreignKeys = $manyToManytable->getForeignKeys();

                        if( $foreignKeys->count() == 2 ){

                            $foreignKey1 = $foreignKeys->read();

                            $otherTable1 = $tables->getByTablename($foreignKey1->getForeignTable()->getName()->toString());
                            $otherColumn1 = $otherTable1->getColumns()->getByPK($foreignKey1->getForeign()->getName()->toString());

                            $foreignKey2 = $foreignKeys->read();

                            $otherTable2 = $tables->getByTablename($foreignKey2->getForeignTable()->getName()->toString());
                            $otherColumn2 = $otherTable2->getColumns()->getByPK($foreignKey2->getForeign()->getName()->toString());

                            $manyToMany1 = new ManyToMany($otherColumn1, $foreignKey1->getLocal(), $foreignKey2->getLocal(), $manyToManytable, $otherTable2);
                            $otherTable1->getManyToManyCollection()->append($manyToMany1);

                            $manyToMany2 = new ManyToMany($otherColumn2, $foreignKey2->getLocal(), $foreignKey1->getLocal(), $manyToManytable, $otherTable1);
                            $otherTable2->getManyToManyCollection()->append($manyToMany2);
                        }
                    }
                }
            }
        }
    }

}