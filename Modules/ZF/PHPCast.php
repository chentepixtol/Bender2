<?php
namespace Modules\ZF;

use Application\Database\Cast\AbstractCast;

/**
 *
 * @author chente
 *
 */
class PHPCast extends AbstractCast
{

    /**
     * (non-PHPdoc)
     * @see Application\Database\Cast.AbstractCast::getType()
     */
    public function getType()
    {
        $column = $this->getColumn();
        if( $column->isBigint() || $column->isInteger() || $column->isSmallint() ){
            return 'int';
        }

        if( $column->isFloat() || $column->isDecimal() ){
            return 'float';
        }

        if( $column->isDate() || $column->isDatetime() || $column->isTime() ){
            return 'Datetime';
        }

        if( $column->isString() || $column->isText() ){
            return 'string';
        }

        if( $column->isBoolean() ){
            return 'boolean';
        }
    }

}
