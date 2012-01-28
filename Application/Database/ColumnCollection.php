<?php
namespace Application\Database;

use Application\Base\BaseCollection;

/**
 *
 *
 * @author chente
 * @method Application\Database\Column current
 * @method Application\Database\Column read
 * @method Application\Database\Column getOne
 * @method Application\Database\Column getByPK
 * @method Application\Database\ColumnCollection intersect
 */
class ColumnCollection extends BaseCollection
{

    /**
     *
     * @return \Application\Database\ColumnCollection
     */
    public function nonPrimaryKeys()
    {
        return $this->filter(function(Column $column){
            return !$column->isPrimaryKey();
        });
    }

    /**
     *
     * @return \Application\Database\ColumnCollection
     */
    public function nonForeignKeys()
    {
        return $this->filter(function(Column $column){
            return !$column->getTable()->getForeignKeys()->containsColumnName($column->getIndex());
        });
    }

}