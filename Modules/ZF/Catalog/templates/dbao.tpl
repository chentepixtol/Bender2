{% include 'header.tpl' %}
{% set DBAO = classes.get('DBAO') %}
{{ DBAO.printNamespace() }}

{%if isZF2 %}
use Zend\Db\Db as ZendDb;
{% else %}
use Zend_Db as ZendDb;
{% endif %}


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
{%if isZF2 %}
     * @var \Zend\Db\Adapter\AbstractAdapter
{% else %}
     * @var \Zend_Db_Adapter_Abstract
{% endif %}
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
{%if isZF2 %}
     * @return \Zend\Db\Adapter\AbstractAdapter
{% else %}
     * @return \Zend_Db_Adapter_Abstract
{% endif %}
     */
    public static function getDbAdapter()
    {
        if( self::$config === null ){
            throw new \Exception("No se ha configurado el parametro estatico de la base de datos");
        }

        if ( !isset(self::$dbAdapter) ){
            self::$dbAdapter = ZendDb::factory(self::$config);
        }

        return self::$dbAdapter;
    }
    
}