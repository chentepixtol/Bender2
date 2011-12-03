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
    public function save(string $key, $object){}
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load(string $key){ 
        return null;
    }
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists(string $key){
        return false;
    }

}