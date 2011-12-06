{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set NullStorage = classes.get('NullStorage') %}

{{ NullStorage.printNamespace() }}

/**
 *
 * {{ NullStorage }}
 * @author chente
 *
 */
class {{ NullStorage }} implements {{ Storage }}
{

    /**
     * Save
     * @param string $key
     * @param mixed $object
     */
    public function save($key, $object){}
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load($key){ 
        return null;
    }
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists($key){
        return false;
    }

}