<?php


namespace Application\Database;

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
        $tables->rewind();
    }

}