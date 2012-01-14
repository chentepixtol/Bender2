{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set ChainStorage = classes.get('ChainStorage') %}
{{ ChainStorage.printNamespace() }}

use Zend\Cache\Cache;

{% include "header_class.tpl" with {'infoClass': ChainStorage} %}
class {{ ChainStorage }} implements {{ Storage }}
{
  
    /**
     * @var Storage
     */
    private $primaryStorage;
    
    /**
     * @var Storage
     */
    private $secondaryStorage;

    /**
     * @param Storage $primaryStorage
     * @param Storage $secodaryStorage
     */
    public function __construct(Storage $primaryStorage, Storage $secondaryStorage)
    {
        $this->primaryStorage = $primaryStorage;
        $this->secondaryStorage = $secondaryStorage;
    }

    /**
     * Save
     * @param string $key
     * @param mixed $object
     */
    public function save($key, $object){
        $this->primaryStorage->save($key, $object);
        $this->secondaryStorage->save($key, $object);
    }
    
    /**
     * Load
     * @param string $key
     * @return mixed
     */
    public function load($key)
    {
        if( !$this->exists($key) ){
            return null;
        }
        
        if( $this->primaryStorage->exists($key) ){
            return $this->primaryStorage->load($key);
        }
        
        if( $this->secondaryStorage->exists($key) ){
            $object = $this->secondaryStorage->load($key);
            $this->primaryStorage->save($key, $object);
            return $object;
        }
    }
    
    /**
     * Exists
     * @param string
     * @return boolean
     */
    public function exists($key){
        return $this->primaryStorage->exists($key) ||
        $this->secondaryStorage->exists($key);
    }
    
}