{% include 'header.tpl' %}
{% set Singleton = classes.get('Singleton') %}
{% set DBAO = classes.get('DBAO') %}
{% set TransactionalCatalog = classes.get('TransactionalCatalog') %}

{{ TransactionalCatalog.printNamespace() }}

{{ Singleton.printRequire() }}
{{ DBAO.printRequire() }}

{{ Singleton.printUse() }}

/**
 * Clase que representa un catalogo general
 *
 */
class {{ TransactionalCatalog }} extends {{ Singleton }}
{

    /**
     * Engines
     * @var array
     */
    protected static $engines = array("pgsql", "mysql");

    /**
     * The current transaction level
     */
    protected static $transLevel = 0;

    /**
     *
     * @return Zend_Db_Adapter_Abstract
     */
    public function getDb()
    {
        return \{{ DBAO.getFullName() }}::getDbAdapter();
    }

    /**
     * Soporta transacciones nested
     * @return array
     */
    protected function isNestable()
    {
        $engineName = $this->getDb()->getConnection()->getAttribute(\PDO::ATTR_DRIVER_NAME); 
        return in_array($engineName, self::$engines);
    }

    /**
     * beginTransaction
     */
    public function beginTransaction()
    {
        if(!$this->isNestable() || self::$transLevel == 0) {
            $this->getDb()->beginTransaction();
        } else {
            $this->getDb()->exec("SAVEPOINT LEVEL".self::$transLevel);
        }
        self::$transLevel++;
    }

    /**
     * commit
     */
    public function commit()
    {
        self::$transLevel--;

        if(!$this->isNestable() || self::$transLevel == 0) {
            $this->getDb()->commit();
        } else {
            $this->getDb()->exec("RELEASE SAVEPOINT LEVEL".self::$transLevel);
        }
    }

    /**
     * rollBack
     */
    public function rollBack()
    {
        self::$transLevel--;

        if(!$this->isNestable() || self::$transLevel == 0)
        {
            $this->getDb()->rollBack();
        } else {
            $this->getDb()->exec("ROLLBACK TO SAVEPOINT LEVEL".self::$transLevel);
        }
    }
    
}
