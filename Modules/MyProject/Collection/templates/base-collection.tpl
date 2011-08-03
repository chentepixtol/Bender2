{% include 'header.tpl' %}

{% set Collection = classes.get('Collection') %}
{% set collection = classes.get('Collection').getName().toCamelCase() %}

{{ Collection.printNamespace() }}

/**
 *
 *
 * @author chente
 *
 */
abstract class {{ Collection }} extends \ArrayIterator
{

    /**
     *
     *
     * @return {{ Collection }}
     */
    protected function makeCollection(){
    	return new static();
    }

    /**
     *
     * validate object
     * @param {{ classes.get('Collectable') }} $object
     */
    protected function validate($object)
    {
		if( !($object instanceof {{ classes.get('Collectable') }}) ){
			throw new \Exception("Debe de cumplir con la Interface {{ classes.get('Collectable') }}");
		}
    }

    /**
     * Appends the value
     * @param {{ classes.get('Collectable') }} $object
     */
    public function append($object)
    {
    	$this->validate($object);
        parent::offsetSet($object->getIndex(), $object);
        $this->rewind();
    }

    /**
     * Return current array entry
     * @return {{ classes.get('Collectable') }}
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return {{ classes.get('Collectable') }}
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
     * @return {{ classes.get('Collectable') }}|null
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
     * @param {{ Collection }} ${{ collection }}
     * @return void
     */
    public function merge({{ Collection }} ${{ collection }})
    {
        ${{ collection }}->rewind();
        while( ${{ collection }}->valid() )
        {
            $object = ${{ collection }}->read();
            if( !$this->containsIndex( $object->getIndex() ) )
                $this->append($object);
        }
        ${{ collection }}->rewind();
    }

    /**
     * Diff two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return void
     */
    public function diff({{ Collection }} ${{ collection }})
    {
        ${{ collection }}->rewind();
        while( ${{ collection }}->valid() )
        {
            $object = ${{ collection }}->read();
            if( $this->containsIndex( $object->getIndex() ) )
                $this->remove($object->getIndex());
        }
        ${{ collection }}->rewind();
    }

    /**
     * Intersect two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return {{ Collection }}
     */
    public function intersect({{ Collection }} ${{ collection }})
    {
        ${{ collection }} = $this->makeCollection();
        ${{ collection }}->rewind();
        while(${{ collection }}->valid())
        {
            $object = ${{ collection }}->read();
            if( $this->containsIndex( $object->getIndex() ) )
                ${{ collection }}->append($object);
        }
        ${{ collection }}->rewind();
        return ${{ collection }};
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
     * Retrieve the {{ classes.get('Collectable') }} with primary key
     * @param  int $name
     * @return {{ classes.get('Collectable') }}
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
     * @return {{ Collection }}
     */
    public function filter($fn)
    {
    	${{ collection }} = $this->makeCollection();
    	$this->rewind();
        while( $this->valid() )
        {
            $object = $this->read();
            if( $fn($object) ){
            	${{ collection }}->append($object);
            }
        }
        $this->rewind();
        ${{ collection }}->rewind();
        return ${{ collection }};
    }

}