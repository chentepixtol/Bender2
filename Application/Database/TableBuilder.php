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
        $table->setInSchema($configuration->has('object'));

        foreach($doctrineTable->getColumns() as $doctrineColumn)
        {
            $column = new Column($doctrineColumn);
            $column->setTable($table);
            $table->getColumns()->append($column);
        }

        $this->configure($table, $doctrineTable);

        return $table;
    }

    /**
     *
     *
     * @param Application\Database\Table $table
     * @param DoctrineTable $doctrineTable
     */
    protected function configure(Table $table, $doctrineTable)
    {
        $columnPrimary = null;
        if( $doctrineTable->hasPrimaryKey() ){
            $columnPrimaries = $doctrineTable->getPrimaryKey()->getColumns();
            $columnPrimary = $table->getColumns()->getByPK($columnPrimaries[0]);
        }

        $uniques = array();
        foreach ($doctrineTable->getIndexes() as $index){
            if( $index->isUnique() ){
                $columnsUnique = $index->getColumns();
                $uniques[] = $columnsUnique[0];
            }
        }

        foreach($doctrineTable->getColumns() as $doctrineColumn)
        {
            $column = $table->getColumns()->getByPK($doctrineColumn->getName());
            $isPrimary = ($columnPrimary === $column);
            $column->setIsPrimaryKey($isPrimary);
            $column->setIsUnique(in_array($column->getName()->toString(), $uniques));
            if( $isPrimary ) $table->setPrimaryKey($column);
        }
    }

}
