<?php

namespace Application\Database;

use Application\Base\Collectable;

/**
 *
 * @author chente
 *
 */
class ManyToMany implements Collectable
{

    /**
     *
     * @var Column
     */
    private $localColumn;

    /**
     *
     * @var Column
     */
    private $relationColumn;

    /**
     *
     * @var Column
     */
    private $relationForeignColumn;

    /**
     *
     * @var Table
     */
    private $relationTable;

    /**
     *
     * @var Table
     */
    private $foreignTable;


    /**
     *
     */
    public function __construct(Column $localColumn, Column $relationColumn,
    Column $relationForeignColumn, Table $relationTable, Table $foreignTable){
        $this->localColumn = $localColumn;
        $this->relationColumn = $relationColumn;
        $this->relationForeignColumn = $relationForeignColumn;
        $this->relationTable = $relationTable;
        $this->foreignTable = $foreignTable;
    }

    /**
     * (non-PHPdoc)
     * @see Application\Base.Collectable::getIndex()
     */
    public function getIndex(){
        return $this->foreignTable->getObject()->toString();
    }

    /**
     * @return Column
     */
    public function getLocalColumn(){
        return $this->localColumn;
    }

    /**
     * @return Column
     */
    public function getRelationColumn(){
        return $this->relationColumn;
    }

    /**
     * @return Column
     */
    public function getRelationForeignColumn(){
        return $this->relationForeignColumn;
    }

    /**
     * @return Table
     */
    public function getRelationTable(){
        return $this->relationTable;
    }

    /**
     * @return Table
     */
    public function getForeignTable(){
        return $this->foreignTable;
    }

}
