{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set MemoryStorage = classes.get('MemoryStorage') %}

{{ MemoryStorage.printNamespace() }}

/**
 *
 * {{ MemoryStorage }}
 * @author chente
 *
 */
class {{ MemoryStorage }} implements {{ Storage }}
{

    /**
     *
     * @staticvar array $cache
     */
    private static $cache = array();

    /**
     * Save
     * @param string $key
     * @param mixed $object
     */
    public function save(string $key, $object){
        self::$cache[$key] = $object;
    }
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load(string $key){
        if( !$this->exists($key) ){
            return null;
        }
        return self::$cache[$key];
    }
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists(string $key){
        return array_key_exists($key, self::$cache);
    }
    
}