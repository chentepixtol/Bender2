<?php


namespace Application\Database;

use Application\Base\Collectable;
use Doctrine\DBAL\Schema\Table as DoctrineTable;
use Application\Native\String;
use Application\Config\Configuration;

/**
 *
 *
 * @author chente
 *
 */
class Table implements Collectable
{

    /**
     *
     *
     * @var DoctrineTable
     */
    protected $doctrineTable;

    /**
     *
     * @var Database
     */
    protected $database;

    /**
     *
     *
     * @var ColumnCollection
     */
    protected $columns;

    /**
     *
     * @var Application\Config\Configuration
     */
    protected $configuration;

    /**
     *
     * @var Table
     */
    protected $parent;

    /**
     *
     * @var Application\Database\ForeignKeyCollection
     */
    protected $foreignKeys;

    /**
     *
     * @var Application\Native\String
     */
    protected $name;

    /**
     *
     * @var Application\Database\Column
     */
    protected $primaryKey;

    /**
     *
     * @var ManyToManyCollection
     */
    protected $manyToManyCollection;

    /**
     *
     *
     * @param DoctrineTable $table
     */
    public function __construct(DoctrineTable $table){
        $this->doctrineTable = $table;
        $this->columns = new ColumnCollection();
        $this->manyToManyCollection = new ManyToManyCollection();
    }

    /**
     * (non-PHPdoc)
     * @see Application\Base.Collectable::getIndex()
     */
    public function getIndex(){
        return $this->getObject()->toString();
    }

    /**
     *
     * @return Application\Native\String
     */
    public function getName()
    {
        if( !($this->name instanceof String) ){
            $this->name = new String($this->doctrineTable->getName(), String::UNDERSCORE);
        }
        return $this->name;
    }

    /**
     *
     * @return array
     */
    public function getDataForeignKeys()
    {
        $foreignKeys = array();
        foreach ($this->doctrineTable->getForeignKeys() as $foreignKey){
            $colums = $foreignKey->getForeignColumns();
            $foreignColumn = $colums[0];
            $colums = $foreignKey->getLocalColumns();
            $localColum = $colums[0];
            $foreignKeys[] = array(
                'name' => $foreignKey->getName(),
                'local' => $localColum,
                'localTable' => $foreignKey->getLocalTableName(),
                'foreign' => $foreignColumn,
                'foreignTable' => $foreignKey->getForeignTableName(),
            );
        }
        return $foreignKeys;
    }

    /**
     *
     * @return Application\Native\String
     */
    public function getObject()
    {
        if( !$this->configuration->has('object') ){
            $this->configuration->set('object', new String($this->getName()->toString()));
        }
        return $this->configuration->get('object');
    }

    /**
     *
     * @return Application\Config\Configuration
     */
    public function getOptions()
    {
        if( !$this->configuration->has('options') ){
            $this->configuration->set('options', new Configuration());
        }
        return $this->configuration->get('options');
    }

    /**
     * @return Database
     */
    public function getDatabase() {
        return $this->database;
    }

    /**
     * @param Database $database
     */
    public function setDatabase(Database $database) {
        $this->database = $database;
    }

    /**
     * @return Application\Config\Configuration
     */
    public function getConfiguration() {
        return $this->configuration;
    }

    /**
     * @param Application\Config\Configuration $configuration
     */
    public function setConfiguration(\Application\Config\Configuration $configuration) {
        $this->configuration = $configuration;
    }

    /**
     *
     * @param boolean $inSchema
     */
    public function setInSchema($inSchema){
        $this->configuration->set('inSchema', $inSchema);
    }

    /**
     *
     * @return boolean
     */
    public function inSchema(){
        return $this->configuration->get('inSchema', false);
    }

    /**
     * @return boolean
     */
    public function hasParent() {
        return ($this->parent instanceof Table);
    }

    /**
     * @return Application\Database\Table
     */
    public function getParent() {
        return $this->parent;
    }

    /**
     * @param Application\Database\Table $parentTable
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }

    /**
     *
     * @return Application\Database\ColumnCollection
     */
    public function getColumns(){
        $this->columns->rewind();
        return $this->columns;
    }

    /**
     * All columns include parents
     * @return Application\Database\ColumnCollection
     */
    public function getFullColumns()
    {
        $columns = new ColumnCollection();
        $columns->merge($this->getColumns());
        if( $this->hasParent() ){
            $columns->merge($this->getParent()->getFullColumns());
        }
        $columns->rewind();
        return $columns;
    }

    /**
     * @return Application\Database\ForeignKeyCollection
     */
    public function getForeignKeys() {
        return $this->foreignKeys;
    }

    /**
     * @param Application\Database\ForeignKeyCollection $foreignKeys
     */
    public function setForeignKeys($foreignKeys) {
        $this->foreignKeys = $foreignKeys;
    }

    /**
     * @return the $primaryKey
     */
    public function getPrimaryKey() {
        return $this->primaryKey;
    }

    /**
     *
     * @return \Application\Database\ManyToManyCollection
     */
    public function getManyToManyCollection(){
        $this->manyToManyCollection->rewind();
        return $this->manyToManyCollection;
    }

    /**
     *
     * @return boolean
     */
    public function hasPrimaryKey(){
        return ( ($this->primaryKey instanceof Column)
         && $this->primaryKey->isPrimaryKey());
    }

    /**
     * @param Column $primaryKey
     */
    public function setPrimaryKey($primaryKey) {
        $this->primaryKey = $primaryKey;
    }

}