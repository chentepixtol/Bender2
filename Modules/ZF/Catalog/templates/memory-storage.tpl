{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set MemoryStorage = classes.get('MemoryStorage') %}
{{ MemoryStorage.printNamespace() }}

{% include "header_class.tpl" with {'infoClass': MemoryStorage} %}
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