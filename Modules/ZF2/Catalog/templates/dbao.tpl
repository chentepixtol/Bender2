{% include 'header.tpl' %}
{% set DBAO = classes.get('DBAO') %}
{{ DBAO.printNamespace() }}

use Zend\Db\Db;

/**
 * Clase que representa la abstraccion de nuestro objeto Zend_Db
 *
 */
class DBAO
{

    /**
     * @var mixed
     */
    public static $config = null;

    /**
     * @var Zend\Db\Adapter\AbstractAdapter
     */
    protected static $dbAdapter  = null;
    
    /**
     * @param Config
     */
    public function __construct($dbConfig){
        self::$config = $dbConfig;
    }

    /**
     * Metodo para obtener la Connection
     * @return Zend\Db\Adapter\AbstractAdapter
     */
    public static function getDbAdapter()
    {
    	if( self::$config === null ){
            throw new \Exception("No se ha configurado el parametro estatico de la base de datos");
        }

        if ( !isset(self::$dbAdapter) ){
            self::$dbAdapter = Db::factory(self::$config);
        }

        return self::$dbAdapter;
    }
    
}