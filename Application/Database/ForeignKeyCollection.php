<?php
namespace Application\Database;

use Application\Base\BaseCollection;
use Application\Database\ForeignKey;

/**
 *
 *
 * @author chente
 * @method Application\Database\ForeignKey current
 * @method Application\Database\ForeignKey read
 * @method Application\Database\ForeignKey getOne
 * @method Application\Database\ForeignKey getByPK
 * @method Application\Database\ForeignKeyCollection intersect
 */
class ForeignKeyCollection extends BaseCollection
{

    /**
     *
     * @var array
     */
    protected $localNames = array();

    /**
     * (non-PHPdoc)
     * @see Application\Base.BaseCollection::append()
     */
    public function append($object){
        parent::append($object);
        $this->localNames[$object->getLocal()->getIndex()] = $object->getIndex();
    }

    /**
     *
     * @param string $columnName
     * @return \Application\Database\ForeignKey
     */
    public function getByColumnName($columnName){
        if( !$this->containsColumnName($columnName) ){
            return null;
        }
        return $this->getByPK($this->localNames[$columnName]);
    }

    /**
     * @param string $columnName
     * @return boolean
     */
    public function containsColumnName($columnName){
        return isset($this->localNames[$columnName]);
    }

    /**
     * @return array
     */
    public function getColumnNames(){
       return $this->localNames;
    }

}