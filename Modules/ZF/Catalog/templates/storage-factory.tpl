{% include 'header.tpl' %}
{% set Storage = classes.get('Storage') %}
{% set FactoryStorage = classes.get('FactoryStorage') %}
{% set NullStorage = classes.get('NullStorage') %}
{% set MemoryStorage = classes.get('MemoryStorage') %}
{% set FileStorage = classes.get('FileStorage') %}

{{ FactoryStorage.printNamespace() }}

/**
 *
 * {{ FactoryStorage }}
 * @author chente
 *
 */
class {{ FactoryStorage }}
{

    /**
     * @staticvar array
     */
    private static $storages = array();

    /**
     * @param string|{{ Storage }}
     * @return {{ Storage }}
     */
    public static function create($name)
    {
        if( $name instanceOf {{ Storage }} ){
            return $name;
        }
        
        if( null === $name || (is_string($name) && 'null' == $name) ){
            return self::lazyLoad('null', function(){
                return new {{ NullStorage }}();
            });
        }      
        
        if( is_string($name) && 'memory' == $name ){
            return self::lazyLoad('memory', function(){
                return new {{ MemoryStorage }}();
            });
        }
        
        if( is_string($name) && 'file' == $name ){
            return self::lazyLoad('file', function(){
                return new {{ FileStorage }}();
            });
        }
        
        throw new \Exception("No existe el storage especificado ".$name);
    }
    
    /**
     * @param string $name
     * @param Closure $createStorageFn
     * @return {{ Storage }}
     */
    private static function lazyLoad($name, $createStorageFn)
    {
        if( !isset(self::$storages[$name]) ){
            self::$storages[$name] = $createStorageFn();
        }
        
        return self::$storages[$name]; 
    } 
    
}