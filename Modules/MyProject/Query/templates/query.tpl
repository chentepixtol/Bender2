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
		$this->from("{{ table.getName() }}", "{{ Bean }}");
{% set auxTable = table %}
{% for i in 1..5 %}
{% if auxTable.hasParent() %}
{% set auxTable = auxTable.getParent() %}
		$this->innerJoinUsing('{{ auxTable.getName() }}','{{ auxTable.getPrimaryKey() }}');
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

}