{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set FileStorage = classes.get('FileStorage') %}

{{ FileStorage.printNamespace() }}

use Zend\Cache\StorageFactory as ZendStorageFactory;

/**
 *
 * {{ FileStorage }}
 * @author chente
 *
 */
class {{ FileStorage }} implements {{ Storage }}
{

    /**
     *
     * @var Zend\Cache\Frontend
     */
    private $zendCache = null;
    
    /**
     * @var array
     */
    private $options = array(
        'ttl' => 86400,
        'cache_dir' => '../cache/',
    );
    
    /**
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options); 
        
        $this->zendCache = ZendStorageFactory::factory(array(
            'adapter' => array(
                'name' => 'filesystem',
                'options' => $this->options,
            ),
            'plugins' => array('serializer'),
        ));
    }

    /**
     * Save
     * @param string $key
     * @param mixed $object
     */
    public function save($key, $object){
        $this->zendCache->save($object, sha1($key));
    }
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load($key){
        return $this->zendCache->load(sha1($key));
    }
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists($key){
        return $this->zendCache->load(sha1($key)) !== false;
    }
    
}