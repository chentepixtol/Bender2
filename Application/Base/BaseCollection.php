<?php
namespace Application\Base;

/**
 *
 *
 * @author chente
 *
 */
abstract class BaseCollection extends \ArrayIterator
{

    /**
     *
     *
     * @return BaseCollection
     */
    protected function makeColletion(){
    	return new static();
    }

    /**
     *
     * validate object
     * @param Collectable $object
     */
    protected function validate($object)
    {
		if( !($object instanceof Collectable) ){
			throw new \InvalidArgumentException("Debe de cumplir con la Interface Collectable");
		}
    }

    /**
     * Appends the value
     * @param Collectable $object
     */
    public function append($object)
    {
    	$this->validate($object);
        parent::offsetSet($object->getIndex(), $object);
        $this->rewind();
    }

    /**
     *
     * Append many
     * @param array $objects
     */
    public function appendFromArray($objects){
    	foreach ($objects as $object){
    		$this->append($object);
    	}
    }

    /**
     * Return current array entry
     * @return Collectable
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return Collectable
     */
    public function read()
    {
        $object = $this->current();
        $this->next();
        return $object;
    }

    /**
     * Get the first array entry
     * if exists or null if not
     * @return Collectable|null
     */
    public function getOne()
    {
        if ($this->count() > 0)
        {
            $this->seek(0);
            return $this->current();
        } else
            return null;
    }

    /**
     * Contains one object with $name
     * @param  int $index
     * @return boolean
     */
    public function containsIndex($index)
    {
        return parent::offsetExists($index);
    }

    /**
     * Remove one object with $name
     * @param  int $index
     */
    public function remove($index)
    {
        if( $this->containsIndex($index) )
            $this->offsetUnset($index);
    }

    /**
     * Merge two Collections
     * @param BaseCollection $objectCollection
     * @return void
     */
    public function merge(BaseCollection $baseCollection)
    {
        $baseCollection->rewind();
        while( $baseCollection->valid() )
        {
            $object = $baseCollection->read();
            if( !$this->containsIndex( $object->getIndex() ) )
                $this->append($object);
        }
        $baseCollection->rewind();
    }

    /**
     * Diff two Collections
     * @param BaseCollection $objectCollection
     * @return void
     */
    public function diff(BaseCollection $baseCollection)
    {
        $baseCollection->rewind();
        while( $baseCollection->valid() )
        {
            $object = $baseCollection->read();
            if( $this->containsIndex( $object->getIndex() ) )
                $this->remove($object->getIndex());
        }
        $baseCollection->rewind();
    }

    /**
     * Intersect two Collections
     * @param CollectableCollection $objectCollection
     * @return CollectableCollection
     */
    public function intersect(BaseCollection $objectCollection)
    {
        $newobjectCollection = $this->makeColletion();
        $objectCollection->rewind();
        while($objectCollection->valid())
        {
            $object = $objectCollection->read();
            if( $this->containsIndex( $object->getIndex() ) )
                $newobjectCollection->append($object);
        }
        $objectCollection->rewind();
        return $newobjectCollection;
    }

    /**
     * Retrieve the array with primary keys
     * @return array
     */
    public function getPrimaryKeys()
    {
        return array_keys($this->getArrayCopy());
    }

    /**
     * Retrieve the Collectable with primary key
     * @param  int $name
     * @return Collectable
     */
    public function getByPK($index)
    {
        return $this->containsIndex($index) ? $this[$index] : null;
    }

    /**
     * Is Empty
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     *
     * @param \Closure $fn
     */
    public function each($fn)
    {
    	$this->rewind();
        while( $this->valid() )
        {
            $object = $this->read();
            $fn($object);
        }
        $this->rewind();
    }

    /**
     *
     * @param \Closure $fn
     * @return BaseCollection
     */
    public function filter($fn)
    {
    	$baseColletion = $this->makeColletion();
    	$this->rewind();
        while( $this->valid() )
        {
            $object = $this->read();
            if( $fn($object) ){
            	$baseColletion->append($object);
            }
        }
        $this->rewind();
        $baseColletion->rewind();
        return $baseColletion;
    }

}