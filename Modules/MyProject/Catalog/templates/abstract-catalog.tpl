{% include 'header.tpl' %}

{% set AbstractCatalog = classes.get('AbstractCatalog') %}
{% set Catalog = classes.get('Catalog') %}
{% set Singleton = classes.get('Singleton') %}
{% set DBAO = classes.get('DBAO') %}
{% set BaseCollection = classes.get('Collection') %}
{% set Storage = classes.get('Storage') %}
{% set NullStorage = classes.get('NullStorage') %}
{% set Bean = classes.get('Bean') %}
{% set bean = Bean.getName().toCamelCase() %}
{% set storage = Storage.getName().toCamelCase() %}

{{ AbstractCatalog.printNamespace() }}

{{ Singleton.printRequire() }}
{{ Catalog.printRequire() }}
{{ DBAO.printRequire() }}
{{ Storage.printRequire() }}
{{ NullStorage.printRequire() }}

{{ Singleton.printUse() }}
{{ Catalog.printUse() }}
{{ Storage.printUse() }}

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
     * @var {{ Storage }}
     */
    protected static $defaultStorage = null;

    /**
     *
     * Validate Query
     * @param Query $query
     * @throws Exception
     */
    abstract protected function validateQuery(Query $query);
    
    /**
     *
     * Validate {{ Bean }}
     * @param {{ Bean }} ${{ bean }}
     * @throws Exception
     */
    abstract protected function validateBean({{ Bean }} ${{ bean }} = null);

    /**
     *
     * throwException
     * @throws Exception
     */
    abstract protected function throwException($message, Exception $exception = null);

    /**
     *
     * makeCollection
     * @return BaseCollection
     */
    abstract protected function makeCollection();

    /**
     *
     * makeBean
     * @return Bean
     */
    abstract protected function makeBean($rs);


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

    /**
     *
     * @param Query $query
     * @return {{ BaseCollection }}
     */
    public function getByQuery(Query $query, {{ Storage }} ${{ storage }} = null)
    {
        ${{ storage }} = $this->getStorage(${{ storage }});
        
        $key = "getByQuery:". $query->createSql();
        if( ${{ storage }}->exists($key) ){
            $collection = ${{ storage }}->load($key);
        }else{
            $collection = $this->makeCollection();
            foreach( $this->fetchAll($query, ${{ storage }}) as $row ){
                $collection->append($this->makeBean($row));
            }
            ${{ storage }}->save($key, $collection);
        }
        return $collection;
    }

    /**
     *
     * @param  Query $query
     * @return {{ Bean }}
     */
    public function getOneByQuery(Query $query, {{ Storage }} ${{ storage }} = null, $throwIfNotExists = false)
    {
        ${{ storage }} = $this->getStorage(${{ storage }});
        
        $key = "getOneByQuery:". $query->createSql();
        if( ${{ storage }}->exists($key) ){
            ${{ bean }} = ${{ storage }}->load($key);
        }else{
            ${{ bean }} = $this->getByQuery($query, ${{ storage }})->getOne();
            ${{ storage }}->save($key, ${{ bean }});
        }   
        if( $throwIfNotExists ){
            $this->validateBean(${{ bean }});
        }
    	return ${{ bean }};
    }

    /**
     *
     * @return array
     */
    public function fetchAll(Query $query, {{ Storage }} ${{ storage }} = null){
    	return $this->executeDbMethod($query, 'fetchAll', ${{ storage }});
    }

    /**
     *
     * @return array
     */
    public function fetchCol(Query $query, {{ Storage }} ${{ storage }} = null){
        return $this->executeDbMethod($query, 'fetchCol', ${{ storage }});
    }

    /**
     *
     * @return mixed
     */
    public function fetchOne(Query $query, {{ Storage }} ${{ storage }} = null){
        return $this->executeDbMethod($query, 'fetchOne', ${{ storage }});
    }

    /**
     *
     * @return mixed
     */
    public function fetchPairs(Query $query, {{ Storage }} ${{ storage }} = null){
        return $this->executeDbMethod($query, 'fetchPairs', ${{ storage }}); 
    }
    
    /**
     * 
     * @param Query $query
     * @param string $method
     * @throws Exception
     */
    protected function executeDbMethod(Query $query, $method, {{ Storage }} ${{ storage }} = null)
    {
        $this->validateQuery($query);
        if( !method_exists($this->getDb(), $method) ){
            $this->throwException("El metodo {$method} no existe");
        }
        
        ${{ storage }} = $this->getStorage(${{ storage }});
        
        try
        {
            $sql = $query->createSql();
            if( ${{ storage }}->exists($sql) ){
                $rs = ${{ storage }}->load($sql);
            }else{   
                $rs = call_user_func_array(array($this->getDb(), $method), array($sql));
                ${{ storage }}->save($sql, $rs);
            }
        }catch(\Exception $e){
            $this->throwException("Cant execute query \n", $e);
        }
        
        return $rs;
    }
    
    /**
     * getStorage
     * @param {{ Storage }}
     * @return {{ Storage }}
     */
    protected function getStorage({{ Storage }} ${{ storage }} = null){
        if( null == ${{ storage }} ){
            ${{ storage }} = $this->getDefaultStorage();
        }
        return ${{ storage }};
    }
    
    /**
     *
     * @return {{ Storage }}
     */
    protected function getDefaultStorage(){
        if( null == self::$defaultStorage ){
            self::$defaultStorage = new {{ NullStorage.getFullName() }}();   
        }
        return self::$defaultStorage;
    }

}
