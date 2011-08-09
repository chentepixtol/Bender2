{% include 'header.tpl' %}

{% set AbstractCatalog = classes.get('AbstractCatalog') %}
{% set Catalog = classes.get('Catalog') %}
{% set Singleton = classes.get('Singleton') %}
{% set DBAO = classes.get('DBAO') %}

{{ AbstractCatalog.printNamespace() }}

{{ Singleton.printRequire() }}
{{ Catalog.printRequire() }}
{{ DBAO.printRequire() }}

{{ Singleton.printUse() }}
{{ Catalog.printUse() }}

/**
 * Clase que representa un catalogo general
 *
 */
abstract class {{ AbstractCatalog }} extends {{ Singleton }} implements {{ Catalog }}
{

    /**
     * Engines
     * @var array
     */
    protected static $savepointTransactions = array("pgsql", "mysql");

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
    	return DBAO::getDbAdapter();
    }

    /**
     * Soporta transacciones nested
     * @return array
     */
    protected function isNestable()
    {
        return in_array($this->getDb()->getConnection()->getAttribute(PDO::ATTR_DRIVER_NAME),
                        self::$savepointTransactions);
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
     * @var string $field
     * @return boolean
     */
    public function isNotNull($field){
    	return !is_null($field);
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
