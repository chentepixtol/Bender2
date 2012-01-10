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
     *
     * validate Callback
     * @param callable $callable
     * @throws \InvalidArgumentException
     */
    protected function validateCallback($callable)
    {
        if( !is_callable($callable) ){
            throw new \InvalidArgumentException("Is not a callable function");
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
     * @return {{ Collection }}
     */
    public function merge({{ Collection }} ${{ collection }})
    {
        $newCollection = $this->makeCollection();
        $appendFunction = function(Collectable $collectable) use($newCollection){
            if( !$newCollection->containsIndex( $collectable->getIndex() ) ){
                $newCollection->append($collectable);
            }
        };
        $this->each($appendFunction);
        $collection->each($appendFunction);
        
        return $new{{ Collection }};
    }

    /**
     * Diff two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return {{ Collection }}
     */
    public function diff({{ Collection }} ${{ collection }})
    {
        $newCollection = $this->makeCollection();
        $this->each(function(Collectable $collectable) use($newCollection, $collection){
            if( !$collection->containsIndex($collectable->getIndex()) ){
                $newCollection->append($collectable);
            }
        });
        return $newCollection;
    }

    /**
     * Intersect two Collections
     * @param {{ Collection }} ${{ collection }}
     * @return {{ Collection }}
     */
    public function intersect({{ Collection }} ${{ collection }})
    {
        $newCollection = $this->makeCollection();
        $this->each(function(Collectable $collectable) use($newCollection, $collection){
            if( $collection->containsIndex($collectable->getIndex()) ){
                $newCollection->append($collectable);
            }
        });
        return $newCollection;
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
     * @param \Closure $callable
     */
    public function each($callable)
    {
        $this->validateCallback($callable);

        $this->rewind();
        while( $this->valid() )
        {
            ${{ collectable }} = $this->read();
            $callable(${{ collectable }});
        }
        $this->rewind();
    }

    /**
     *
     * @param \Closure $callable
     * @return array
     */
    public function map($callable)
    {
        $this->validateCallback($callable);

        $array = array();
        $this->each(function(Collectable $collectable) use(&$array, $callable){
            $mapResult = $callable($collectable);
            if( is_array($mapResult) ){
                foreach($mapResult as $key => $value){
                    $array[$key] = $value;
                }
            }else{
                $array[] = $mapResult;
            }
        });

        return $array;
    }

    /**
     *
     * @param \Closure $callable
     * @return {{ Collection }}
     */
    public function filter($callable)
    {
        $this->validateCallback($callable);
        
        $newCollection = $this->makeCollection();
        $this->each(function(Collectable $collectable) use($newCollection, $callable){
            if( $callable($collectable) ){
                $newCollection->append($collectable);
            }
        });

        return $newCollection;
    }
    
    /**
     * @param mixed $start
     * @param callable $callable
     */
    public function foldLeft($start, $callable)
    {
        $this->validateCallback($callable);
        $result = $start;
        $this->each(function(Collectable $collectable) use(&$result, $callable){
            $result = $callable($result, $collectable);
        });
        return $result;
    }
    
    /**
     *
     * @param callable $callable
     * @return boolean
     */
    public function forall($callable)
    {
        if( $this->isEmpty() ) return false;
        $this->validateCallback($callable);
        return $this->foldLeft(true, function($boolean, Collectable $collectable) use($callable){
            return $boolean && $callable($collectable);
        });
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