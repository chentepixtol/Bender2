{% include 'header.tpl' %}
{% set BaseQuery = classes.get('BaseQuery') %}

{{ Query.printNamespace() }}

{{ Catalog.printRequire() }}
{{ Catalog.printUse() }}

{% if parent %}
	{% set parentQuery = classes.get(parent.getObject()~'Query') %}
{% else %}
{{ BaseQuery.printRequire() }}
{{ BaseQuery.printUse() }}
{% endif %}


/**
 * {{ Query }}
 */
class {{ Query }} extends{% if parentQuery %} {{ parentQuery}}{% else %} {{ BaseQuery }}{% endif %}
{

	/**
	 * initialization
	 */
	protected function init(){
		$this->setDefaultColumn("{{ Bean }}.*");
		$this->from({{ Bean }}::TABLENAME, "{{ Bean }}");
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
		$this->innerJoin{{ auxTable.getObject().toUpperCamelCase() }}('{{ auxTable.getObject().toUpperCamelCase() }}');
{% endif%}
{% endfor %}
	}

	/**
	 * (non-PHPdoc)
	 * @see Query.Query::createCriteria()
	 */
	protected function createCriteria(){
		return new {{ Criteria }}($this);
	}

	/**
	 * (non-PHPdoc)
	 * @see {{ classes.get('BaseQuery') }}::getCatalog()
	 */
	protected function getCatalog(){
		return {{ Catalog }}::getInstance();
	}

{% for foreignKey in foreignKeys %}
	/**
	 * @return {{ Query }}
	 */
	public function innerJoin{{ foreignKey.getForeignTable().getObject() }}($alias = null)
	{
		if( null == $alias ){
			$alias = {{ foreignKey.getForeignTable().getObject().toUpperCamelCase() }}::TABLENAME;
		}

		$this->innerJoinOn({{ foreignKey.getForeignTable().getObject().toUpperCamelCase() }}::TABLENAME, $alias)
			->equalFields('{{ Bean }}.{{ foreignKey.getLocal() }}', "$alias.{{ foreignKey.getForeign() }}");

		return $this;
	}

{% endfor %}
}