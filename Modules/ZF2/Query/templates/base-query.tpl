{% include 'header.tpl' %}

{% set BaseQuery = classes.get('BaseQuery') %}
{% set FactoryStorage = classes.get('FactoryStorage') %}

{{ BaseQuery.printNamespace() }}

use Query\Query;

/**
 *
 *
 * @author chente
 * @abstract
 */
abstract class {{ BaseQuery }} extends Query
{

    /**
     *
     */
    protected $storage = null;

    /**
     * @abstract
     * @return {{ classes.get('Catalog') }}
     */
    abstract protected function getCatalog();

    /**
     *
     * @return {{ classes.get('Collection') }}
     */
    public function execute(){
        return $this->getCatalog()->getByQuery($this, $this->storage);
    }
    
    /**
     * @return {{ BaseQuery }}
     */
    public function useMemoryCache(){
       $this->storage = \{{ FactoryStorage.getFullName() }}::create('memory');
       return $this;
    }

    /**
     *
     * @return {{ classes.get('Bean') }}
     */
    public function executeOne()
    {
        $limit = $this->getLimit();
        $this->setLimit(1);
        ${{ classes.get('Bean').getName().toCamelCase() }} = $this->getCatalog()->getOneByQuery($this, $this->storage);
        $this->setLimit($limit);
        return ${{ classes.get('Bean').getName().toCamelCase() }};
    }

    /**
     *
     * Filter the request
     * @return {{ BaseQuery }}
     */
    public function filter($params){
        $this->whereCriteria->filter($params);
        return $this;
    }

    /**
     *
     * @return array
     */
    public function fetchCol(){
        return $this->getCatalog()->fetchCol($this, $this->storage);
    }

    /**
     *
     * @return array
     */
    public function fetchAll(){
        return $this->getCatalog()->fetchAll($this, $this->storage);
    }

    /**
     *
     * @return mixed
     */
    public function fetchOne(){
        return $this->getCatalog()->fetchOne($this, $this->storage);
    }

    /**
     *
     * @return array
     */
    public function fetchPairs(){
        return $this->getCatalog()->fetchPairs($this, $this->storage);
    }
    
}