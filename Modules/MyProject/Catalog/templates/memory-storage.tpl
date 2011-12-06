{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set Singleton = classes.get('Singleton') %}
{% set MemoryStorage = classes.get('MemoryStorage') %}

{{ MemoryStorage.printNamespace() }}

{{ Singleton.printUse() }}

/**
 *
 * {{ MemoryStorage }}
 * @author chente
 *
 */
class {{ MemoryStorage }} extends {{ Singleton }} implements {{ Storage }}
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
    public function save($key, $object){
        self::$cache[$key] = $object;
    }
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load($key){
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
    public function exists($key){
        return array_key_exists($key, self::$cache);
    }
    
}