<?php
namespace Application\Database;

use Application\Base\BaseCollection;
use Application\Config\Configuration;

/**
 *
 *
 * @author chente
 * @method \Application\Database\Table current
 * @method \Application\Database\Table read
 * @method \Application\Database\Table getOne
 * @method \Application\Database\Table getByPK
 * @method \Application\Database\TableCollection intersect
 * @method \Application\Database\TableCollection filter
 */
class TableCollection extends BaseCollection
{

    /**
     * @var array
     */
    protected $tablenames = array();

    /**
     *
     */
    public function append($object)
    {
        parent::append($object);
        $this->tablenames[$object->getName()->toString()] = $object->getIndex();
    }

    /**
     * @return \Application\Database\Table
     */
    public function getByTablename($tablename)
    {
        return $this->getByPK($this->tablenames[$tablename]);
    }

    /**
     *
     * @return Application\Database\TableCollection
     */
    public function inSchema()
    {
        $collection = $this;
        return $this->lazyLoad('inSchema', function () use($collection){
            return $collection->filter(function (Table $table){
                return $table->inSchema();
            });
        });
    }

    /**
     * @return Application\Database\TableCollection
     */
    public function filterUseForm(){
        return $this->whereOption(function(Configuration $options){
            return $options->has('crud') || $options->has('form');
        });
    }

    /**
     * @return Application\Database\TableCollection
     */
    public function filterUseCRUD(){
        return $this->whereOption(function(Configuration $options){
            return $options->has('crud');
        });
    }

    /**
     * @return Application\Database\TableCollection
     */
    public function filterUseService(){
        return $this->whereOption(function(Configuration $options){
            return $options->has('service');
        });
    }

    /**
     *
     * @param Closure $function
     * @return Application\Database\TableCollection
     */
    public function whereOption($function){
        return $this->inSchema()->filter(function(Table $table) use($function){
            return $function($table->getOptions());
        });
    }



}