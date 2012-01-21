{% include 'header.tpl' %}

{% set BaseQuery = classes.get('BaseQuery') %}
{% set Storage = classes.get('Storage') %}
{% set FactoryStorage = classes.get('FactoryStorage') %}
{% set Option = classes.get('Option') %}

{{ BaseQuery.printNamespace() }}

use Query\Query;
{{ Storage.printUse() }}
{{ Option.printUse() }}

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
     * @abstract
     * @return {{ BaseQuery }}
     */
    abstract public function primaryKey($value, $comparison = \Query\Criterion::AUTO, $mutatorColumn = null, $mutatorValue = null);

    /**
     * @return {{ BaseQuery }}
     */
    public function useMemoryCache(){
       $this->setStorage(\{{ FactoryStorage.getFullName() }}::create('memory'));
       return $this;
    }
    
    /**
     * @return {{ BaseQuery }}
     */
    public function useFileCache(){
       $this->setStorage(\{{ FactoryStorage.getFullName() }}::create('file'));
       return $this;
    }
    
    /**
     *
     * @return {{ classes.get('Collection') }}
     */
    public function find(){
        return $this->getCatalog()->getByQuery($this, $this->storage);
    }

    /**
     *
     * @return {{ classes.get('Bean') }}
     */
    public function findOne()
    {
        ${{ classes.get('Bean').getName().toCamelCase() }} = $this->getCatalog()->getOneByQuery($this, $this->getStorage());
        return ${{ classes.get('Bean').getName().toCamelCase() }};
    }
    
    /**
     * @return \{{ Option.getFullName() }}
     */
    public function findOneOption(){
        return new {{ Option }}($this->findOne());
    }
    
    /**
     * @param mixed $alternative
     * @return {{ classes.get('Bean') }}
     */
    public function findOneOrElse($alternative){
        return $this->findOneOption()->getOrElse($alternative);
    }
    
    /**
     * @param mixed $message
     * @return {{ classes.get('Bean') }}
     * @throws \InvalidArgumentException
     */
    public function findOneOrThrow($message){
        return $this->findOneOption()->getOrThrow($message);
    }

    /**
     *
     * @return array
     */
    public function fetchCol(){
        return $this->getCatalog()->fetchCol($this, $this->getStorage());
    }

    /**
     *
     * @return array
     */
    public function fetchAll(){
        return $this->getCatalog()->fetchAll($this, $this->getStorage());
    }

    /**
     *
     * @return mixed
     */
    public function fetchOne(){
        return $this->getCatalog()->fetchOne($this, $this->getStorage());
    }

    /**
     *
     * @return array
     */
    public function fetchPairs(){
        return $this->getCatalog()->fetchPairs($this, $this->getStorage());
    }
    
    /**
     * @param Storage $storage
     * @return {{ BaseQuery }}
     */
    public function setStorage(Storage $storage){
        $this->storage = $storage;
        return $this;
    }
    
    /**
     * @return Storage
     */
    public function getStorage(){
        return $this->storage;
    }
    
}