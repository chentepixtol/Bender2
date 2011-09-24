{% include 'header.tpl' %}

{{ Criteria.printNamespace() }}

{% if parent %}
	{% set parentCriteria = classes.get(parent.getObject()~'Criteria') %}
{% else %}
use Query\Criteria;
use Query\Criterion;
{{ Bean.printUse() }}
{% endif %}

/**
 * {{ Criteria }}
 */
class {{ Criteria }} extends {% if parent %}{{ parentCriteria }}{% else %}Criteria{% endif %}
{

	/**
	 * fromArray
	 * @param array $fields
	 * @return {{ Criteria }}
	 */
	public function fromArray($fields)
	{
{% for field in fields %}
		if( array_key_exists('{{ field }}', $fields) ){
			$this->add{{ field.getName().toUpperCamelCase() }}($fields['{{ field }}']);
		}
{% endfor %}

		return $this;
	}

	/**
	 * Filter
	 * @param array $params
	 * @return {{ Criteria }}
	 */
	public function filter($params){
		return $this->fromArray($params);
	}

{% for field in fields %}
	/**
	 * add filter by {{ field.getName().toUpperCase() }}
	 * @param mixed ${{ field.getName().toCamelCase() }}
	 * @param string $comparison
	 * @param string $mutatorColumn
	 * @param string $mutatorValue
	 * @return {{ Criteria }}
	 */
	public function add{{ field.getName().toUpperCamelCase() }}(${{ field.getName().toCamelCase() }}, $comparison = Criterion::AUTO, $mutatorColumn = null, $mutatorValue = null)
	{
		$this->add({{ Bean }}::{{ field.getName().toUpperCase() }}, ${{ field.getName().toCamelCase() }}, $comparison, $mutatorColumn, $mutatorValue);
		return $this;
	}

{% endfor %}

}