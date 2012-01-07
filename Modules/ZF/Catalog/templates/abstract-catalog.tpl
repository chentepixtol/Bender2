{% include 'header.tpl' %}
{% set AbstractCatalog = classes.get('AbstractCatalog') %}
{% set Catalog = classes.get('Catalog') %}
{% set Singleton = classes.get('Singleton') %}
{% set BaseCollection = classes.get('Collection') %}
{% set FactoryStorage = classes.get('FactoryStorage') %}
{% set Storage = classes.get('Storage') %}
{% set Bean = classes.get('Bean') %}
{% set TransactionalCatalog = classes.get('TransactionalCatalog') %}
{% set bean = Bean.getName().toCamelCase() %}

{{ AbstractCatalog.printNamespace() }}

{{ Catalog.printRequire() }}
{{ FactoryStorage.printRequire() }}

{% if AbstractCatalog.getNamespace() != Catalog.getNamespace() %}{{ Catalog.printUse() }}{% endif %}
{{ Bean.printUse() }}
{{ Storage.printUse() }}
{{ FactoryStorage.printUse() }}
use Query\Query;

/**
 * Clase que representa un catalogo general
 *
 */
abstract class {{ AbstractCatalog }} extends {{ TransactionalCatalog }} implements {{ Catalog }}
{

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
    abstract protected function throwException($message, \Exception $exception = null);

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
    abstract protected function makeBean($resultset);

    /**
     * @var string $field
     * @return boolean
     */
    public function isNotNull($field){
        return !is_null($field);
    }

    /**
     *
     * @param Query $query
     * @return {{ BaseCollection }}
     */
    public function getByQuery(Query $query, {{ Storage }} $storage = null)
    {
        $storage = {{ FactoryStorage }}::create($storage);
        
        $key = "getByQuery:". $query->createSql();
        if( $storage->exists($key) ){
            $collection = $storage->load($key);
        }else{
            $collection = $this->makeCollection();
            foreach( $this->fetchAll($query, $storage) as $row ){
                $collection->append($this->makeBean($row));
            }
            $storage->save($key, $collection);
        }
        return $collection;
    }

    /**
     *
     * @param  Query $query
     * @return {{ Bean }}
     */
    public function getOneByQuery(Query $query, {{ Storage }} $storage = null, $throwIfNotExists = false)
    {
        $storage = {{ FactoryStorage }}::create($storage);
        
        $key = "getOneByQuery:". $query->createSql();
        if( $storage->exists($key) ){
            ${{ bean }} = $storage->load($key);
        }else{
            ${{ bean }} = $this->getByQuery($query, $storage)->getOne();
            $storage->save($key, ${{ bean }});
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
    public function fetchAll(Query $query, {{ Storage }} $storage = null){
        return $this->executeDbMethod($query, 'fetchAll', $storage);
    }

    /**
     *
     * @return array
     */
    public function fetchCol(Query $query, {{ Storage }} $storage = null){
        return $this->executeDbMethod($query, 'fetchCol', $storage);
    }

    /**
     *
     * @return mixed
     */
    public function fetchOne(Query $query, {{ Storage }} $storage = null){
        return $this->executeDbMethod($query, 'fetchOne', $storage);
    }

    /**
     *
     * @return mixed
     */
    public function fetchPairs(Query $query, {{ Storage }} $storage = null){
        return $this->executeDbMethod($query, 'fetchPairs', $storage); 
    }
    
    /**
     * 
     * @param Query $query
     * @param string $method
     * @throws Exception
     */
    protected function executeDbMethod(Query $query, $method, {{ Storage }} $storage = null)
    {
        $this->validateQuery($query);
        if( !method_exists($this->getDb(), $method) ){
            $this->throwException("El metodo {$method} no existe");
        }
        
        $storage = {{ FactoryStorage }}::create($storage);
        try
        {
            $sql = $query->createSql();
            if( $storage->exists($sql) ){
                $resultset = $storage->load($sql);
            }else{   
                $resultset = call_user_func_array(array($this->getDb(), $method), array($sql));
                $storage->save($sql, $resultset);
            }
        }catch(\Exception $e){
            $this->throwException("Cant execute query \n", $e);
        }
        
        return $resultset;
    }

}
