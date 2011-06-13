{% include 'header.tpl' %}


class {{ Bean }} {% if table.hasParent() %}extends {{ table.getParent().getObject().toUpperCamelCase() }} {% endif %}
{
	const TABLENAME = '{{ table.getName() }}';

}