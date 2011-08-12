{% include 'header.tpl' %}

{{ Criteria.printNamespace() }}

{% if parent %}
	{% set parentCriteria = classes.get(parent.getObject()~'Criteria') %}
{% else %}
use Query\Criteria;
{% endif %}

/**
 * {{ Criteria }}
 */
class {{ Criteria }} extends {% if parent %}{{ parentCriteria }}{% else %}Criteria{% endif %}
{

}