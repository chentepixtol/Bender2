{% include 'header.tpl' %}

{% set Collection = classes.get('Collection') %}
{% set collection = classes.get('Collection').getName().toCamelCase() %}
{% set Collectable = classes.get('Collectable') %}
{% set collectable = classes.get('Collectable').getName().toCamelCase() %}

{{ Collection.printNamespace() }}

{{ Collectable.printRequire() }}
{{ Collectable.printUse() }}


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
     * validate {{ collectable }}
     * @param {{ Collectable }} ${{ collectable }}
     */
    protected function validate(${{ collectable }})
    {
		if( !(${{ collectable }} instanceof {{ Collectable }}) ){
			throw new \InvalidArgumentException("Debe de cumplir con la Interface {{ Collectable }}");
		}
    }

    /**
     * Appends the value
     * @param {{ Collectable }} ${{ collectable }}
     */
    public function append(${{ collectable }})
    {
    	$this->validate(${{ collectable }});
        parent::offsetSet(${{ collectable }}->getIndex(), ${{ collectable }});
        $this->rewind();
    }

    /**
     * Return current array entry
     * @return {{ Collectable }}
     */
    public function current()
    {
        return parent::current();
    }

    /**
     * Return current array entry and
     * move to next entry
     * @return {{ Collectable }}
     */
    public function read()
    {
        ${{ collectable }} = $this->current();
        $this->next();
        return ${{ collectable }};
    }

    /**
     * Get the first array entry
     * if exists or null if not
     * @return {{ Collectable }}|null
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
     * Contains one {{ collectable }} with $name
     * @param  int $index
     * @return boolean
     */
    public function containsIndex($index)
    {
        return parent::offsetExists($index);
    }

    /**
     * Remove one {{ collectable }} with $name
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
            ${{ collectable }} = ${{ collection }}->read();
            if( !$this->containsIndex( ${{ collectable }}->getIndex() ) )
                $this->append(${{ collectable }});
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
            ${{ collectable }} = ${{ collection }}->read();
            if( $this->containsIndex( ${{ collectable }}->getIndex() ) )
                $this->remove(${{ collectable }}->getIndex());
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
       	$new{{ Collection }} = $this->makeCollection();
        ${{ collection }}->rewind();
        while( ${{ collection }}->valid() )
        {
            $object = ${{ collection }}->read();
            if( $this->containsIndex( $object->getIndex() ) ){
                $new{{ Collection }}->append($object);
            }
        }
        ${{ collection }}->rewind();
        $new{{ Collection }}->rewind();
        return $new{{ Collection }};
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
     * Retrieve the {{ Collectable }} with primary key
     * @param  int $name
     * @return {{ Collectable }}
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
     * @param \Closure $closure
     */
    public function each($closure)
    {
    	if( !is_callable($closure) ){
    		throw new \InvalidArgumentException("Is not a callable function");
    	}

    	$this->rewind();
        while( $this->valid() )
        {
            ${{ collectable }} = $this->read();
            $closure(${{ collectable }});
        }
        $this->rewind();
    }

    /**
     *
     * @param \Closure $closure
     * @return array
     */
    public function map($closure)
    {
    	if( !is_callable($closure) ){
    		throw new \InvalidArgumentException("Is not a callable function");
    	}

		$array = array();
    	$this->rewind();
        while( $this->valid() )
        {
            ${{ collectable }} = $this->read();
            $mapResult = $closure(${{ collectable }});
            if( is_array($mapResult) ){
            	foreach($mapResult as $key => $value){
            		$array[$key] = $value;
            	}
            }else{
            	$array[] = $mapResult;
            }
        }
        $this->rewind();

        return $array;
    }

    /**
     *
     * @param \Closure $closure
     * @return {{ Collection }}
     */
    public function filter($closure)
    {
    	${{ collection }} = $this->makeCollection();
    	$this->rewind();
        while( $this->valid() )
        {
            ${{ collectable }} = $this->read();
            if( $closure(${{ collectable }}) ){
            	${{ collection }}->append(${{ collectable }});
            }
        }
        $this->rewind();
        ${{ collection }}->rewind();
        return ${{ collection }};
    }

    /**
     * convert to array
     * @return array
     */
    public function toArray(){
    	return $this->map(function({{ Collectable }} ${{ collectable }}){
    		return array(${{ collectable }}->getIndex() => ${{ collectable }}->toArray());
    	});
    }

}