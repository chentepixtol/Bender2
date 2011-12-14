{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}

{{ Storage.printNamespace() }}

/**
 *
 * {{ Storage }}
 * @author chente
 *
 */
interface {{ Storage }}
{

    /**
     * Save
     * @param string $key
     * @param mixed $object
     */
    public function save($key, $object);
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load($key);
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists($key);

}