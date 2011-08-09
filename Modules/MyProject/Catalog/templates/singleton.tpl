{% include 'header.tpl' %}

{% set Singleton = classes.get('Singleton') %}
{{ Singleton.printNamespace() }}

/**
 *
 *
 * @author chente
 *
 */
abstract class {{ Singleton }}
{

	/**
	 *
	 *
	 * @var array
	 */
    private static $instances = array();

    /**
     *
     *
     */
    final private function __construct(){}

    /**
     *
     *
     */
    final private function __clone(){}

    /**
     *
     *
     */
    final public static function getInstance(){
        $class = get_called_class();
        if( !isset(self::$instances[$class]) ){
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}