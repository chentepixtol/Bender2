{% include 'header.tpl' %}

class {{ table.getObject().toUpperCamelCase() }} {% if table.hasParent %}extends {{ table.getParent().getObject().toUpperCamelCase() }} {% endif %}
{
	const TABLENAME = '{{ table.getName() }}';
}