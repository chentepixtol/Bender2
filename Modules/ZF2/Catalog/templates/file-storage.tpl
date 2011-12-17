{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set FileStorage = classes.get('FileStorage') %}

{{ FileStorage.printNamespace() }}

use Zend\Cache\Cache;

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
    private $defaultFrontendOptions = array(
        'lifetime' => 86400,
        'automatic_serialization' => true,
    );
    
    /**
     * @var array
     */
    private $defaultBackendOptions = array(
        'cache_dir' => '../cache/',
    );
    
    /**
     * @param array $frontendOptions
     * @param array $backendOptions
     */
    public function __construct($frontendOptions = array(), $backendOptions = array())
    {
        $frontendOptions = array_merge($this->defaultFrontendOptions, $frontendOptions); 
        $backendOptions = array_merge($this->defaultBackendOptions, $backendOptions);
        $this->zendCache = Cache::factory('Core', 'File', $frontendOptions, $backendOptions);
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