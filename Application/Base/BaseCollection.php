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
     * @param mixed $object
     * @return int|string
     */
    abstract protected function getIndex($object);

    /**
     *
     *
     * @return BaseCollection
     */
    protected function makeColletion(){
    	return new static();
    }

    /**
     * Appends the value
     * @param Object $object
     */
    public function append($object)
    {
        parent::offsetSet($this->getIndex($object), $object);
        $this->rewind();
    }

    /**
     * Return current array entry
     * @return Object
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return Object
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
     * @return Object|null
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
            if( !$this->containsIndex( $this->getIndex($object) ) )
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
            if( $this->containsIndex( $this->getIndex($object) ) )
                $this->remove($this->getIndex($object));
        }
        $baseCollection->rewind();
    }

    /**
     * Intersect two Collections
     * @param ObjectCollection $objectCollection
     * @return ObjectCollection
     */
    public function intersect(BaseCollection $objectCollection)
    {
        $newobjectCollection = $this->makeColletion();
        $objectCollection->rewind();
        while($objectCollection->valid())
        {
            $object = $objectCollection->read();
            if( $this->containsIndex( $this->getIndex($object) ) )
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
     * Retrieve the Object with primary key
     * @param  int $name
     * @return Object
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

}