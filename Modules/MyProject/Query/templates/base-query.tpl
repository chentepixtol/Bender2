{% include 'header.tpl' %}

{% set BaseQuery = classes.get('BaseQuery') %}

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
	 * @abstract
	 * @return {{ classes.get('Catalog') }}
	 */
	abstract protected function getCatalog();

    /**
     *
     * @return {{ classes.get('Collection') }}
     */
	public function execute(){
		return $this->getCatalog()->getByQuery($this);
	}

	/**
     *
     * @return {{ classes.get('Bean') }}
     */
	public function executeOne()
	{
	    $limit = $this->getLimit();
	    $this->setLimit(1);
		${{ classes.get('Bean').getName().toCamelCase() }} = $this->getCatalog()->getOneByQuery($this);
		$this->setLimit($limit);
		return ${{ classes.get('Bean').getName().toCamelCase() }};
	}

	/**
	 *
	 * @return array
	 */
	public function fetchCol(){
		return $this->getCatalog()->fetchCol($this);
	}

	/**
	 *
	 * @return array
	 */
	public function fetchAll(){
		return $this->getCatalog()->fetchAll($this);
	}

	/**
	 *
	 * @return mixed
	 */
	public function fetchOne(){
		return $this->getCatalog()->fetchOne($this);
	}

	/**
	 *
	 * @return array
	 */
	public function fetchPairs(){
		return $this->getCatalog()->fetchPairs($this);
	}
}