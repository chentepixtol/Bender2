{% include 'header.tpl' %}
{% set DBAO = classes.get('DBAO') %}
{{ DBAO.printNamespace() }}

use Zend\Db;

/**
 * Clase que representa la abstraccion de nuestro objeto Zend_Db
 *
 */
abstract class DBAO
{

    /**
     * @var mixed
     */
    public static $config = null;

    /**
     * @var Zend_Db_Adapter_Abstract
     */
    protected static $instance  = null;

    /**
     * Metodo para obtener la Connection
     * @return Zend_Db_Adapter_Abstract
     */
    public static function getDbAdapter()
    {
    	if( DBAO::$config === null ){
            throw new \Exception("No se ha configurado el parametro estatico de la base de datos");
        }

        if ( !isset(self::$instance) ){
            self::$instance = Db::factory(DBAO::$config);
        }

        return self::$instance;
    }
    
}